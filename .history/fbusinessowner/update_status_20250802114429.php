<?php
// filepath: c:\xampp\htdocs\TASTELIBMANAN\fbusinessowner\update_status.php
session_start();
require_once '../db_con.php';

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Session Expired. Please log in again.']));
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['status'])) {
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE fb_owner SET fb_status = ? WHERE user_id = ?");
    $stmt->bind_param("si", $status, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Status not provided.']);
}

$conn->close();
?>