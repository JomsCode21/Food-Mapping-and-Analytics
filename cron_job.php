<?php
// Set correct timezone for the Philippines
date_default_timezone_set('Asia/Manila');

// Define path
$dir = __DIR__;
$logFile = $dir . '/cron_log.txt';

// Function to write to log
function writeLog($message, $file) {
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($file, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
    echo $message; // Still echo for the screen just in case
}

// Check database connection file
if (file_exists($dir . '/db_con.php')) {
    include $dir . '/db_con.php';
} else {
    writeLog("CRITICAL ERROR: Could not find db_con.php", $logFile);
    exit;
}

// Run Query
if (isset($conn) && $conn) {
    $sql = "UPDATE fb_owner 
            SET is_new = 0
            WHERE created_at < DATE_SUB(NOW(), INTERVAL 2 WEEK) 
            AND is_new = 1";

    if ($conn->query($sql) === TRUE) {
        // Check if any rows were actually affected
        if ($conn->affected_rows > 0) {
            writeLog("SUCCESS: Updated " . $conn->affected_rows . " records.", $logFile);
        } else {
            writeLog("SUCCESS: Run completed, but no records needed updating.", $logFile);
        }
    } else {
        writeLog("SQL ERROR: " . $conn->error, $logFile);
    }
} else {
    writeLog("CONNECTION ERROR: Database connection failed.", $logFile);
}
?>