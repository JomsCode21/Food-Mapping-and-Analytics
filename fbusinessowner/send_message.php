<?php
// /admin_folder/send_message.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../db_con.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$sender_id = (int)$_SESSION['user_id'];
$receiver_id = (int)($_POST['receiver_id'] ?? 0);
$message = trim($_POST['message'] ?? '');

$conn->query("SET time_zone = '+08:00'");

if ($receiver_id === 0 || $message === '') {
    echo json_encode(['success' => false, 'error' => 'Missing receiver or message']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
