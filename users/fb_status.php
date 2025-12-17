<?php
session_start();
require_once '../db_con.php'; 

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'not_logged_in']);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT fb_status FROM fb_owner WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    echo json_encode(['fb_status' => $row['fb_status']]);
} else {
    echo json_encode(['fb_status' => 'closed']);
}
$stmt->close();
$conn->close();
?>
