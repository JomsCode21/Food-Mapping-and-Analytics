<?php
require_once '../db_con.php';
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['owner_id'] ?? '';
if ($user_id) {
    $stmt2 = $conn->prepare("UPDATE accounts SET user_type = 'fb_owner' WHERE user_id = ?");
    $stmt2->bind_param("i", $user_id);
    $success = $stmt2->execute();
    $stmt2->close();
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'error' => 'No user_id']);
}
?>