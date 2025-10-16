<?php
// Single email processor: validate -> domain verify -> smtp verify
// Accepts POST: email, user_id, optional user_name

require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Use the existing DB-backed token validator and require a Bearer token
require_once __DIR__ . '/../oauth/validate_token.php';
require_bearer();

// Require that the authenticated user's email is verified
try {
    // Ensure the column exists (best effort, ignore errors if not permitted)
    try {
        $colRes = $conn->query("SHOW COLUMNS FROM users LIKE 'email_verified'");
        if ($colRes && $colRes->num_rows === 0) {
            @$conn->query("ALTER TABLE users ADD COLUMN email_verified TINYINT(1) NOT NULL DEFAULT 0");
        }
    } catch (Throwable $e) {
        // ignore
    }

    $authUserId = isset($GLOBALS['oauth_user_id']) ? (int)$GLOBALS['oauth_user_id'] : 0;
    if ($authUserId <= 0) {
        http_response_code(401);
        echo json_encode(['error' => 'unauthorized', 'message' => 'Missing or invalid authentication']);
        exit;
    }
    $stmtChk = $conn->prepare('SELECT email_verified FROM users WHERE id = ? LIMIT 1');
    if ($stmtChk) {
        $stmtChk->bind_param('i', $authUserId);
        $stmtChk->execute();
        $resChk = $stmtChk->get_result();
        if (!$rowChk = $resChk->fetch_assoc()) {
            $stmtChk->close();
            http_response_code(401);
            echo json_encode(['error' => 'unauthorized', 'message' => 'User not found']);
            exit;
        }
        $stmtChk->close();
        $isVerified = (int)($rowChk['email_verified'] ?? 0) === 1;
        if (!$isVerified) {
            http_response_code(403);
            echo json_encode(['error' => 'forbidden', 'message' => 'Email not verified. Please verify your email to use this endpoint.']);
            exit;
        }
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'server_error']);
        exit;
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'server_error']);
    exit;
}

// Simple logger
function sip_log($msg)
{
    $dir = __DIR__ . '/../storage';
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    @file_put_contents($dir . '/single_email_processor.log', date('c') . ' ' . $msg . "\n", FILE_APPEND);
}

// Helper: build the minimal compact response
function build_compact($email, $statusLabel, $domain, $resolved_ip, $smtp_status, $ehlo, $rcpt, $reason)
{
    // production compact response: exclude client_ip and reason
    return [
        'email' => $email ?? '',
        'status' => $statusLabel ?? 'unknown',
        'domain' => $domain ?? '',
        'resolved_ip' => $resolved_ip ?? '',
        'smtp_status' => $smtp_status ?? 'unknown',
        'EHLO' => $ehlo ? 'valid' : 'invalid',
        'RCPT_to' => $rcpt ? 'valid' : 'invalid'
    ];
}

// Helpers copied/condensed from existing code
function normalize_gmail_for_processor($email)
{
    $parts = explode('@', strtolower(trim($email)));
    if (count($parts) !== 2) return $email;
    if ($parts[1] !== 'gmail.com') return $email;
    $local = explode('+', $parts[0])[0];
    $local = str_replace('.', '', $local);
    return $local . '@gmail.com';
}

function is_valid_account_name($account)
{
    if (!preg_match('/^[a-z0-9](?!.*[._-]{2})[a-z0-9._-]*[a-z0-9]$/i', $account)) return false;
    if (strlen($account) < 1 || strlen($account) > 64) return false;
    if (preg_match('/^[0-9]+$/', $account)) return false;
    return true;
}

// Basic full-email validation including account checks and IDN-safe domain handling
function is_valid_email($email)
{
    $email = trim($email);
    if ($email === '') return false;
    // quick filter
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
    // split and verify lengths
    $parts = explode('@', $email);
    if (count($parts) !== 2) return false;
    $local = $parts[0];
    $domain = $parts[1];
    if (strlen($local) < 1 || strlen($local) > 64) return false;
    if (strlen($domain) < 1 || strlen($domain) > 255) return false;
    // account name rules
    if (!is_valid_account_name($local)) return false;
    return true;
}

function get_excluded_accounts($conn)
{
    $out = [];
    $tbl = $conn->query("SHOW TABLES LIKE 'exclude_accounts'");
    if (!$tbl || $tbl->num_rows === 0) return $out;
    $res = $conn->query("SELECT account FROM exclude_accounts");
    if (!$res) return $out;
    while ($r = $res->fetch_assoc()) $out[] = strtolower(trim($r['account']));
    return $out;
}

function get_excluded_domains_with_ips($conn)
{
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
function resolve_domain($conn, $email_id)
{
    $stmt = $conn->prepare("SELECT sp_domain FROM emails WHERE id = ? LIMIT 1");
    if (!$stmt) return ['error' => 'db_prepare'];
    $stmt->bind_param('i', $email_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if (!$row = $res->fetch_assoc()) {
        $stmt->close();
        return ['error' => 'not_found'];
    }
    $domain = $row['sp_domain'];
    $stmt->close();

    $ips = [];
    // collect MX records (all) and resolve each to A/AAAA
    $mx = @dns_get_record($domain, DNS_MX);
    if ($mx && is_array($mx)) {
        usort($mx, function ($a, $b) {
            return ($a['pri'] ?? 0) - ($b['pri'] ?? 0);
        });
        foreach ($mx as $m) {
            $host = rtrim($m['target'] ?? '', '.');
            if ($host === '') continue;
            // try A/AAAA records
            $arec = @dns_get_record($host, DNS_A + DNS_AAAA);
            if ($arec && is_array($arec)) {
                foreach ($arec as $r) {
                    if (!empty($r['ip']) && filter_var($r['ip'], FILTER_VALIDATE_IP)) $ips[] = $r['ip'];
                    if (!empty($r['ipv6']) && filter_var($r['ipv6'], FILTER_VALIDATE_IP)) $ips[] = $r['ipv6'];
                }
            }
            // fallback to host lookup
            if (empty($arec) || empty($ips)) {
                $hosts = @gethostbynamel($host);
                if ($hosts && is_array($hosts)) {
                    foreach ($hosts as $h) {
                        if (filter_var($h, FILTER_VALIDATE_IP)) $ips[] = $h;
                    }
                }
            }
        }
    }
    // if still empty, try A/AAAA on the domain itself
    if (empty($ips)) {
        $arec = @dns_get_record($domain, DNS_A + DNS_AAAA);
        if ($arec && is_array($arec)) {
            foreach ($arec as $r) {
                if (!empty($r['ip']) && filter_var($r['ip'], FILTER_VALIDATE_IP)) $ips[] = $r['ip'];
                if (!empty($r['ipv6']) && filter_var($r['ipv6'], FILTER_VALIDATE_IP)) $ips[] = $r['ipv6'];
            }
        } else {
            $hosts = @gethostbynamel($domain);
            if ($hosts && is_array($hosts)) {
                foreach ($hosts as $h) {
                    if (filter_var($h, FILTER_VALIDATE_IP)) $ips[] = $h;
                }
            }
        }
    }

    $ips = array_values(array_unique($ips));
    $status = empty($ips) ? 0 : 1;
    $message = $status ? implode(',', $ips) : 'No MX/A records';
    $up = $conn->prepare("UPDATE emails SET domain_verified = 1, domain_status = ?, validation_response = ? WHERE id = ?");
    if ($up) {
        $up->bind_param('isi', $status, $message, $email_id);
        $up->execute();
        $up->close();
    }
    return ['domain' => $domain, 'resolved' => $status === 1, 'ips' => $ips, 'message' => $message, 'domain_status' => $status];
}

// SMTP verification (EHLO server.relyon.co.in, MAIL FROM info@relyon.co.in)
function smtp_verify($conn, $email_id)
{
    $stmt = $conn->prepare("SELECT raw_emailid, sp_domain FROM emails WHERE id = ? LIMIT 1");
    if (!$stmt) return ['error' => 'db_prepare'];
    $stmt->bind_param('i', $email_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if (!$row = $res->fetch_assoc()) {
        $stmt->close();
        return ['error' => 'not_found'];
    }
    $email = $row['raw_emailid'];
    $domain = $row['sp_domain'];
    $stmt->close();

    $result = ['email' => $email, 'domain' => $domain, 'attempted' => false, 'ip' => null, 'steps' => ['smtp_connection' => false, 'ehlo' => false, 'mail_from' => false, 'rcpt_to' => false], 'validation_status' => 'invalid', 'validation_response' => null, 'domain_status' => 0];

    // build candidate hosts/IPs: MX hosts first, then domain A records
    $candidates = [];
    $mxhosts = [];
    if (getmxrr($domain, $mxhosts, $mxprio) && !empty($mxhosts)) {
        // pair priority and host
        $mxPairs = [];
        for ($i = 0; $i < count($mxhosts); $i++) $mxPairs[] = ['host' => $mxhosts[$i], 'pri' => $mxprio[$i] ?? 0];
        usort($mxPairs, function ($a, $b) {
            return $a['pri'] - $b['pri'];
        });
        foreach ($mxPairs as $p) {
            $host = rtrim($p['host'], '.');
            $addrs = @gethostbynamel($host);
            if ($addrs && is_array($addrs)) foreach ($addrs as $a) if (filter_var($a, FILTER_VALIDATE_IP)) $candidates[] = ['host' => $host, 'ip' => $a];
        }
    }
    // domain A/AAAA fallback
    if (empty($candidates)) {
        $addrs = @gethostbynamel($domain);
        if ($addrs && is_array($addrs)) foreach ($addrs as $a) if (filter_var($a, FILTER_VALIDATE_IP)) $candidates[] = ['host' => $domain, 'ip' => $a];
    }

    if (empty($candidates)) {
        $msg = 'No valid MX or A records found for SMTP';
        $up = $conn->prepare("UPDATE emails SET domain_status = 0, domain_processed = 1, validation_status = ?, validation_response = ? WHERE id = ?");
        if ($up) {
            $s = 'invalid';
            $up->bind_param('ssi', $s, $msg, $email_id);
            $up->execute();
            $up->close();
        }
        $result['validation_response'] = $msg;
        return $result;
    }

    $attempted = false;
    $finalCode = null;
    $finalResp = '';
    $port = 25;
    $timeout = 6; // smaller timeout for each attempt
    // try multiple candidates until a conclusive answer
    foreach ($candidates as $cand) {
        $ip = $cand['ip'];
        $attempted = true;
        $result['ip'] = $ip;
        $errNo = 0;
        $errStr = '';
        $smtp = @stream_socket_client("tcp://$ip:$port", $errNo, $errStr, $timeout);
        if (!$smtp) {
            $finalResp = "Connect failed to $ip: $errStr";
            continue;
        }
        // mark connection success
        $result['steps']['smtp_connection'] = true;
        stream_set_timeout($smtp, $timeout);
        $banner = fgets($smtp, 4096);
        if ($banner === false || substr($banner, 0, 3) !== '220') {
            fclose($smtp);
            $finalResp = 'SMTP banner not ready';
            continue;
        }

        // EHLO
        fputs($smtp, "EHLO server.relyon.co.in\r\n");
        $ehloLines = [];
        while (($line = fgets($smtp, 4096)) !== false) {
            $ehloLines[] = $line;
            // lines that start with code + space are final
            if (strlen($line) >= 4 && $line[3] === ' ') break;
            // safety: break if too many lines
            if (count($ehloLines) > 30) break;
        }
        if (empty($ehloLines) || substr($ehloLines[0], 0, 3) !== '250') {
            fclose($smtp);
            $finalResp = 'EHLO failed';
            continue;
        }
        $result['steps']['ehlo'] = true;

        // MAIL FROM
        fputs($smtp, "MAIL FROM:<info@relyon.co.in>\r\n");
        $mfr = fgets($smtp, 4096);
        if ($mfr === false || substr($mfr, 0, 3) !== '250') {
            fclose($smtp);
            $finalResp = 'MAIL FROM rejected';
            continue;
        }
        $result['steps']['mail_from'] = true;

        // RCPT TO
        fputs($smtp, "RCPT TO:<$email>\r\n");
        $rcpt = fgets($smtp, 4096);
        $code = $rcpt !== false ? substr($rcpt, 0, 3) : null;
        $result['steps']['rcpt_to'] = ($code == '250' || $code == '251');
        fputs($smtp, "QUIT\r\n");
        fclose($smtp);

        $finalCode = $code;
        $finalResp = $rcpt !== false ? $rcpt : '';
        // map codes
        if ($code == '250' || $code == '251') {
            $status = 'valid';
            $ds = 1;
        } elseif (in_array($code, ['450', '451', '452'])) {
            $status = 'retryable';
            $ds = 2;
        } elseif (in_array($code, ['421', '451']) || $code === null) {
            $status = 'retryable';
            $ds = 2;
        } else {
            $status = 'invalid';
            $ds = 0;
        }

        // update DB and return early on conclusive responses
        $up = $conn->prepare("UPDATE emails SET domain_status = ?, domain_processed = 1, validation_status = ?, validation_response = ? WHERE id = ?");
        if ($up) {
            $up->bind_param('issi', $ds, $status, $finalResp, $email_id);
            $up->execute();
            $up->close();
        }
        $result['validation_status'] = $status;
        $result['domain_status'] = $ds;
        $result['validation_response'] = $finalResp;
        $result['attempted'] = $attempted;
        return $result;
    }

    // if reached without conclusive reply
    $msg = $finalResp !== '' ? $finalResp : 'No SMTP response from any host';
    $up = $conn->prepare("UPDATE emails SET domain_status = 0, domain_processed = 1, validation_status = ?, validation_response = ? WHERE id = ?");
    if ($up) {
        $s = 'invalid';
        $up->bind_param('ssi', $s, $msg, $email_id);
        $up->execute();
        $up->close();
    }
    $result['validation_response'] = $msg;
    $result['attempted'] = $attempted;
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
        http_response_code(405);
        echo json_encode(['error' => 'method_not_allowed']);
        exit;
    }

    // Default behavior: return compact/minimal JSON unless caller requests full output via full=1
    $full_requested = false;
    $rawFullFlag = $input['full'] ?? $input['full_response'] ?? $input['full_output'] ?? null;
    if (!is_null($rawFullFlag)) {
        $tmp = filter_var($rawFullFlag, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $full_requested = ($tmp === null) ? false : $tmp;
    }

    // user_id is optional now; use 0 for anonymous/non-authenticated calls.
    // If protected by oauth/validate_token.php upstream, map oauth token client to user_id
    $user_id = intval($input['user_id'] ?? 0);
    if ($user_id === 0) {
        if (isset($GLOBALS['oauth_user_id'])) {
            $user_id = (int)$GLOBALS['oauth_user_id'];
        } elseif (isset($GLOBALS['oauth_client_id']) && is_string($GLOBALS['oauth_client_id'])) {
            if (preg_match('/^(verify|api):user:(\d+)$/', $GLOBALS['oauth_client_id'], $m)) {
                $user_id = intval($m[2]);
            }
        }
    }
    $user_name = trim($input['user_name'] ?? '');
    // accept email, raw_email, or email_id as input keys (email_id kept for backward compatibility)
    $input_email = trim($input['email'] ?? $input['raw_email'] ?? $input['email_id'] ?? '');
    // no longer require user_id; proceed with anonymous (user_id=0) if not provided
    if ($input_email === '') {
        http_response_code(400);
        echo json_encode(build_compact('', 'invalid', '', '', 'invalid', false, false, 'email is required'));
        exit;
    }

    // Normalize and sanitize like CSV flow
    $raw_input_email = $input_email;
    $email = normalize_gmail_for_processor($input_email);
    $email = preg_replace('/[^\x20-\x7E]/', '', $email);
    $emailKey = strtolower($email);

    // capture client IP for logging and storage
    function get_client_ip()
    {
        $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        foreach ($keys as $k) {
            if (!empty($_SERVER[$k])) {
                $val = $_SERVER[$k];
                // X-Forwarded-For may contain multiple IPs, take the first
                if ($k === 'HTTP_X_FORWARDED_FOR' && strpos($val, ',') !== false) $val = trim(explode(',', $val)[0]);
                if (filter_var($val, FILTER_VALIDATE_IP)) return $val;
            }
        }
        return null;
    }
    $client_ip = get_client_ip();

    // validate full email before DB work
    if (!is_valid_email($email)) {
        http_response_code(400);
        echo json_encode(build_compact($input_email, 'invalid', '', '', 'invalid', false, false, 'Invalid email format'));
        exit;
    }

    // Duplicate check: if the email already exists, reuse the row (do not error).
    // When a user_id is provided (>0) prefer user-scoped duplicate lookup so each user has their own entries.
    $existing = false;
    $email_id = null;
    if ($user_id > 0) {
        $stmt = $conn->prepare("SELECT id FROM emails WHERE user_id = ? AND LOWER(raw_emailid) = LOWER(?) LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('is', $user_id, $email);
            $stmt->execute();
            $r = $stmt->get_result();
            if ($r && $row = $r->fetch_assoc()) {
                $existing = true;
                $email_id = intval($row['id']);
            }
            $stmt->close();
        }
    } else {
        $stmt = $conn->prepare("SELECT id FROM emails WHERE LOWER(raw_emailid) = LOWER(?) LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $r = $stmt->get_result();
            if ($r && $row = $r->fetch_assoc()) {
                $existing = true;
                $email_id = intval($row['id']);
            }
            $stmt->close();
        }
    }

    list($sp_account, $sp_domain) = explode('@', $email) + [null, null];
    if ($sp_account === null || !is_valid_account_name($sp_account)) {
        echo json_encode(build_compact($email, 'invalid', $sp_domain, '', 'invalid', false, false, 'Invalid account name in email'));
        exit;
    }

    // Exclusions
    $excludedAccounts = get_excluded_accounts($conn);
    $excludedDomains = get_excluded_domains_with_ips($conn);
    $domain_verified = 0;
    $domain_status = 0;
    $validation_response = 'Not Verified Yet';
    if (in_array(strtolower($sp_account), $excludedAccounts)) {
        $domain_verified = 1;
        $domain_status = 1;
        $validation_response = 'Excluded: Account';
    } elseif (array_key_exists(strtolower($sp_domain), $excludedDomains)) {
        $domain_verified = 1;
        $domain_status = 1;
        $validation_response = $excludedDomains[strtolower($sp_domain)];
    }

    // Insert into emails only when not existing
    if (!$existing) {
        // ensure client_ip column exists (safe to run; will be skipped if already present)
        $colCheck = $conn->query("SHOW COLUMNS FROM emails LIKE 'client_ip'");
        if ($colCheck && $colCheck->num_rows === 0) {
            // best-effort alter; ignore errors to avoid hard failures on restricted DB users
            @$conn->query("ALTER TABLE emails ADD COLUMN client_ip VARCHAR(45) DEFAULT NULL AFTER domain_processed");
        }
        $dp = 0;
        if ($user_id > 0) {
            $ins = $conn->prepare("INSERT INTO emails (user_id, raw_emailid, sp_account, sp_domain, domain_verified, domain_status, validation_response, domain_processed, client_ip, csv_list_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)");
            if (!$ins) {
                echo json_encode(build_compact($email, 'invalid', $sp_domain, '', 'invalid', false, false, 'DB insert prepare failed: ' . $conn->error));
                exit;
            }
            $ins->bind_param('isssiisis', $user_id, $email, $sp_account, $sp_domain, $domain_verified, $domain_status, $validation_response, $dp, $client_ip);
        } else {
            $ins = $conn->prepare("INSERT INTO emails (raw_emailid, sp_account, sp_domain, domain_verified, domain_status, validation_response, domain_processed, client_ip, csv_list_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL)");
            if (!$ins) {
                echo json_encode(build_compact($email, 'invalid', $sp_domain, '', 'invalid', false, false, 'DB insert prepare failed: ' . $conn->error));
                exit;
            }
            $ins->bind_param('sssiisis', $email, $sp_account, $sp_domain, $domain_verified, $domain_status, $validation_response, $dp, $client_ip);
        }

        if (!$ins->execute()) {
            $err = $ins->error;
            $ins->close();
            echo json_encode(build_compact($email, 'invalid', $sp_domain, '', 'invalid', false, false, 'Insert failed: ' . $err));
            exit;
        }
        $email_id = $conn->insert_id;
        $ins->close();
    }

    $out = ['status' => 'success', 'email_id' => $email_id, 'email' => $email, 'account' => $sp_account, 'domain' => $sp_domain, 'excluded' => ($domain_verified === 1)];

    if ($domain_verified === 1) {
        // skip verification if excluded
        $out['validation'] = ['domain' => ['domain_verified' => 1, 'domain_status' => $domain_status, 'message' => $validation_response], 'smtp' => null, 'overall' => 'excluded'];
        // build minimal compact response for excluded entries
        $excluded_compact = [
            'email' => $email,
            'status' => 'excluded',
            'domain' => $sp_domain,
            'resolved_ip' => '',
            'smtp_status' => 'excluded',
            'EHLO' => 'invalid',
            'RCPT_to' => 'invalid',
            'reason' => $validation_response
        ];
        if (!empty($full_requested)) {
            echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            echo json_encode(build_compact($email, 'excluded', $sp_domain, '', 'excluded', false, false, $validation_response), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        exit;
    }

    // Run domain resolution
    $domainResult = resolve_domain($conn, $email_id);
    $out['validation']['domain'] = $domainResult;

    // Run SMTP verification
    $smtpResult = smtp_verify($conn, $email_id);
    $out['validation']['smtp'] = $smtpResult;

    // Prefer to show a single, primary IP for quick UI: use SMTP IP if available, otherwise the first resolved IP
    $primaryIp = null;
    if (!empty($smtpResult['ip']) && filter_var($smtpResult['ip'], FILTER_VALIDATE_IP)) {
        $primaryIp = $smtpResult['ip'];
    } elseif (!empty($domainResult['ips']) && is_array($domainResult['ips'])) {
        // pick first IPv4 preferred, otherwise first available
        $ipv4 = null;
        $ipv6 = null;
        foreach ($domainResult['ips'] as $candidate) {
            if (filter_var($candidate, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && $ipv4 === null) $ipv4 = $candidate;
            if (filter_var($candidate, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) && $ipv6 === null) $ipv6 = $candidate;
        }
        $primaryIp = $ipv4 ?? $ipv6 ?? ($domainResult['ips'][0] ?? null);
    }
    if ($primaryIp) {
        $out['validation']['domain']['ips'] = [$primaryIp];
        $out['validation']['domain']['message'] = $primaryIp;
    } else {
        $out['validation']['domain']['ips'] = [];
        $out['validation']['domain']['message'] = $domainResult['message'] ?? 'No IPs';
    }

    // Overall decision
    $overall = 'invalid';
    if (!empty($smtpResult['validation_status'])) {
        if ($smtpResult['validation_status'] === 'valid') $overall = 'valid';
        elseif ($smtpResult['validation_status'] === 'retryable') $overall = 'retryable';
        else $overall = 'invalid';
    } elseif (!empty($domainResult['resolved'])) {
        $overall = 'unknown';
    }
    $out['validation']['overall'] = $overall;

    // Quick, human-friendly summary for fast decisions
    $quick_label = $overall;
    $quick_reason = '';
    // excluded case
    if (!empty($out['excluded']) && $out['excluded'] === true) {
        $quick_label = 'excluded';
        $quick_reason = $validation_response ?? ($out['validation']['domain']['message'] ?? 'Excluded by rules');
    } elseif (!empty($smtpResult['validation_status'])) {
        $quick_label = $smtpResult['validation_status'];
        $quick_reason = $smtpResult['validation_response'] ?? '';
    } elseif (!empty($domainResult['resolved'])) {
        $quick_label = 'domain_resolved_only';
        $quick_reason = $domainResult['message'] ?? 'Domain resolves but SMTP not verified';
    } else {
        $quick_label = 'invalid';
        $quick_reason = $domainResult['message'] ?? ($smtpResult['validation_response'] ?? 'No MX/A records or SMTP response');
    }

    // Map to a simple boolean when possible (only `valid` => true)
    $quick_valid_bool = ($quick_label === 'valid');
    $out['result'] = $overall; // machine-friendly top-level result
    $out['quick_status'] = ['label' => $quick_label, 'is_valid' => $quick_valid_bool, 'reason' => $quick_reason];

    // Very short summary for quick consumption (single-line reason)
    $short_reason = is_string($quick_reason) ? preg_replace('/[\r\n\t]+/', ' ', trim($quick_reason)) : '';
    if (strlen($short_reason) > 180) $short_reason = substr($short_reason, 0, 177) . '...';
    $out['valid'] = $quick_valid_bool;
    $out['status'] = $quick_label;
    $out['reason'] = $short_reason;

    // Update csv_list counts for the related list(s) if any (safe, minimal)
    // We only touch csv_list stats aggregated from emails (keep relation only)
    $conn->query(
        "UPDATE csv_list cl JOIN (SELECT csv_list_id, COUNT(*) AS total_emails, SUM(CASE WHEN domain_status=1 THEN 1 ELSE 0 END) AS valid_count, SUM(CASE WHEN domain_status=0 THEN 1 ELSE 0 END) AS invalid_count FROM emails WHERE csv_list_id IS NOT NULL GROUP BY csv_list_id) e ON e.csv_list_id = cl.id SET cl.total_emails = e.total_emails, cl.valid_count = e.valid_count, cl.invalid_count = e.invalid_count"
    );

    // Compact response suitable for quick consumption (exact minimal fields)
    $compact = [
        'email' => $email,
        'status' => $out['result'] ?? ($smtpResult['validation_status'] ?? 'unknown'),
        'domain' => $sp_domain,
        'resolved_ip' => $primaryIp ?? '',
        'smtp_status' => $smtpResult['validation_status'] ?? 'unknown',
        'EHLO' => (!empty($smtpResult['steps']['ehlo']) ? 'valid' : 'invalid'),
        'RCPT_to' => (!empty($smtpResult['steps']['rcpt_to']) ? 'valid' : 'invalid')
    ];
    $out['compact'] = $compact;

    // write a simple request log into email_client_logs for analytics/debugging
    try {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $route = $_SERVER['REQUEST_URI'] ?? null;
        $logStmt = $conn->prepare("INSERT INTO email_client_logs (email_id, raw_email, client_ip, user_id, user_agent, route) VALUES (?, ?, ?, ?, ?, ?)");
        if ($logStmt) {
            $logStmt->bind_param('ississ', $email_id, $email, $client_ip, $user_id, $ua, $route);
            $logStmt->execute();
            $logStmt->close();
        }
    } catch (Exception $e) {
        // do not fail main request on logging error
        sip_log('email_client_logs insert error: ' . $e->getMessage());
    }

    // Final output: by default return only compact minimal JSON; return full output only when full_requested is true
    if (!empty($full_requested)) {
        echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        echo json_encode($out['compact'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
} catch (Exception $e) {
    $errMsg = $e->getMessage();
    sip_log('Exception: ' . $errMsg . "\n" . $e->getTraceAsString());
    // write an explicit log file as well
    $logFile = __DIR__ . '/../storage/single_email_processor.log';
    @file_put_contents($logFile, date('c') . ' Exception: ' . $errMsg . "\n" . $e->getTraceAsString() . "\n\n", FILE_APPEND);
    // return a safer error payload but include message for local debugging
    http_response_code(500);
    // return compact-shaped error
    echo json_encode(build_compact($input_email ?? '', 'invalid', '', '', 'invalid', false, false, 'Server error: ' . $errMsg));
}

exit;
