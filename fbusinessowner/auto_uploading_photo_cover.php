<?php
session_start();
require_once '../db_con.php'; 

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

// Fetch Business Name to generate the folder name
$stmt = $conn->prepare("SELECT fb_name FROM fb_owner WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

// Folder Naming Logic (Matched to your snippet)
$business_name = $row['fb_name'] ?? 'default';
$business_folder = preg_replace('/[^A-Za-z0-9_\-]/', '_', $business_name);

// Set Paths based on Type
$filename = uniqid() . '_' . basename($_FILES['file']['name']);

if ($type === 'logo') {
    $upload_dir = "../uploads/business_photo/" . $business_folder . "/";
    $db_column = "fb_photo";
    // Path string format for Database
    $relative_path_for_db = 'uploads/business_photo/' . $business_folder . '/' . $filename;
} else {
    $upload_dir = "../uploads/business_cover/" . $business_folder . "/";
    $db_column = "fb_cover";
    // Path string format for Database
    $relative_path_for_db = 'uploads/business_cover/' . $business_folder . '/' . $filename;
}

// Ensure directory exists
if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }

$target_file = $upload_dir . $filename;

// 4. Process Upload
if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
    
    // Update Database
    $update = $conn->prepare("UPDATE fb_owner SET $db_column = ? WHERE user_id = ?");
    $update->bind_param("si", $relative_path_for_db, $user_id);
    
    if ($update->execute()) {
        echo json_encode([
            'success' => true, 
            // We add '../' here so the frontend JS can display it immediately without reload
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