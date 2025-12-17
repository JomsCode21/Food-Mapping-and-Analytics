<?php
session_start();
require_once '../db_con.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

$user_id = $_SESSION['user_id'];

// Delete all images
if (isset($_POST['delete_all']) && $_POST['delete_all'] == '1') {
    $stmt = $conn->prepare("SELECT fb_gallery FROM fb_owner WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row && $row['fb_gallery']) {
        $gallery = json_decode($row['fb_gallery'], true);
        if (is_array($gallery)) {
            foreach ($gallery as $img) {
                $file_path = '../' . $img;
                if (file_exists($file_path)) unlink($file_path);
            }
        }
    }
    $stmt = $conn->prepare("UPDATE fb_owner SET fb_gallery = ? WHERE user_id = ?");
    $empty = json_encode([]);
    $stmt->bind_param("si", $empty, $user_id);
    $stmt->execute();
    echo 'success';
    exit;
}

// Delete single image
$img = isset($_POST['img']) ? $_POST['img'] : '';
if (!$img) {
    http_response_code(400);
    exit('No image specified');
}

$stmt = $conn->prepare("SELECT fb_gallery FROM fb_owner WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    http_response_code(404);
    exit('Business not found');
}

$gallery = json_decode($row['fb_gallery'], true);
if (!is_array($gallery)) $gallery = [];

$gallery = array_values(array_filter($gallery, function($item) use ($img) {
    return $item !== $img;
}));

$stmt = $conn->prepare("UPDATE fb_owner SET fb_gallery = ? WHERE user_id = ?");
$stmt->bind_param("si", json_encode($gallery), $user_id);
$stmt->execute();

$file_path = '../' . $img;
if (file_exists($file_path)) {
    unlink($file_path);
}

echo 'success';