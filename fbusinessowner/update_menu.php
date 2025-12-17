<?php
session_start();
require_once '../db_con.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'error' => 'Not logged in']);
  exit;
}

$menu_id = $_POST['menu_id'] ?? 0;

// Check if image is uploaded
if (isset($_FILES['menu_image']) && $_FILES['menu_image']['error'] === 0) {
  $targetDir = "../uploads/menus/";
  if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

  $fileName = basename($_FILES['menu_image']['name']);
  $targetFile = $targetDir . time() . "_" . $fileName;

  if (move_uploaded_file($_FILES['menu_image']['tmp_name'], $targetFile)) {
    // Update image path in DB
    $stmt = $conn->prepare("UPDATE menus SET menu_image = ? WHERE menu_id = ?");
    $stmt->bind_param("si", $targetFile, $menu_id);
    $stmt->execute();

    echo json_encode(['success' => true, 'image_path' => $targetFile]);
    exit;
  } else {
    echo json_encode(['success' => false, 'error' => 'Image upload failed']);
    exit;
  }
}

// Handle menu details update
$menu_name = $_POST['menu_name'] ?? '';
$menu_price = $_POST['menu_price'] ?? '';
$category_id = $_POST['category_id'] ?? '';

if (!$menu_id || !$menu_name || !$menu_price || !$category_id) {
  echo json_encode(['success' => false, 'error' => 'Invalid input']);
  exit;
}

$stmt = $conn->prepare("UPDATE menus SET menu_name=?, menu_price=?, category_id=? WHERE menu_id=?");
$stmt->bind_param("sdii", $menu_name, $menu_price, $category_id, $menu_id);
$ok = $stmt->execute();

echo json_encode(['success' => $ok]);
?>
