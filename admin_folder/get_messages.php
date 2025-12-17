<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../db_con.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$receiver_id = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : 0;
$other_id = isset($_GET['other_id']) ? intval($_GET['other_id']) : $receiver_id;

if ($other_id === 0) {
    echo json_encode([]);
    exit;
}

$conn->query("SET time_zone = '+08:00'");

$query = "
  SELECT 
    id, 
    sender_id, 
    receiver_id, 
    message,
    `timestamp`,  /* Return raw timestamp so JS can format it */
    DATE_FORMAT(`timestamp`, '%b %d, %Y %h:%i %p') AS created_at
  FROM messages
  WHERE (sender_id = ? AND receiver_id = ?)
     OR (sender_id = ? AND receiver_id = ?)
  ORDER BY messages.timestamp ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param('iiii', $user_id, $other_id, $other_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
$stmt->close();

echo json_encode($messages);
?>