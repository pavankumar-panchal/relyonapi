<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// DB connection
$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "CRM";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Optimization settings
set_time_limit(0);
ini_set('memory_limit', '512M');

// --- Utilities ---
function consoleOutput($message)
{
    if (php_sapi_name() === 'cli') {
        echo $message . PHP_EOL;
    }
}

// Get first IP for a domain (MX or A record)
function getDomainIP($domain)
{
    if (getmxrr($domain, $mxhosts)) {
        $mxIp = @gethostbyname($mxhosts[0]);
        if ($mxIp !== $mxhosts[0]) {
            return $mxIp;
        }
    }
    $aRecord = @gethostbyname($domain);
    return ($aRecord !== $domain) ? $aRecord : false;
}

// Sequential domain verification
function processDomainsSequential($conn)
{
    $res = $conn->query("SELECT id, sp_domain FROM emails WHERE domain_verified = 0");
    if (!$res || $res->num_rows === 0) {
        consoleOutput("All domains already verified.");
        return 0;
    }

    $processed = 0;
    while ($row = $res->fetch_assoc()) {
        $domainId = $row['id'];
        $domain   = $row['sp_domain'];
        $ip       = false;
        $status   = 0;
        $response = "Not valid domain";

        if (filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            $ip = getDomainIP($domain);
        }

        if ($ip) {
            $status   = 1;
            $response = $ip;
        }

        $stmt = $conn->prepare("UPDATE emails 
            SET domain_verified=1, domain_status=?, validation_response=? 
            WHERE id=?");
        $stmt->bind_param("isi", $status, $response, $domainId);
        $stmt->execute();
        $stmt->close();

        $processed++;
        consoleOutput("Processed ID: $domainId | Domain: $domain | Status: $status | Response: $response");
    }
    return $processed;
}

// Set all pending csv_list to running before verification
$conn->query("UPDATE csv_list SET status = 'running' WHERE status = 'pending'");

// Main execution
try {
    $start = microtime(true);
    $processed = processDomainsSequential($conn);
    $time = microtime(true) - $start;

    $totalResult = $conn->query("SELECT COUNT(*) as total FROM emails");
    $total = $totalResult->fetch_assoc()['total'];

    echo json_encode([
        "status"          => "success",
        "processed"       => $processed,
        "total"           => $total,
        "time_seconds"    => round($time, 2),
        "rate_per_second" => $time > 0 ? round($processed / $time, 2) : 0
    ]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

$conn->close();

// Trigger SMTP verification after domain check
exec('php includes/verify_smtp.php > /dev/null 2>&1 &');
?>
