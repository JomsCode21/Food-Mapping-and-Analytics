<?php
session_start();
header('Content-Type: application/json');
require_once '../db_con.php';

// Set Timezone to Philippines
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Session expired']);
    exit;
}
$user_id = (int)$_SESSION['user_id'];

if (!isset($_POST['status'])) {
    echo json_encode(['success' => false, 'message' => 'No status provided']);
    exit;
}

$status = ($_POST['status'] === 'open') ? 'open' : 'closed';
$current_timestamp = date('Y-m-d H:i:s');

// Ensure a row exists
$stmt_check = $conn->prepare("SELECT 1 FROM fb_owner WHERE user_id = ?");
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$res_check = $stmt_check->get_result();

if ($res_check->num_rows === 0) {
    // Insert new row with timestamp
    $stmt_ins = $conn->prepare("INSERT INTO fb_owner (user_id, fb_status, status_last_updated) VALUES (?, ?, ?)");
    $stmt_ins->bind_param("iss", $user_id, $status, $current_timestamp);
    $ok_ins = $stmt_ins->execute();
    $stmt_ins->close();
    if ($ok_ins) {
        echo json_encode(['success' => true, 'status' => $status]);
        exit;
    }
}
$stmt_check->close();

// Update existing row with new status AND timestamp
$stmt = $conn->prepare("UPDATE fb_owner SET fb_status = ?, status_last_updated = ? WHERE user_id = ?");
$stmt->bind_param("ssi", $status, $current_timestamp, $user_id);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
    echo json_encode(['success' => true, 'status' => $status]);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed']);
}
$conn->close();
?>