<?php
// Google OAuth 2.0 start: redirect user to consent screen
// Loads config from env or config files via google_env.php
require_once __DIR__ . '/google_env.php';
$env = get_google_env();
$clientId = $env['client_id'];
$redirectUri = $env['redirect_uri'];
$scopes = $env['scopes'] ?: 'openid email profile';

if (!$clientId || !$redirectUri) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'server_misconfigured', 'message' => 'Google OAuth not configured. Set env vars or create backend/config/google_oauth.local.php']);
    exit;
}

$state = bin2hex(random_bytes(16));
// Optionally store state in a cookie for CSRF protection
setcookie('g_oauth_state', $state, [
    'expires' => time() + 600,
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'httponly' => true,
    'samesite' => 'Lax'
]);

$params = [
    'client_id' => $clientId,
    'redirect_uri' => $redirectUri,
    'response_type' => 'code',
    'scope' => $scopes,
    'access_type' => 'offline',
    'prompt' => 'consent',
    'state' => $state
];

$url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
header('Location: ' . $url, true, 302);
exit;
