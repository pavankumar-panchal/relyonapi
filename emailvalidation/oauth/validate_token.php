<?php
// Middleware to validate Bearer tokens stored in oauth_access_tokens (DB-backed simple implementation)
require_once __DIR__ . '/../config/db.php';

function require_bearer()
{
    global $conn;
    $hdr = $_SERVER['HTTP_AUTHORIZATION'] ?? ($_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? null);
    if (!$hdr) {
        // Try getallheaders()/apache_request_headers fallbacks (some Apache/PHP setups drop HTTP_AUTHORIZATION)
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            if (is_array($headers)) {
                foreach ($headers as $k => $v) {
                    if (strcasecmp($k, 'Authorization') === 0) {
                        $hdr = $v;
                        break;
                    }
                }
            }
        }
        if (!$hdr && function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (is_array($headers)) {
                foreach ($headers as $k => $v) {
                    if (strcasecmp($k, 'Authorization') === 0) {
                        $hdr = $v;
                        break;
                    }
                }
            }
        }
    }
    if (!$hdr) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'missing_authorization']);
        exit;
    }
    if (stripos($hdr, 'Bearer ') === 0) {
        $token = trim(substr($hdr, 7));
    } else {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'invalid_authorization_header']);
        exit;
    }
    if ($token === '') {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'invalid_token']);
        exit;
    }
    $hash = hash('sha256', $token);
    $stmt = $conn->prepare('SELECT client_id, expires_at FROM oauth_access_tokens WHERE access_token_hash = ? LIMIT 1');
    if (!$stmt) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'server_error']);
        exit;
    }
    $stmt->bind_param('s', $hash);
    $stmt->execute();
    $res = $stmt->get_result();
    if (!$row = $res->fetch_assoc()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'invalid_token']);
        exit;
    }
    $stmt->close();
    // Treat expires_at = 0 or NULL as non-expiring
    $exp = isset($row['expires_at']) ? intval($row['expires_at']) : 0;
    if ($exp > 0 && time() > $exp) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'token_expired']);
        exit;
    }
    // set globals for downstream code
    $GLOBALS['oauth_client_id'] = $row['client_id'];
    if (preg_match('/^(verify|api):user:(\d+)$/', $row['client_id'], $m)) {
        $GLOBALS['oauth_user_id'] = (int)$m[2];
    }
    return true;
}
