<?php
// Minimal front controller for the API
$debug = getenv('APP_DEBUG');
$isDebug = ($debug === '1' || strcasecmp($debug ?? '', 'true') === 0);
error_reporting(E_ALL);
ini_set('display_errors', $isDebug ? '1' : '0');

// CORS + common headers for local testing
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Bootstrap the main API router
require_once __DIR__ . '/../routes/api.php';
