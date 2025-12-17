<?php
session_start();
require_once '../db_con.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT fb_gallery FROM fb_owner WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$gallery = [];
if ($row && $row['fb_gallery']) {
    $gallery = json_decode($row['fb_gallery'], true);
    if (!is_array($gallery)) $gallery = [];
}
header('Content-Type: application/json');
echo json_encode($gallery);
exit;