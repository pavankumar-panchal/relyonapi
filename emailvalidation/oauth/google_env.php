<?php
// Helper to read Google OAuth env with fallbacks to local config files
function get_google_env()
{
    $cfg = [
        'client_id' => getenv('GOOGLE_CLIENT_ID') ?: null,
        'client_secret' => getenv('GOOGLE_CLIENT_SECRET') ?: null,
        'redirect_uri' => getenv('GOOGLE_REDIRECT_URI') ?: null,
        'scopes' => getenv('GOOGLE_SCOPES') ?: 'openid email profile',
    ];
    // If not set via env, try PHP config files
    $paths = [
        __DIR__ . '/../config/google_oauth.local.php',
        __DIR__ . '/../config/google_oauth.php',
    ];
    foreach ($paths as $p) {
        if (is_file($p)) {
            $arr = @include $p; // should return array with keys
            if (is_array($arr)) {
                $cfg['client_id'] = $cfg['client_id'] ?: ($arr['GOOGLE_CLIENT_ID'] ?? null);
                $cfg['client_secret'] = $cfg['client_secret'] ?: ($arr['GOOGLE_CLIENT_SECRET'] ?? null);
                $cfg['redirect_uri'] = $cfg['redirect_uri'] ?: ($arr['GOOGLE_REDIRECT_URI'] ?? null);
                $cfg['scopes'] = $cfg['scopes'] ?: ($arr['GOOGLE_SCOPES'] ?? 'openid email profile');
            }
        }
    }
    return $cfg;
}
