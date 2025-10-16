<?php
// Single email processor: validate -> domain verify -> smtp verify
// Accepts POST: email, user_id, optional user_name

require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

// Simple logger
function sip_log($msg) {
    $dir = __DIR__ . '/../storage';
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    @file_put_contents($dir . '/single_email_processor.log', date('c') . ' ' . $msg . "\n", FILE_APPEND);
}

// Helpers copied/condensed from existing code
function normalize_gmail_for_processor($email) {
    $parts = explode('@', strtolower(trim($email)));
    if (count($parts) !== 2) return $email;
    if ($parts[1] !== 'gmail.com') return $email;
    $local = explode('+', $parts[0])[0];
    $local = str_replace('.', '', $local);
    return $local . '@gmail.com';
}

function is_valid_account_name($account) {
    if (!preg_match('/^[a-z0-9](?!.*[._-]{2})[a-z0-9._-]*[a-z0-9]$/i', $account)) return false;
    if (strlen($account) < 1 || strlen($account) > 64) return false;
    if (preg_match('/^[0-9]+$/', $account)) return false;
    return true;
}

function get_excluded_accounts($conn) {
    $out = [];
    $tbl = $conn->query("SHOW TABLES LIKE 'exclude_accounts'");
    if (!$tbl || $tbl->num_rows === 0) return $out;
    $res = $conn->query("SELECT account FROM exclude_accounts");
    if (!$res) return $out;
    while ($r = $res->fetch_assoc()) $out[] = strtolower(trim($r['account']));
    return $out;
}

function get_excluded_domains_with_ips($conn) {
    $out = [];
    $tbl = $conn->query("SHOW TABLES LIKE 'exclude_domains'");
    if (!$tbl || $tbl->num_rows === 0) return $out;
    $res = $conn->query("SELECT domain, ip_address FROM exclude_domains");
    if (!$res) return $out;
    while ($r = $res->fetch_assoc()) {
        $d = strtolower(trim($r['domain']));
        $ip = trim($r['ip_address']);
        if ($d !== '') $out[$d] = $ip;
    }
    return $out;
}

// Domain resolution and update
function resolve_domain($conn, $email_id) {
    $stmt = $conn->prepare("SELECT sp_domain FROM emails WHERE id = ? LIMIT 1");
    if (!$stmt) return ['error'=>'db_prepare'];
    $stmt->bind_param('i', $email_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if (!$row = $res->fetch_assoc()) { $stmt->close(); return ['error'=>'not_found']; }
    $domain = $row['sp_domain'];
    $stmt->close();

    $ips = [];
    $mx = @dns_get_record($domain, DNS_MX);
    if ($mx && is_array($mx)) {
        usort($mx, function($a,$b){ return ($a['pri'] ?? 0) - ($b['pri'] ?? 0); });
        foreach ($mx as $m) {
            $host = $m['target'] ?? null; if (!$host) continue;
            $arec = @dns_get_record($host, DNS_A + DNS_AAAA);
            if ($arec && is_array($arec)) {
                foreach ($arec as $r) {
                    if (!empty($r['ip']) && filter_var($r['ip'], FILTER_VALIDATE_IP)) $ips[] = $r['ip'];
                    if (!empty($r['ipv6']) && filter_var($r['ipv6'], FILTER_VALIDATE_IP)) $ips[] = $r['ipv6'];
                }
            } else {
                $h = @gethostbyname($host);
                if ($h && $h !== $host && filter_var($h, FILTER_VALIDATE_IP)) $ips[] = $h;
            }
            if (!empty($ips)) break;
        }
    }
    if (empty($ips)) {
        $arec = @dns_get_record($domain, DNS_A + DNS_AAAA);
        if ($arec && is_array($arec)) {
            foreach ($arec as $r) {
                if (!empty($r['ip']) && filter_var($r['ip'], FILTER_VALIDATE_IP)) $ips[] = $r['ip'];
                if (!empty($r['ipv6']) && filter_var($r['ipv6'], FILTER_VALIDATE_IP)) $ips[] = $r['ipv6'];
            }
        } else {
            $h = @gethostbyname($domain);
            if ($h && $h !== $domain && filter_var($h, FILTER_VALIDATE_IP)) $ips[] = $h;
        }
    }

    $status = empty($ips) ? 0 : 1;
    $message = $status ? implode(',', $ips) : 'No MX/A records';
    $up = $conn->prepare("UPDATE emails SET domain_verified = 1, domain_status = ?, validation_response = ? WHERE id = ?");
    if ($up) { $up->bind_param('isi', $status, $message, $email_id); $up->execute(); $up->close(); }
    return ['domain'=>$domain,'resolved'=>$status===1,'ips'=>$ips,'message'=>$message,'domain_status'=>$status];
}

// SMTP verification (EHLO server.relyon.co.in, MAIL FROM info@relyon.co.in)
function smtp_verify($conn, $email_id) {
    $stmt = $conn->prepare("SELECT raw_emailid, sp_domain FROM emails WHERE id = ? LIMIT 1");
    if (!$stmt) return ['error'=>'db_prepare'];
    $stmt->bind_param('i', $email_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if (!$row = $res->fetch_assoc()) { $stmt->close(); return ['error'=>'not_found']; }
    $email = $row['raw_emailid']; $domain = $row['sp_domain']; $stmt->close();

    $result = ['email'=>$email,'domain'=>$domain,'attempted'=>false,'ip'=>null,'steps'=>['smtp_connection'=>false,'ehlo'=>false,'mail_from'=>false,'rcpt_to'=>false],'validation_status'=>'invalid','validation_response'=>null,'domain_status'=>0];

    // find mail host
    $ip = false;
    if (getmxrr($domain, $mxhosts) && !empty($mxhosts)) {
        $mxIp = @gethostbyname($mxhosts[0]); if ($mxIp !== $mxhosts[0] && filter_var($mxIp, FILTER_VALIDATE_IP)) { $ip = $mxIp; }
    }
    if (!$ip) { $a = @gethostbyname($domain); if ($a !== $domain && filter_var($a, FILTER_VALIDATE_IP)) $ip = $a; }
    if (!$ip) {
        $msg = 'No valid MX or A record found';
        $up = $conn->prepare("UPDATE emails SET domain_status = 0, domain_processed = 1, validation_status = ?, validation_response = ? WHERE id = ?");
        if ($up) { $s='invalid'; $up->bind_param('ssi',$s,$msg,$email_id); $up->execute(); $up->close(); }
        $result['attempted'] = false; $result['validation_response']=$msg; return $result;
    }

    $result['attempted'] = true; $result['ip'] = $ip;
    $port = 25; $timeout = 8;
    $smtp = @stream_socket_client("tcp://$ip:$port", $errno, $errstr, $timeout);
    if (!$smtp) {
        $msg = "Connection failed: $errstr";
        $up = $conn->prepare("UPDATE emails SET domain_status = 0, domain_processed = 1, validation_status = ?, validation_response = ? WHERE id = ?"); if ($up) { $s='invalid'; $up->bind_param('ssi',$s,$msg,$email_id); $up->execute(); $up->close(); }
        $result['validation_response'] = $msg; return $result;
    }
    $result['steps']['smtp_connection'] = true;
    stream_set_timeout($smtp, $timeout);
    $banner = fgets($smtp, 4096);
    if ($banner === false || substr($banner,0,3) !== '220') { fclose($smtp); $msg='SMTP not ready'; $up = $conn->prepare("UPDATE emails SET domain_status = 0, domain_processed = 1, validation_status = ?, validation_response = ? WHERE id = ?"); if ($up) { $s='invalid'; $up->bind_param('ssi',$s,$msg,$email_id); $up->execute(); $up->close(); } $result['validation_response']=$msg; return $result; }

    // EHLO using server.relyon.co.in
    fputs($smtp, "EHLO server.relyon.co.in\r\n");
    $ehlo_ok = false;
    while ($line = fgets($smtp, 4096)) { if (substr($line,3,1) == ' ') { $ehlo_ok = true; break; } }
    if (!$ehlo_ok) { fclose($smtp); $msg='EHLO failed'; $up = $conn->prepare("UPDATE emails SET domain_status = 0, domain_processed = 1, validation_status = ?, validation_response = ? WHERE id = ?"); if ($up) { $s='invalid'; $up->bind_param('ssi',$s,$msg,$email_id); $up->execute(); $up->close(); } $result['validation_response']=$msg; return $result; }
    $result['steps']['ehlo'] = true;

    // MAIL FROM using info@relyon.co.in
    fputs($smtp, "MAIL FROM:<info@relyon.co.in>\r\n");
    $mfr = fgets($smtp,4096);
    if ($mfr === false) { fclose($smtp); $msg='MAIL FROM failed'; $up = $conn->prepare("UPDATE emails SET domain_status = 0, domain_processed = 1, validation_status = ?, validation_response = ? WHERE id = ?"); if ($up) { $s='invalid'; $up->bind_param('ssi',$s,$msg,$email_id); $up->execute(); $up->close(); } $result['validation_response']=$msg; return $result; }
    $result['steps']['mail_from'] = true;

    fputs($smtp, "RCPT TO:<$email>\r\n");
    $rcpt = fgets($smtp,4096);
    $code = $rcpt !== false ? substr($rcpt,0,3) : null;
    $result['steps']['rcpt_to'] = ($code == '250' || $code == '251');
    fputs($smtp, "QUIT\r\n"); fclose($smtp);

    $validation_response = $rcpt !== false ? $rcpt : '';
    if ($code == '250' || $code == '251') { $s='valid'; $ds=1; }
    elseif (in_array($code,['450','451','452'])) { $s='retryable'; $ds=2; }
    else { $s='invalid'; $ds=0; }

    $up = $conn->prepare("UPDATE emails SET domain_status = ?, domain_processed = 1, validation_status = ?, validation_response = ? WHERE id = ?");
    if ($up) { $up->bind_param('issi',$ds,$s,$validation_response,$email_id); $up->execute(); $up->close(); }

    $result['validation_status'] = $s; $result['domain_status'] = $ds; $result['validation_response'] = $validation_response;
    return $result;
}

// MAIN: accept POST input, JSON body, or GET for quick testing; run validation, domain & smtp checks
try {
    // Gather input from POST form-data, JSON body, or GET (for quick manual testing)
    $input = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = $_POST;
        $raw = trim(file_get_contents('php://input'));
        if ($raw !== '') {
            $json = json_decode($raw, true);
            if (is_array($json)) {
                // JSON body fields take precedence
                $input = array_merge($input, $json);
            }
        }
    } else {
        // allow GET for quick tests (not recommended for production)
        $input = $_GET;
    }

    $user_id = intval($input['user_id'] ?? 0);
    $user_name = trim($input['user_name'] ?? '');
    $input_email = trim($input['email'] ?? $input['raw_email'] ?? '');

    if ($user_id <= 0) {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>'user_id is required and must be a positive integer']);
        exit;
    }
    if ($input_email === '') {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>'email is required']);
        exit;
    }

    // Normalize and sanitize like CSV flow
    $email = normalize_gmail_for_processor($input_email);
    $email = preg_replace('/[^\x20-\x7E]/', '', $email);
    $emailKey = strtolower($email);

    // Duplicate check for this user
    $stmt = $conn->prepare("SELECT id FROM emails WHERE user_id = ? AND LOWER(raw_emailid) = LOWER(?) LIMIT 1");
    if ($stmt) { $stmt->bind_param('is',$user_id,$email); $stmt->execute(); $r = $stmt->get_result(); if ($r && $r->fetch_assoc()) { $stmt->close(); echo json_encode(['status'=>'error','message'=>'Email already exists for this user']); exit; } $stmt->close(); }

    list($sp_account, $sp_domain) = explode('@', $email) + [null,null];
    if ($sp_account === null || !is_valid_account_name($sp_account)) { echo json_encode(['status'=>'error','message'=>'Invalid account name in email']); exit; }

    // Exclusions
    $excludedAccounts = get_excluded_accounts($conn);
    $excludedDomains = get_excluded_domains_with_ips($conn);
    $domain_verified = 0; $domain_status = 0; $validation_response = 'Not Verified Yet';
    if (in_array(strtolower($sp_account), $excludedAccounts)) { $domain_verified=1; $domain_status=1; $validation_response='Excluded: Account'; }
    elseif (array_key_exists(strtolower($sp_domain), $excludedDomains)) { $domain_verified=1; $domain_status=1; $validation_response=$excludedDomains[strtolower($sp_domain)]; }

    // Insert into emails
    $dp = 0;
    $ins = $conn->prepare("INSERT INTO emails (user_id, raw_emailid, sp_account, sp_domain, domain_verified, domain_status, validation_response, domain_processed, csv_list_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL)");
    if (!$ins) { echo json_encode(['status'=>'error','message'=>'DB insert prepare failed: '.$conn->error]); exit; }
    $ins->bind_param('isssiisi', $user_id, $email, $sp_account, $sp_domain, $domain_verified, $domain_status, $validation_response, $dp);
    if (!$ins->execute()) { $err = $ins->error; $ins->close(); echo json_encode(['status'=>'error','message'=>'Insert failed: '.$err]); exit; }
    $email_id = $conn->insert_id; $ins->close();

    $out = ['status'=>'success','email_id'=>$email_id,'email'=>$email,'account'=>$sp_account,'domain'=>$sp_domain,'excluded'=>($domain_verified===1)];

    if ($domain_verified === 1) {
        // skip verification if excluded
        $out['validation'] = ['domain'=>['domain_verified'=>1,'domain_status'=>$domain_status,'message'=>$validation_response],'smtp'=>null,'overall'=>'excluded'];
        echo json_encode($out); exit;
    }

    // Run domain resolution
    $domainResult = resolve_domain($conn, $email_id);
    $out['validation']['domain'] = $domainResult;

    // Run SMTP verification
    $smtpResult = smtp_verify($conn, $email_id);
    $out['validation']['smtp'] = $smtpResult;

    // Overall decision
    $overall = 'invalid';
    if (!empty($smtpResult['validation_status'])) {
        if ($smtpResult['validation_status'] === 'valid') $overall = 'valid';
        elseif ($smtpResult['validation_status'] === 'retryable') $overall = 'retryable';
        else $overall = 'invalid';
    } elseif (!empty($domainResult['resolved'])) { $overall = 'unknown'; }
    $out['validation']['overall'] = $overall;

    // Update csv_list counts for the related list(s) if any (safe, minimal)
    // We only touch csv_list stats aggregated from emails (keep relation only)
    $conn->query(
        "UPDATE csv_list cl JOIN (SELECT csv_list_id, COUNT(*) AS total_emails, SUM(CASE WHEN domain_status=1 THEN 1 ELSE 0 END) AS valid_count, SUM(CASE WHEN domain_status=0 THEN 1 ELSE 0 END) AS invalid_count FROM emails WHERE csv_list_id IS NOT NULL GROUP BY csv_list_id) e ON e.csv_list_id = cl.id SET cl.total_emails = e.total_emails, cl.valid_count = e.valid_count, cl.invalid_count = e.invalid_count"
    );

    echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (Exception $e) {
    sip_log('Exception: '.$e->getMessage());
    echo json_encode(['status'=>'error','message'=>'Server error']);
}

exit;
