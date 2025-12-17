<?php
session_start();
require_once '../db_con.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['sender_id'])) {
    exit;
}

$receiver_id = $_SESSION['user_id'];
$sender_id = intval($_POST['sender_id']);

// Update messages to Read (1) where I am the receiver and Admin is sender
$stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE receiver_id = ? AND sender_id = ?");
$stmt->bind_param("ii", $receiver_id, $sender_id);
$stmt->execute();
$stmt->close();
$conn->close();
?>