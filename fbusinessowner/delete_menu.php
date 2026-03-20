<?php
session_start();
require_once '../db_con.php';
require_once '../upload_utils.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'error' => 'Not logged in']);
  exit;
}

$menu_id = intval($_POST['menu_id']);

$oldImage = null;
$stmtSelect = $conn->prepare("SELECT menu_image FROM menus WHERE menu_id = ?");
$stmtSelect->bind_param("i", $menu_id);
$stmtSelect->execute();
$stmtSelect->bind_result($oldImage);
$stmtSelect->fetch();
$stmtSelect->close();

$stmt = $conn->prepare("DELETE FROM menus WHERE menu_id=?");
$stmt->bind_param("i", $menu_id);
$success = $stmt->execute();

if ($success && !empty($oldImage)) {
  tlm_delete_storage_file($oldImage, dirname(__DIR__));
}

echo json_encode(['success' => $success]);
