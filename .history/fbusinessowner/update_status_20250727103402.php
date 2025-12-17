<?php
session_start();
require_once '../db_con.php';

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'msg' => 'Not logged in']);
  exit;
}

$user_id = $_SESSION['user_id'];
$status = ($_POST['status'] === 'open') ? 'open' : 'closed';

$stmt = $conn->prepare("UPDATE fb_owner SET fb_status = ? WHERE user_id = ?");
$stmt->bind_param('si', $status, $user_id);
$success = $stmt->execute();
$stmt->close();

echo json_encode(['success' => $success]);
?>