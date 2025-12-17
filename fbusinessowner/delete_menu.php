<?php
session_start();
require_once '../db_con.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'error' => 'Not logged in']);
  exit;
}

$menu_id = intval($_POST['menu_id']);
$stmt = $conn->prepare("DELETE FROM menus WHERE menu_id=?");
$stmt->bind_param("i", $menu_id);
echo json_encode(['success' => $stmt->execute()]);
