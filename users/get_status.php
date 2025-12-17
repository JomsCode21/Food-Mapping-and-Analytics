<?php
require_once '../db_con.php';
require_once '../status_logic.php'; // Import the logic

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$status = 'closed';

if ($user_id > 0) {
    // This single line does all the calculation and DB updating
    $status = checkAndSyncBusinessStatus($conn, $user_id);
}

echo json_encode(['status' => $status]);
?>