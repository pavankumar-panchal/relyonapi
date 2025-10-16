<?php
$debug = getenv('APP_DEBUG');
$isDebug = ($debug === '1' || strcasecmp($debug ?: '', 'true') === 0);
error_reporting(E_ALL);
ini_set('display_errors', $isDebug ? '1' : '0');

$dbConfig = [
    'host' => '127.0.0.1',
    'username' => 'CRM_API',
    'password' => '55y60jgW*',
    'name' => 'CRM_API',
    'port' => 3306
];
$conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['name'], $dbConfig['port']);
if ($conn->connect_error) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}


$jwt_config = [];

$oauth_config = [
    'secret' => getenv('OAUTH_SECRET') ?: 'change_this_oauth_secret',
    'access_token_ttl' => 3600, 
];
?>
