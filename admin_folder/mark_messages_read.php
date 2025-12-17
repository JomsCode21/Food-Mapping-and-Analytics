<?php
session_start();
require_once '../db_con.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['sender_id'])) {
    exit;
}

$current_user_id = intval($_SESSION['user_id']); // The Admin (Receiver)
$sender_id = intval($_POST['sender_id']);        // The Owner (Sender)

// Mark messages from this sender as read
$stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?");
$stmt->bind_param("ii", $sender_id, $current_user_id);
$stmt->execute();
?>