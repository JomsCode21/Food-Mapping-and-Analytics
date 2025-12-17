<?php
session_start();
require_once '../db_con.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['total' => 0, 'senders' => []]);
    exit;
}

$user_id = intval($_SESSION['user_id']);

// Count unread messages where the current user is the RECEIVER
$query = "
    SELECT sender_id, COUNT(*) as count 
    FROM messages 
    WHERE receiver_id = ? AND is_read = 0 
    GROUP BY sender_id
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$senders = [];
$total_unread = 0;

while ($row = $result->fetch_assoc()) {
    $sender_id = $row['sender_id'];
    $count = intval($row['count']);
    
    $senders[$sender_id] = $count; // Store count per sender
    $total_unread += $count;       // Add to total
}

echo json_encode(['total' => $total_unread, 'senders' => $senders]);
?>