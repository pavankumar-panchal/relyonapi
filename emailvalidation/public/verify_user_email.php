<?php
require_once __DIR__ . '/../config/db.php';

function send_json_local($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = $_POST;
}
$email = trim($input['email'] ?? '');
$code  = trim($input['oauth_code'] ?? '');

if ($email === '' || $code === '') {
    send_json_local(['status' => 'error', 'message' => 'email and oauth_code are required'], 400);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_json_local(['status' => 'error', 'message' => 'Invalid email'], 400);
}

// Ensure users.email_verified exists (only add if missing)
try {
    $colRes = $conn->query("SHOW COLUMNS FROM users LIKE 'email_verified'");
    if ($colRes && $colRes->num_rows === 0) {
        $conn->query("ALTER TABLE users ADD COLUMN email_verified TINYINT(1) NOT NULL DEFAULT 0");
    }
} catch (Throwable $e) {
    // ignore if already exists or insufficient privileges
}

// Find user
$stmt = $conn->prepare('SELECT id, email_verified FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$res = $stmt->get_result();
if (!$user = $res->fetch_assoc()) {
    send_json_local(['status' => 'error', 'message' => 'User not found'], 404);
}
$stmt->close();
$userId = (int)$user['id'];

// Validate code: hash and match in oauth_access_tokens with client_id = verify:user:<id> and non-expiring
$hash = hash('sha256', $code);
$client_id = 'verify:user:' . $userId;
$stmt2 = $conn->prepare('SELECT id, expires_at FROM oauth_access_tokens WHERE client_id = ? AND access_token_hash = ? LIMIT 1');
$stmt2->bind_param('ss', $client_id, $hash);
$stmt2->execute();
$res2 = $stmt2->get_result();
if (!$tok = $res2->fetch_assoc()) {
    send_json_local(['status' => 'error', 'message' => 'Invalid oauth_code'], 401);
}
$stmt2->close();
$exp = isset($tok['expires_at']) ? intval($tok['expires_at']) : 0;
if ($exp > 0 && time() > $exp) {
    send_json_local(['status' => 'error', 'message' => 'oauth_code expired'], 401);
}

// Mark verified
$upd = $conn->prepare('UPDATE users SET email_verified = 1 WHERE id = ?');
$upd->bind_param('i', $userId);
if (!$upd->execute()) {
    send_json_local(['status' => 'error', 'message' => 'Failed to verify'], 500);
}
$upd->close();

send_json_local(['status' => 'success', 'message' => 'Email verified']);
