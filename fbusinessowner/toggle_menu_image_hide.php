<?php
session_start();
require_once '../db_con.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Session expired']);
    exit;
}
$user_id = $_SESSION['user_id'];
$img = $_POST['img'] ?? '';
$hide = isset($_POST['hide']) ? (int)$_POST['hide'] : 0;

if (!$img) {
    echo json_encode(['success' => false, 'error' => 'No image specified']);
    exit;
}

// Get current menu_images
$stmt = $conn->prepare("SELECT menu_images FROM fb_owner WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$menu_images = json_decode($row['menu_images'] ?? '[]', true);

// Update the hidden status for the specific image
foreach ($menu_images as &$image) {
    if ($image['path'] === $img) {
        $image['hidden'] = $hide ? 1 : 0;
        break;
    }
}
$stmt->close();

// Save back to DB
$stmt2 = $conn->prepare("UPDATE fb_owner SET menu_images = ? WHERE user_id = ?");
$stmt2->bind_param("si", json_encode($menu_images), $user_id);
$stmt2->execute();
$stmt2->close();

echo json_encode(['success' => true]);