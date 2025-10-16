<?php
// Google OAuth 2.0 callback: exchange code, verify ID token via JWKS, upsert user, issue oauth_code
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/google_env.php';

function send_json_cb($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

$env = get_google_env();
$clientId = $env['client_id'];
$clientSecret = $env['client_secret'];
$redirectUri = $env['redirect_uri'];

if (!$clientId || !$clientSecret || !$redirectUri) {
    send_json_cb(['error' => 'server_misconfigured', 'message' => 'Missing GOOGLE_CLIENT_ID/SECRET/REDIRECT_URI'], 500);
}

$code = $_GET['code'] ?? null;
$state = $_GET['state'] ?? null;
if (!$code) {
    send_json_cb(['error' => 'invalid_request', 'message' => 'Missing code'], 400);
}

// Optional state validation (CSRF)
if (!empty($_COOKIE['g_oauth_state']) && !hash_equals($_COOKIE['g_oauth_state'], (string)$state)) {
    send_json_cb(['error' => 'invalid_state'], 400);
}

// Exchange code for tokens
$tokenUrl = 'https://oauth2.googleapis.com/token';
$post = http_build_query([
    'code' => $code,
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'redirect_uri' => $redirectUri,
    'grant_type' => 'authorization_code'
]);

$opts = [
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => $post,
        'timeout' => 15
    ]
];
$ctx = stream_context_create($opts);
$resp = @file_get_contents($tokenUrl, false, $ctx);
if ($resp === false) {
    send_json_cb(['error' => 'token_exchange_failed'], 502);
}
$tok = json_decode($resp, true);
if (!is_array($tok) || empty($tok['id_token'])) {
    send_json_cb(['error' => 'invalid_token_response', 'raw' => $tok], 502);
}
$idToken = $tok['id_token'];

// Verify ID token with Google JWKS
function base64url_decode_str($data) {
    $replaced = strtr($data, '-_', '+/');
    $pad = strlen($replaced) % 4;
    if ($pad > 0) $replaced .= str_repeat('=', 4 - $pad);
    return base64_decode($replaced);
}

function parse_jwt_header($jwt) {
    $parts = explode('.', $jwt);
    if (count($parts) < 2) return null;
    return json_decode(base64url_decode_str($parts[0]), true);
}

function parse_jwt_claims($jwt) {
    $parts = explode('.', $jwt);
    if (count($parts) < 2) return null;
    return json_decode(base64url_decode_str($parts[1]), true);
}

$header = parse_jwt_header($idToken);
$claims = parse_jwt_claims($idToken);
if (!is_array($header) || !is_array($claims)) {
    send_json_cb(['error' => 'invalid_id_token'], 401);
}

$kid = $header['kid'] ?? null;
$alg = $header['alg'] ?? null;
if ($alg !== 'RS256' || !$kid) {
    send_json_cb(['error' => 'unsupported_token_alg'], 401);
}

// Fetch JWKS
$jwksUrl = 'https://www.googleapis.com/oauth2/v3/certs';
$jwksRaw = @file_get_contents($jwksUrl);
if ($jwksRaw === false) {
    send_json_cb(['error' => 'jwks_fetch_failed'], 502);
}
$jwks = json_decode($jwksRaw, true);
if (!isset($jwks['keys']) || !is_array($jwks['keys'])) {
    send_json_cb(['error' => 'invalid_jwks'], 502);
}

function build_pem_from_jwk($jwk) {
    // Build SubjectPublicKeyInfo (SPKI) PEM from RSA JWK (n,e)
    $n = isset($jwk['n']) ? base64url_decode_str($jwk['n']) : null;
    $e = isset($jwk['e']) ? base64url_decode_str($jwk['e']) : null;
    if (!$n || !$e) return null;

    $der_len = function($len) {
        if ($len < 128) return chr($len);
        $out = '';
        while ($len > 0) { $out = chr($len & 0xFF) . $out; $len >>= 8; }
        return chr(0x80 | strlen($out)) . $out;
    };
    $asn1_int = function($bytes) use ($der_len) {
        if (strlen($bytes) === 0) $bytes = "\x00";
        if ((ord($bytes[0]) & 0x80) !== 0) { $bytes = "\x00" . $bytes; }
        return "\x02" . $der_len(strlen($bytes)) . $bytes;
    };
    $asn1_seq = function($enc) use ($der_len) {
        return "\x30" . $der_len(strlen($enc)) . $enc;
    };
    $asn1_bit_str = function($bytes) use ($der_len) {
        // prepend 0x00 for zero unused bits
        $bytes = "\x00" . $bytes;
        return "\x03" . $der_len(strlen($bytes)) . $bytes;
    };

    // RSAPublicKey (PKCS#1)
    $rsa_pubkey = $asn1_seq($asn1_int($n) . $asn1_int($e));

    // AlgorithmIdentifier for rsaEncryption (1.2.840.113549.1.1.1) with NULL param
    $oid_rsa = "\x06\x09\x2A\x86\x48\x86\xF7\x0D\x01\x01\x01"; // 1.2.840.113549.1.1.1
    $asn1_null = "\x05\x00";
    $alg_id = $asn1_seq($oid_rsa . $asn1_null);

    // SubjectPublicKeyInfo
    $spki = $asn1_seq($alg_id . $asn1_bit_str($rsa_pubkey));
    $pem = "-----BEGIN PUBLIC KEY-----\n" . chunk_split(base64_encode($spki), 64, "\n") . "-----END PUBLIC KEY-----\n";
    return $pem;
}

$matching = null;
foreach ($jwks['keys'] as $k) {
    if (($k['kid'] ?? null) === $kid && ($k['kty'] ?? '') === 'RSA') { $matching = $k; break; }
}
if (!$matching) {
    send_json_cb(['error' => 'kid_not_found'], 401);
}
$pem = build_pem_from_jwk($matching);
if (!$pem) {
    send_json_cb(['error' => 'pem_build_failed'], 500);
}

// Verify signature
$parts = explode('.', $idToken);
$signingInput = $parts[0] . '.' . $parts[1];
$sig = base64url_decode_str($parts[2]);
$ok = openssl_verify($signingInput, $sig, $pem, OPENSSL_ALGO_SHA256);
if ($ok !== 1) {
    send_json_cb(['error' => 'invalid_id_token_signature'], 401);
}

// Validate registered claims
$now = time();
$aud = $claims['aud'] ?? null;
$iss = $claims['iss'] ?? null;
$exp = intval($claims['exp'] ?? 0);
$email = $claims['email'] ?? null;
$email_verified = $claims['email_verified'] ?? false;
// aud may be a string or an array; accept if our clientId present
if (is_array($aud)) {
    if (!in_array($clientId, $aud, true)) send_json_cb(['error' => 'invalid_audience'], 401);
} else {
    if ($aud !== $clientId) send_json_cb(['error' => 'invalid_audience'], 401);
}
if (!in_array($iss, ['https://accounts.google.com', 'accounts.google.com'], true)) send_json_cb(['error' => 'invalid_issuer'], 401);
if ($exp < $now) send_json_cb(['error' => 'id_token_expired'], 401);
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) send_json_cb(['error' => 'email_missing'], 400);

// Upsert user in our DB
$name = $claims['name'] ?? ($claims['given_name'] ?? 'Google User');
$emailNorm = $email;
$stmt = $conn->prepare('SELECT id, email_verified FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $emailNorm);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    $userId = intval($row['id']);
} else {
    // Create placeholder with random password
    $placeholderPassword = bin2hex(random_bytes(16));
    $hashed = password_hash($placeholderPassword, PASSWORD_BCRYPT);
    $ins = $conn->prepare('INSERT INTO users (name, email, password, email_verified) VALUES (?, ?, ?, 0)');
    $ins->bind_param('sss', $name, $emailNorm, $hashed);
    if (!$ins->execute()) send_json_cb(['error' => 'user_create_failed'], 500);
    $userId = $ins->insert_id;
    $ins->close();
}
$stmt->close();

// If Google asserts email_verified, mark it
if ($email_verified) {
    $upd = $conn->prepare('UPDATE users SET email_verified = 1 WHERE id = ?');
    $upd->bind_param('i', $userId);
    $upd->execute();
    $upd->close();
}

// Issue our long-lived oauth_code for this user (used as Bearer for protected endpoints)
$oauth_code = bin2hex(random_bytes(24));
$token_hash = hash('sha256', $oauth_code);
$client_id_local = 'verify:user:' . $userId;
$expires_at = 0; // non-expiring

// Ensure client exists
$stmtCli = $conn->prepare('SELECT id FROM oauth_clients WHERE client_id = ? LIMIT 1');
$stmtCli->bind_param('s', $client_id_local);
$stmtCli->execute();
$rCli = $stmtCli->get_result();
if (!$rCli->fetch_assoc()) {
    $nameCli = 'Email Verify for user ' . $userId;
    $dummySecret = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
    $insCli = $conn->prepare('INSERT INTO oauth_clients (client_id, client_secret_hash, name) VALUES (?,?,?)');
    $insCli->bind_param('sss', $client_id_local, $dummySecret, $nameCli);
    $insCli->execute();
    $insCli->close();
}
$stmtCli->close();

// Replace any prior tokens for this user
$del = $conn->prepare('DELETE FROM oauth_access_tokens WHERE client_id = ?');
$del->bind_param('s', $client_id_local);
$del->execute();
$del->close();

// Insert token (with optional columns if exist)
$canEmail = false; $canIp = false;
try { $c1 = $conn->query("SHOW COLUMNS FROM oauth_access_tokens LIKE 'client_email'"); if ($c1 && $c1->num_rows > 0) $canEmail = true; } catch (Throwable $e) {}
try { $c2 = $conn->query("SHOW COLUMNS FROM oauth_access_tokens LIKE 'client_ip'"); if ($c2 && $c2->num_rows > 0) $canIp = true; } catch (Throwable $e) {}

if ($canEmail && $canIp) {
    $insTok = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at, client_ip, client_email) VALUES (?,?,?,?,?)');
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $insTok->bind_param('ssiss', $token_hash, $client_id_local, $expires_at, $ip, $emailNorm);
    if (!$insTok->execute()) { $insTok->close(); $insTok2 = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?,?,?)'); $insTok2->bind_param('ssi', $token_hash, $client_id_local, $expires_at); $insTok2->execute(); $insTok2->close(); }
    else { $insTok->close(); }
} elseif ($canEmail && !$canIp) {
    $insTok = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at, client_email) VALUES (?,?,?,?)');
    $insTok->bind_param('ssis', $token_hash, $client_id_local, $expires_at, $emailNorm);
    if (!$insTok->execute()) { $insTok->close(); $insTok2 = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?,?,?)'); $insTok2->bind_param('ssi', $token_hash, $client_id_local, $expires_at); $insTok2->execute(); $insTok2->close(); }
    else { $insTok->close(); }
} else {
    $insTok2 = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?,?,?)');
    $insTok2->bind_param('ssi', $token_hash, $client_id_local, $expires_at);
    $insTok2->execute();
    $insTok2->close();
}

// Respond JSON for SPA/mobile or redirect with code in fragment
$response_mode = $_GET['mode'] ?? 'json';

// Also issue API access/refresh tokens (expiring) for proper authentication flows
$access_token = bin2hex(random_bytes(24));
$access_hash = hash('sha256', $access_token);
$access_client = 'api:user:' . $userId;
$access_ttl = 3600; $access_expires_at = time() + $access_ttl;
$refresh_token = bin2hex(random_bytes(32));
$refresh_hash = hash('sha256', $refresh_token);
$refresh_client = 'refresh:user:' . $userId;
$refresh_expires = time() + (30*24*3600);

// Replace any prior access tokens
$delAcc = $conn->prepare('DELETE FROM oauth_access_tokens WHERE client_id = ?');
$delAcc->bind_param('s', $access_client); $delAcc->execute(); $delAcc->close();
$insAcc = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?,?,?)');
$insAcc->bind_param('ssi', $access_hash, $access_client, $access_expires_at); $insAcc->execute(); $insAcc->close();
// Replace any prior refresh tokens
$delRef = $conn->prepare('DELETE FROM oauth_access_tokens WHERE client_id = ?');
$delRef->bind_param('s', $refresh_client); $delRef->execute(); $delRef->close();
$insRef = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?,?,?)');
$insRef->bind_param('ssi', $refresh_hash, $refresh_client, $refresh_expires); $insRef->execute(); $insRef->close();
if ($response_mode === 'redirect' && !empty($_GET['redirect_to'])) {
    $redir = $_GET['redirect_to'];
    $glue = (strpos($redir, '#') === false) ? '#' : '&';
    header('Location: ' . $redir . $glue . 'oauth_code=' . urlencode($oauth_code) . '&email=' . urlencode($emailNorm) . '&access_token=' . urlencode($access_token) . '&expires_in=' . $access_ttl . '&refresh_token=' . urlencode($refresh_token));
    exit;
}

send_json_cb([
    'status' => 'success',
    'message' => 'Google sign-in successful',
    'email' => $emailNorm,
    'email_verified' => (bool)$email_verified,
    'oauth_code' => $oauth_code,
    'access_token' => $access_token,
    'token_type' => 'Bearer',
    'expires_in' => $access_ttl,
    'refresh_token' => $refresh_token
]);
