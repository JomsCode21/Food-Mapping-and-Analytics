<?php
session_start();
require_once '../db_con.php';
require_once '../upload_utils.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'error' => 'Not logged in']);
  exit;
}

$menu_id = isset($_POST['menu_id']) ? intval($_POST['menu_id']) : 0;

// Check if image is uploaded
if (isset($_FILES['menu_image']) && $_FILES['menu_image']['error'] === 0) {
  $targetDir = "../uploads/menus/";
  if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
  }

  $oldImage = null;
  $stmtOld = $conn->prepare("SELECT menu_image FROM menus WHERE menu_id = ?");
  $stmtOld->bind_param("i", $menu_id);
  $stmtOld->execute();
  $stmtOld->bind_result($oldImage);
  $stmtOld->fetch();
  $stmtOld->close();

  $ext = strtolower(pathinfo($_FILES['menu_image']['name'], PATHINFO_EXTENSION));
  $ext = $ext !== '' ? $ext : 'jpg';
  $fileName = uniqid('menu_', true) . '.' . $ext;
  $targetFile = $targetDir . $fileName;
  $relativePath = 'uploads/menus/' . $fileName;

  if (tlm_store_uploaded_with_compression($_FILES['menu_image']['tmp_name'], $targetFile)) {
    $stmt = $conn->prepare("UPDATE menus SET menu_image = ? WHERE menu_id = ?");
    $stmt->bind_param("si", $relativePath, $menu_id);

    if ($stmt->execute()) {
      if (!empty($oldImage) && tlm_normalize_stored_path($oldImage) !== tlm_normalize_stored_path($relativePath)) {
        tlm_delete_storage_file($oldImage, dirname(__DIR__));
      }

      echo json_encode(['success' => true, 'image_path' => $relativePath]);
      exit;
    }

    echo json_encode(['success' => false, 'error' => 'Database update failed']);
    exit;
  }

  echo json_encode(['success' => false, 'error' => 'Image upload failed']);
  exit;
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
