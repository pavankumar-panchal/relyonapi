<?php
// Minimal Router for email verification and Google OAuth
require_once __DIR__ . '/../config/db.php';

function send_json($data, $code = 200)
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

function normalizeEmail($email)
{
    $email = urldecode(trim((string)$email));
    if (stripos($email, '@gamil.com') !== false) {
        $email = str_ireplace('@gamil.com', '@gmail.com', $email);
    }
    return $email;
}

$endpoint = $_GET['endpoint'] ?? null;
$fullPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Normalize script name and directory for robust base stripping
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$scriptDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');

// Derive request path by removing either the script path (index.php) or its directory
$request = $fullPath;
// Prefer removing exact script path if present
if (strpos($request, $scriptName) === 0) {
    $request = substr($request, strlen($scriptName));
} elseif ($scriptDir !== '' && $scriptDir !== '/' && strpos($request, $scriptDir) === 0) {
    // Fall back to removing just the directory (when index.php is omitted in URL)
    $request = substr($request, strlen($scriptDir));
}
// Clean and normalize
$request = preg_replace('/\?.*/', '', $request);
$request = rtrim($request, '/');
$method = $_SERVER['REQUEST_METHOD'];

// Extracted: email-only registration handler used by both query param endpoint and short path
function handle_register()
{
    global $conn;
    $input = json_decode(file_get_contents('php://input'), true);
    $email = normalizeEmail($input['email'] ?? '');
    $name = trim($input['name'] ?? '');
    if (!$email) send_json(['status' => 'error', 'message' => 'Email is required'], 400);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) send_json(['status' => 'error', 'message' => 'Invalid email'], 400);

    // Ensure users.email_verified column exists (tinyint default 0) - add only if missing
    try {
        $colRes = $conn->query("SHOW COLUMNS FROM users LIKE 'email_verified'");
        if ($colRes && $colRes->num_rows === 0) {
            $conn->query("ALTER TABLE users ADD COLUMN email_verified TINYINT(1) NOT NULL DEFAULT 0");
        }
    } catch (Throwable $e) {}

    // Ensure users.register_ip exists to record registration IP (VARCHAR(45) for IPv4/IPv6)
    $hasRegisterIp = false;
    try {
        $colRes2 = $conn->query("SHOW COLUMNS FROM users LIKE 'register_ip'");
        if ($colRes2 && $colRes2->num_rows > 0) $hasRegisterIp = true;
        else {
            if ($conn->query("ALTER TABLE users ADD COLUMN register_ip VARCHAR(45) DEFAULT NULL AFTER password")) {
                $hasRegisterIp = true;
            }
        }
    } catch (Throwable $e) {}

    // Resolve email domain to an IP (prefer MX host A/AAAA, fallback to domain A)
    $emailDomain = strtolower(trim(substr(strrchr($email, '@'), 1) ?: ''));
    $primaryDomainIp = null;
    if ($emailDomain !== '') {
        $mxhosts = [];
        $mxprio = [];
        if (function_exists('getmxrr') && @getmxrr($emailDomain, $mxhosts, $mxprio) && !empty($mxhosts)) {
            $pairs = [];
            for ($i = 0; $i < count($mxhosts); $i++) $pairs[] = ['host' => rtrim($mxhosts[$i], '.'), 'pri' => $mxprio[$i] ?? 0];
            usort($pairs, function ($a, $b) { return $a['pri'] <=> $b['pri']; });
            foreach ($pairs as $p) {
                $ips = @gethostbynamel($p['host']);
                if ($ips && is_array($ips) && count($ips) > 0) { $primaryDomainIp = $ips[0]; break; }
            }
        }
        if ($primaryDomainIp === null) {
            $ips = @gethostbynamel($emailDomain);
            if ($ips && is_array($ips) && count($ips) > 0) $primaryDomainIp = $ips[0];
        }
    }

    // Check if user exists
    $stmt = $conn->prepare("SELECT id, email_verified, register_ip FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $userId = (int)$row['id'];
        if (!empty($primaryDomainIp) && $hasRegisterIp) {
            $upd = $conn->prepare("UPDATE users SET register_ip = ? WHERE id = ? AND (register_ip IS NULL OR register_ip = '' OR register_ip = '::1' OR register_ip LIKE '127.%')");
            if ($upd) { $upd->bind_param('si', $primaryDomainIp, $userId); $upd->execute(); $upd->close(); }
        }
    } else {
        if ($name === '') { $name = strstr($email, '@', true) ?: 'User'; }
        $placeholderPassword = bin2hex(random_bytes(16));
        $hashed = password_hash($placeholderPassword, PASSWORD_BCRYPT);
        // get client IP
        $client_ip = null; $keys = ['HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_X_CLUSTER_CLIENT_IP','HTTP_FORWARDED_FOR','HTTP_FORWARDED','REMOTE_ADDR'];
        foreach ($keys as $k) { if (!empty($_SERVER[$k])) { $val = $_SERVER[$k]; if ($k==='HTTP_X_FORWARDED_FOR' && strpos($val, ',')!==false) $val = trim(explode(',', $val)[0]); if (filter_var($val, FILTER_VALIDATE_IP)) { $client_ip = $val; break; } } }
        $register_ip_to_store = $primaryDomainIp ?? $client_ip;
        if ($hasRegisterIp) {
            $stmtIns = $conn->prepare("INSERT INTO users (name,email,password,register_ip) VALUES (?,?,?,?)");
            $stmtIns->bind_param('ssss', $name, $email, $hashed, $register_ip_to_store);
        } else {
            $stmtIns = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
            $stmtIns->bind_param('sss', $name, $email, $hashed);
        }
        if (!$stmtIns->execute()) { send_json(['status' => 'error', 'message' => 'Registration failed'], 500); }
        $userId = $stmtIns->insert_id; $stmtIns->close();
    }
    $stmt->close();

    // Generate tokens
    // Backward compatible oauth_code (non-expiring) and new access + refresh tokens
    $oauth_code = bin2hex(random_bytes(24));
    $token_hash = hash('sha256', $oauth_code);
    $client_id = 'verify:user:' . $userId; // legacy token bucket for verification
    // New API tokens
    $access_token = bin2hex(random_bytes(24));
    $access_hash = hash('sha256', $access_token);
    $access_client = 'api:user:' . $userId;
    $access_ttl = 3600; // 1 hour
    $access_expires_at = time() + $access_ttl;
    $refresh_token = bin2hex(random_bytes(32));
    $refresh_hash = hash('sha256', $refresh_token);
    $refresh_client = 'refresh:user:' . $userId;
    $refresh_expires = time() + (30 * 24 * 3600); // 30 days
    $verify_expires_at = 0; // keep legacy oauth_code non-expiring

    // Ensure oauth_clients exists for verify client
    $stmtCli = $conn->prepare('SELECT id FROM oauth_clients WHERE client_id = ? LIMIT 1');
    $stmtCli->bind_param('s', $client_id);
    $stmtCli->execute();
    $resCli = $stmtCli->get_result();
    if (!$resCli->fetch_assoc()) {
        $nameCli = 'Email Verify for user ' . $userId;
        $dummySecret = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
        $insCli = $conn->prepare('INSERT INTO oauth_clients (client_id, client_secret_hash, name) VALUES (?,?,?)');
        $insCli->bind_param('sss', $client_id, $dummySecret, $nameCli);
        $insCli->execute(); $insCli->close();
    }
    $stmtCli->close();

    // Ensure oauth_clients exists for access client
    $stmtCli2 = $conn->prepare('SELECT id FROM oauth_clients WHERE client_id = ? LIMIT 1');
    $stmtCli2->bind_param('s', $access_client);
    $stmtCli2->execute();
    $resCli2 = $stmtCli2->get_result();
    if (!$resCli2->fetch_assoc()) {
        $nameAcc = 'API Access for user ' . $userId;
        $dummySecret2 = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
        $insCli2 = $conn->prepare('INSERT INTO oauth_clients (client_id, client_secret_hash, name) VALUES (?,?,?)');
        $insCli2->bind_param('sss', $access_client, $dummySecret2, $nameAcc);
        $insCli2->execute(); $insCli2->close();
    }
    $stmtCli2->close();

    // Ensure oauth_clients exists for refresh client
    $stmtCli3 = $conn->prepare('SELECT id FROM oauth_clients WHERE client_id = ? LIMIT 1');
    $stmtCli3->bind_param('s', $refresh_client);
    $stmtCli3->execute();
    $resCli3 = $stmtCli3->get_result();
    if (!$resCli3->fetch_assoc()) {
        $nameRef = 'API Refresh for user ' . $userId;
        $dummySecret3 = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
        $insCli3 = $conn->prepare('INSERT INTO oauth_clients (client_id, client_secret_hash, name) VALUES (?,?,?)');
        $insCli3->bind_param('sss', $refresh_client, $dummySecret3, $nameRef);
        $insCli3->execute(); $insCli3->close();
    }
    $stmtCli3->close();

    // Remove previous tokens and insert new ones (legacy verify + new access + refresh)
    foreach ([$client_id, $access_client, $refresh_client] as $cid) {
        $del = $conn->prepare('DELETE FROM oauth_access_tokens WHERE client_id = ?');
        $del->bind_param('s', $cid); $del->execute(); $del->close();
    }

    // Insert with optional columns
    $canStoreEmail = false; $canStoreIp = false;
    try { $colTok = $conn->query("SHOW COLUMNS FROM oauth_access_tokens LIKE 'client_email'"); if ($colTok && $colTok->num_rows > 0) $canStoreEmail = true; } catch (Throwable $e) {}
    try { $colTok2 = $conn->query("SHOW COLUMNS FROM oauth_access_tokens LIKE 'client_ip'"); if ($colTok2 && $colTok2->num_rows > 0) $canStoreIp = true; } catch (Throwable $e) {}

    if ($canStoreEmail && $canStoreIp) {
        $insTok = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at, client_ip, client_email) VALUES (?,?,?,?,?)');
        if ($insTok) {
            $ipForTok = $primaryDomainIp ?? null;
            if ($ipForTok === null) {
                $keysTok = ['HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_X_CLUSTER_CLIENT_IP','HTTP_FORWARDED_FOR','HTTP_FORWARDED','REMOTE_ADDR'];
                foreach ($keysTok as $k) { if (!empty($_SERVER[$k])) { $val = $_SERVER[$k]; if ($k==='HTTP_X_FORWARDED_FOR' && strpos($val, ',')!==false) $val = trim(explode(',', $val)[0]); if (filter_var($val, FILTER_VALIDATE_IP)) { $ipForTok = $val; break; } } }
            }
            $insTok->bind_param('ssiss', $token_hash, $client_id, $verify_expires_at, $ipForTok, $email);
            if (!$insTok->execute()) {
                $insTok->close();
                $insTok2 = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?,?,?)');
                if ($insTok2) { $insTok2->bind_param('ssi', $token_hash, $client_id, $verify_expires_at); if (!$insTok2->execute()) { send_json(['status' => 'error', 'message' => 'Could not create verification code'], 500);} $insTok2->close(); }
                else { send_json(['status' => 'error', 'message' => 'Could not create verification code'], 500); }
            } else { $insTok->close(); }
        } else {
            $insTok2 = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?,?,?)');
            if ($insTok2) { $insTok2->bind_param('ssi', $token_hash, $client_id, $verify_expires_at); if (!$insTok2->execute()) { send_json(['status' => 'error', 'message' => 'Could not create verification code'], 500);} $insTok2->close(); }
            else { send_json(['status' => 'error', 'message' => 'Could not create verification code'], 500); }
        }
    } elseif ($canStoreEmail && !$canStoreIp) {
        $insTok = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at, client_email) VALUES (?,?,?,?)');
        if ($insTok) { $insTok->bind_param('ssis', $token_hash, $client_id, $verify_expires_at, $email); if (!$insTok->execute()) { $insTok->close(); $insTok2 = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?,?,?)'); if ($insTok2) { $insTok2->bind_param('ssi', $token_hash, $client_id, $verify_expires_at); if (!$insTok2->execute()) { send_json(['status'=>'error','message'=>'Could not create verification code'],500);} $insTok2->close(); } else { send_json(['status'=>'error','message'=>'Could not create verification code'],500);} } else { $insTok->close(); } }
        else { $insTok2 = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?,?,?)'); if ($insTok2) { $insTok2->bind_param('ssi', $token_hash, $client_id, $expires_at); if (!$insTok2->execute()) { send_json(['status'=>'error','message'=>'Could not create verification code'],500);} $insTok2->close(); } else { send_json(['status'=>'error','message'=>'Could not create verification code'],500);} }
    } else {
        $insTok2 = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?,?,?)');
        if ($insTok2) { $insTok2->bind_param('ssi', $token_hash, $client_id, $verify_expires_at); if (!$insTok2->execute()) { send_json(['status'=>'error','message'=>'Could not create verification code'],500);} $insTok2->close(); }
        else { send_json(['status'=>'error','message'=>'Could not create verification code'],500); }
    }

    // Insert access token (expiring)
    $insAcc = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?,?,?)');
    if ($insAcc) { $insAcc->bind_param('ssi', $access_hash, $access_client, $access_expires_at); $insAcc->execute(); $insAcc->close(); }
    // Insert refresh token (longer expiry)
    $insRef = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?,?,?)');
    if ($insRef) { $insRef->bind_param('ssi', $refresh_hash, $refresh_client, $refresh_expires); $insRef->execute(); $insRef->close(); }

    send_json([
        'status' => 'success',
        'message' => 'Registered',
        'oauth_code' => $oauth_code, // legacy
        'access_token' => $access_token,
        'token_type' => 'Bearer',
        'expires_in' => $access_ttl,
        'refresh_token' => $refresh_token
    ]);
}

// Query param endpoints (backward compatibility)
if ($method === 'POST' && $endpoint === 'register') { handle_register(); }
if ($method === 'POST' && $endpoint === 'verify-email') { require __DIR__ . '/../public/verify_user_email.php'; }

try {
    switch (true) {
        case ($request === '/api/register' && $method === 'POST'):
            // Short path for email-only registration
            handle_register();
            break;
        case ($request === '/api/verify-email' && $method === 'POST'):
            require __DIR__ . '/../public/verify_user_email.php';
            break;
        case ($request === '/api/single-email' && $method === 'POST'):
            // Protected
            require __DIR__ . '/../oauth/validate_token.php';
            require_bearer();
            require __DIR__ . '/../includes/single_email_processor.php';
            break;
        case ($request === '/api/single-email'):
            // protect single-email with bearer token
            require __DIR__ . '/../oauth/validate_token.php';
            require_bearer();
            require __DIR__ . '/../includes/single_email_processor.php';
            break;
        case ($request === '/api/auth/google/start' && $method === 'GET'):
            require __DIR__ . '/../oauth/google_start.php';
            break;
        case ($request === '/api/auth/google/callback' && $method === 'GET'):
            require __DIR__ . '/../oauth/google_callback.php';
            break;
        case ($request === '/api/token/refresh' && $method === 'POST'):
            // Simple refresh: takes refresh_token, issues new access token
            require_once __DIR__ . '/../config/db.php';
            $body = json_decode(file_get_contents('php://input'), true) ?: [];
            $refresh = isset($body['refresh_token']) ? trim($body['refresh_token']) : '';
            if ($refresh === '') send_json(['error' => 'invalid_request', 'message' => 'refresh_token required'], 400);
            $hash = hash('sha256', $refresh);
            $stmt = $conn->prepare('SELECT client_id, expires_at FROM oauth_access_tokens WHERE access_token_hash = ? LIMIT 1');
            if (!$stmt) send_json(['error' => 'server_error'], 500);
            $stmt->bind_param('s', $hash);
            $stmt->execute();
            $res = $stmt->get_result();
            if (!$row = $res->fetch_assoc()) send_json(['error' => 'invalid_grant'], 401);
            $stmt->close();
            if (strpos($row['client_id'], 'refresh:user:') !== 0) send_json(['error' => 'invalid_grant'], 401);
            $exp = intval($row['expires_at'] ?? 0);
            if ($exp > 0 && time() > $exp) send_json(['error' => 'invalid_grant', 'message' => 'refresh_token expired'], 401);
            // derive user id
            if (!preg_match('/^refresh:user:(\d+)$/', $row['client_id'], $m)) send_json(['error' => 'invalid_grant'], 401);
            $uid = (int)$m[1];
            // issue new access token
            $access_token = bin2hex(random_bytes(24));
            $access_hash = hash('sha256', $access_token);
            $client = 'api:user:' . $uid;
            $ttl = 3600; $expAt = time() + $ttl;
            // delete old access tokens for this client
            $del = $conn->prepare('DELETE FROM oauth_access_tokens WHERE client_id = ?');
            $del->bind_param('s', $client); $del->execute(); $del->close();
            $ins = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?,?,?)');
            if (!$ins) send_json(['error' => 'server_error'], 500);
            $ins->bind_param('ssi', $access_hash, $client, $expAt);
            $ins->execute(); $ins->close();
            send_json(['access_token' => $access_token, 'token_type' => 'Bearer', 'expires_in' => $ttl]);
            break;
        default:
            send_json(['status' => 'error', 'message' => 'Endpoint not found'], 404);
    }
} catch (Exception $e) {
    // Write exception details to storage for debugging
    $logDir = __DIR__ . '/../storage';
    if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
    $logFile = $logDir . '/api_error.log';
    $msg = date('c') . ' Exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n\n";
    @file_put_contents($logFile, $msg, FILE_APPEND);
    // Return a safe error payload for debugging in local/dev
    send_json(['status' => 'error', 'message' => 'Server error', 'error' => $e->getMessage()], 500);
}


