<?php
session_start();
require_once '../db_con.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$receiver_id = $_SESSION['user_id'];
$sender_id = isset($_GET['sender_id']) ? intval($_GET['sender_id']) : 0; // The Admin ID

// Count messages where I am the receiver, sent by Admin, and is_read is 0
$stmt = $conn->prepare("SELECT COUNT(*) as unread_count FROM messages WHERE receiver_id = ? AND sender_id = ? AND is_read = 0");
$stmt->bind_param("ii", $receiver_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['count' => $row['unread_count']]);

$stmt->close();
$conn->close();
?>