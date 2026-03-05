<?php
session_start();
require_once '../db_con.php';
require_once '../upload_utils.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];
$type = $_POST['type'] ?? ''; // 'logo' or 'cover'

if (!in_array($type, ['logo', 'cover']) || empty($_FILES['file']['name'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

if (!tlm_is_uploaded_image($_FILES['file']['tmp_name'])) {
    echo json_encode(['success' => false, 'message' => 'Only image files are allowed.']);
    exit();
}

// Fetch business name and current file path for cleanup on replace.
$stmt = $conn->prepare("SELECT fb_name, fb_photo, fb_cover FROM fb_owner WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

$business_name = $row['fb_name'] ?? 'default';
$business_folder = preg_replace('/[^A-Za-z0-9_\-]/', '_', $business_name);
$oldPath = $type === 'logo' ? ($row['fb_photo'] ?? '') : ($row['fb_cover'] ?? '');

$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
$ext = $ext !== '' ? $ext : 'jpg';
$filename = uniqid($type . '_', true) . '.' . $ext;

if ($type === 'logo') {
    $upload_dir = "../uploads/business_photo/" . $business_folder . "/";
    $db_column = "fb_photo";
    $relative_path_for_db = 'uploads/business_photo/' . $business_folder . '/' . $filename;
} else {
    $upload_dir = "../uploads/business_cover/" . $business_folder . "/";
    $db_column = "fb_cover";
    $relative_path_for_db = 'uploads/business_cover/' . $business_folder . '/' . $filename;
}

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$target_file = $upload_dir . $filename;

if (tlm_store_uploaded_with_compression($_FILES['file']['tmp_name'], $target_file)) {
    $update = $conn->prepare("UPDATE fb_owner SET $db_column = ? WHERE user_id = ?");
    $update->bind_param("si", $relative_path_for_db, $user_id);

    if ($update->execute()) {
        if (!empty($oldPath) && tlm_normalize_stored_path($oldPath) !== tlm_normalize_stored_path($relative_path_for_db)) {
            tlm_delete_storage_file($oldPath, dirname(__DIR__));
        }

        echo json_encode([
            'success' => true,
            'new_src' => '../' . $relative_path_for_db,
            'message' => ucfirst($type) . ' updated successfully!'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'File upload failed']);
}
?>