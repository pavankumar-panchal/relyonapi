<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
set_time_limit(0);

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Database configuration
$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "CRM";

// Main DB
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Log DB
$log_db_host = "127.0.0.1";
$log_db_user = "root";
$log_db_pass = "";
$log_db_name = "CRM_logs";

$conn_logs = new mysqli($log_db_host, $log_db_user, $log_db_pass, $log_db_name);
$conn_logs->set_charset("utf8mb4");
if ($conn_logs->connect_error) {
    die(json_encode(["status" => "error", "message" => "Log DB connection failed: " . $conn_logs->connect_error]));
}

// --- Logging ---
define('LOG_FILE', __DIR__ . '/../storage/smtp_sequential.log');
function write_log($msg) {
    $ts = date('Y-m-d H:i:s');
    file_put_contents(LOG_FILE, "[$ts] $msg\n", FILE_APPEND);
}

// --- Insert logs into CRM_logs ---
function insert_smtp_log($conn_logs, $email, $steps, $validation, $validation_response) {
    $stmt = $conn_logs->prepare("INSERT INTO email_smtp_checks2 
        (email, smtp_connection, ehlo, mail_from, rcpt_to, validation, validation_response) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssssss",
        $email,
        $steps['smtp_connection'],
        $steps['ehlo'],
        $steps['mail_from'],
        $steps['rcpt_to'],
        $validation,
        $validation_response
    );
    $stmt->execute();
    $stmt->close();
}

// --- SMTP verification ---
function verifyEmailViaSMTP($email, $domain, $conn_logs) {
    $ip = false;
    $steps = [
        'smtp_connection' => 'No',
        'ehlo'            => 'No',
        'mail_from'       => 'No',
        'rcpt_to'         => 'No'
    ];

    // MX record
    if (getmxrr($domain, $mxhosts) && !empty($mxhosts)) {
        $mxIp = @gethostbyname($mxhosts[0]);
        if ($mxIp !== $mxhosts[0] && filter_var($mxIp, FILTER_VALIDATE_IP)) {
            $ip = $mxIp;
        }
    }
    // A record fallback
    if (!$ip) {
        $aRecord = @gethostbyname($domain);
        if ($aRecord !== $domain && filter_var($aRecord, FILTER_VALIDATE_IP)) {
            $ip = $aRecord;
        }
    }
    if (!$ip) {
        insert_smtp_log($conn_logs, $email, $steps, "No valid MX or A record found", "No valid MX or A record found");
        return ["status"=>"invalid","result"=>0,"response"=>"No MX/A record","domain_status"=>0,"validation_status"=>"invalid","validation_response"=>"No valid MX/A record"];
    }

    $port = 25;
    $timeout = 15;
    $smtp = @stream_socket_client("tcp://$ip:$port", $errno, $errstr, $timeout);
    if (!$smtp) {
        insert_smtp_log($conn_logs, $email, $steps, "Connection failed: $errstr", "Connection failed: $errstr");
        return ["status"=>"invalid","result"=>0,"response"=>"Connection failed","domain_status"=>0,"validation_status"=>"invalid","validation_response"=>"Connection failed: $errstr"];
    }

    $steps['smtp_connection'] = 'Yes';
    stream_set_timeout($smtp, $timeout);
    $response = fgets($smtp, 4096);
    if ($response === false || substr($response, 0, 3) != "220") {
        fclose($smtp);
        insert_smtp_log($conn_logs, $email, $steps, "SMTP not ready", "SMTP not ready");
        return ["status"=>"invalid","result"=>0,"response"=>"SMTP not ready","domain_status"=>0,"validation_status"=>"invalid","validation_response"=>"SMTP not ready"];
    }

    fputs($smtp, "EHLO server.relyon.co.in\r\n");
    $ehlo_ok = false;
    while ($line = fgets($smtp, 4096)) {
        if (substr($line, 3, 1) == " ") { $ehlo_ok = true; break; }
    }
    if (!$ehlo_ok) {
        fclose($smtp);
        insert_smtp_log($conn_logs, $email, $steps, "EHLO failed", "EHLO failed");
        return ["status"=>"invalid","result"=>0,"response"=>"EHLO failed","domain_status"=>0,"validation_status"=>"invalid","validation_response"=>"EHLO failed"];
    }
    $steps['ehlo'] = 'Yes';

    fputs($smtp, "MAIL FROM:<info@relyon.co.in>\r\n");
    if (fgets($smtp, 4096) === false) {
        fclose($smtp);
        insert_smtp_log($conn_logs, $email, $steps, "MAIL FROM failed", "MAIL FROM failed");
        return ["status"=>"invalid","result"=>0,"response"=>"MAIL FROM failed","domain_status"=>0,"validation_status"=>"invalid","validation_response"=>"MAIL FROM failed"];
    }
    $steps['mail_from'] = 'Yes';

    fputs($smtp, "RCPT TO:<$email>\r\n");
    $rcpt_resp   = fgets($smtp, 4096);
    $responseCode = $rcpt_resp !== false ? substr($rcpt_resp, 0, 3) : null;
    $steps['rcpt_to'] = ($responseCode == "250" || $responseCode == "251") ? 'Yes' : 'No';

    fputs($smtp, "QUIT\r\n");
    fclose($smtp);

    $validation_response = substr($rcpt_resp ?: '', 0, 1000);

    if ($responseCode == "250" || $responseCode == "251") {
        insert_smtp_log($conn_logs, $email, $steps, $ip, $validation_response);
        return ["status"=>"valid","result"=>1,"response"=>$ip,"domain_status"=>1,"validation_status"=>"valid","validation_response"=>$ip];
    } elseif (in_array($responseCode, ["450","451","452"])) {
        insert_smtp_log($conn_logs, $email, $steps, $rcpt_resp, $validation_response);
        return ["status"=>"retryable","result"=>2,"response"=>$rcpt_resp,"domain_status"=>2,"validation_status"=>"retryable","validation_response"=>$rcpt_resp];
    } else {
        insert_smtp_log($conn_logs, $email, $steps, $rcpt_resp, $validation_response);
        return ["status"=>"invalid","result"=>0,"response"=>$rcpt_resp,"domain_status"=>0,"validation_status"=>"invalid","validation_response"=>$rcpt_resp];
    }
}

// --- MAIN EXECUTION ---
try {
    $conn->query("UPDATE csv_list SET status = 'running' WHERE status = 'pending'");

    $res = $conn->query("SELECT id, raw_emailid, sp_domain FROM emails WHERE domain_status=1 AND domain_processed=0");
    if (!$res || $res->num_rows == 0) {
        $conn->query("UPDATE csv_list SET status = 'completed' WHERE status = 'running'");
        echo json_encode(["status"=>"success","processed"=>0,"message"=>"No emails found to process."]);
        exit;
    }

    $processed = 0;
    $start_time = microtime(true);

    while ($row = $res->fetch_assoc()) {
        $email_id = $row['id'];
        $email    = $row['raw_emailid'];
        $domain   = $row['sp_domain'];

        $verify = verifyEmailViaSMTP($email, $domain, $conn_logs);

        if (isset($verify['validation_response'])) {
            $verify['validation_response'] = mb_convert_encoding($verify['validation_response'], 'UTF-8', 'UTF-8');
            $verify['validation_response'] = mb_substr($verify['validation_response'], 0, 1000, 'UTF-8');
        }

        $update = $conn->prepare("UPDATE emails SET 
            domain_status=?, domain_processed=1, validation_status=?, validation_response=? 
            WHERE id=?");
        $update->bind_param("issi",
            $verify['domain_status'],
            $verify['validation_status'],
            $verify['validation_response'],
            $email_id
        );
        $update->execute();
        $update->close();

        $processed++;
        write_log("Processed $email_id ($email): {$verify['status']} - {$verify['response']}");
    }

    $total_time = microtime(true) - $start_time;

    // Stats update
    update_all_csv_list_stats($conn);

    echo json_encode([
        "status"          => "success",
        "processed"       => $processed,
        "time_seconds"    => round($total_time, 2),
        "message"         => "Sequential SMTP processing completed"
    ]);
} catch (Exception $e) {
    echo json_encode(["status"=>"error","message"=>$e->getMessage()]);
} finally {
    $conn->close();
    $conn_logs->close();
}

// --- Update stats ---
function update_all_csv_list_stats($conn) {
    $sql = "
        UPDATE csv_list cl
        JOIN (
          SELECT csv_list_id,
            SUM(CASE WHEN domain_status = 1 THEN 1 ELSE 0 END) AS valid_count,
            SUM(CASE WHEN domain_status = 0 THEN 1 ELSE 0 END) AS invalid_count,
            COUNT(*) AS total_emails
          FROM emails
          WHERE domain_status IN (0,1)
          GROUP BY csv_list_id
        ) e ON e.csv_list_id = cl.id
        SET cl.valid_count = e.valid_count,
            cl.invalid_count = e.invalid_count,
            cl.total_emails = e.total_emails";
    $conn->query($sql);
}
