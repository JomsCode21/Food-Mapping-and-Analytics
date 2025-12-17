<?php
error_reporting(0);
ini_set('display_errors', 0);

// Tell the browser this is strictly JSON
header('Content-Type: application/json');

// Start output buffering to catch any accidental echoes from includes
ob_start();

require_once '../db_con.php';
require_once '../status_logic.php'; 

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$status = 'closed';

if ($user_id > 0) {
    // If checkAndSyncBusinessStatus causes an error, the try-catch block is safer,
    $status = checkAndSyncBusinessStatus($conn, $user_id);
}

// Clear the buffer. 
// If db_con.php had an "echo" or a newline space, this deletes it.
ob_clean(); 

echo json_encode(['status' => $status]);
exit(); // Stop script execution immediately
?>