<?php
session_start();
require_once '../db_con.php';
$user_id = $_SESSION['user_id'] ?? 0;
$img = $_POST['img'] ?? '';
if (!$img) exit(json_encode(['success'=>false]));
$stmt = $conn->prepare("SELECT menu_images FROM fb_owner WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$menu_images = json_decode($row['menu_images'], true) ?: [];
$menu_images = array_filter($menu_images, function($item) use ($img) {
    return (is_array($item) ? $item['path'] : $item) !== $img;
});
$stmt = $conn->prepare("UPDATE fb_owner SET menu_images=? WHERE user_id=?");
$stmt->bind_param("si", json_encode(array_values($menu_images)), $user_id);
$stmt->execute();
echo json_encode(['success'=>true]);