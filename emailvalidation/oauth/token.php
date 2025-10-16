<?php
// Lightweight client_credentials token endpoint backed by DB
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'method_not_allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$input = json_decode($raw, true);
if (!is_array($input)) $input = $_POST;
$client_id = trim($input['client_id'] ?? ($_SERVER['PHP_AUTH_USER'] ?? ''));
$client_secret = trim($input['client_secret'] ?? ($_SERVER['PHP_AUTH_PW'] ?? ''));

if ($client_id === '' || $client_secret === '') {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_client']);
    exit;
}

$stmt = $conn->prepare('SELECT client_secret_hash FROM oauth_clients WHERE client_id = ? LIMIT 1');
$stmt->bind_param('s', $client_id);
$stmt->execute();
$res = $stmt->get_result();
if (!$row = $res->fetch_assoc()) {
    http_response_code(401);
    echo json_encode(['error' => 'invalid_client']);
    exit;
}
$stmt->close();

if (!password_verify($client_secret, $row['client_secret_hash'])) {
    http_response_code(401);
    echo json_encode(['error' => 'invalid_client']);
    exit;
}

$ttl = $oauth_config['access_token_ttl'] ?? 3600;
$token = bin2hex(random_bytes(32));
$token_hash = hash('sha256', $token);
$expires = time() + $ttl;

// Helper: get client IP (handles X-Forwarded-For etc.)
function get_client_ip()
{
    $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
    foreach ($keys as $k) {
        if (!empty($_SERVER[$k])) {
            $val = $_SERVER[$k];
            if ($k === 'HTTP_X_FORWARDED_FOR' && strpos($val, ',') !== false) $val = trim(explode(',', $val)[0]);
            return $val;
        }
    }
    return null;
}

// Optional client_email may be provided by trusted callers (or left empty)
$client_ip = get_client_ip();
$client_email = trim($input['client_email'] ?? '');
if ($client_email === '') $client_email = null;

// Try to store metadata (client_ip, client_email) if the DB has the columns.
// Fall back to the original insert if the extended insert fails (backwards compatible).
$extended_sql = 'INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at, client_ip, client_email) VALUES (?, ?, ?, ?, ?)';
$ins = $conn->prepare($extended_sql);
if ($ins) {
    // bind params types: s (hash), s (client_id), i (expires), s (ip), s (email|null)
    $ip_for_bind = $client_ip ?? '';
    $email_for_bind = $client_email ?? '';
    $ins->bind_param('ssiss', $token_hash, $client_id, $expires, $ip_for_bind, $email_for_bind);
    if (!$ins->execute()) {
        // extended insert failed (likely missing columns) - fall back
        $ins->close();
        $ins = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?, ?, ?)');
        if ($ins) {
            $ins->bind_param('ssi', $token_hash, $client_id, $expires);
            if (!$ins->execute()) {
                http_response_code(500);
                echo json_encode(['error' => 'server_error']);
                exit;
            }
            $ins->close();
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'server_error']);
            exit;
        }
    } else {
        $ins->close();
    }
} else {
    // Could not prepare extended statement - fall back to original insert
    $ins = $conn->prepare('INSERT INTO oauth_access_tokens (access_token_hash, client_id, expires_at) VALUES (?, ?, ?)');
    if ($ins) {
        $ins->bind_param('ssi', $token_hash, $client_id, $expires);
        if (!$ins->execute()) {
            http_response_code(500);
            echo json_encode(['error' => 'server_error']);
            exit;
        }
        $ins->close();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'server_error']);
        exit;
    }
}

echo json_encode(['access_token' => $token, 'token_type' => 'Bearer', 'expires_in' => $ttl]);
exit;
