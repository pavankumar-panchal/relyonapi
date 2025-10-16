<?php
require_once __DIR__ . '/../config/db.php';

function create_client($name = null)
{
    global $conn;
    $client_id = bin2hex(random_bytes(16));
    $secret = bin2hex(random_bytes(24));
    $hash = password_hash($secret, PASSWORD_BCRYPT);
    $stmt = $conn->prepare('INSERT INTO oauth_clients (client_id, client_secret_hash, name) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $client_id, $hash, $name);
    if (!$stmt->execute()) return false;
    $stmt->close();
    return ['client_id' => $client_id, 'client_secret' => $secret];
}

if (php_sapi_name() === 'cli') {
    $name = $argv[1] ?? 'dev-client';
    $c = create_client($name);
    if ($c) echo json_encode($c) . "\n";
}

