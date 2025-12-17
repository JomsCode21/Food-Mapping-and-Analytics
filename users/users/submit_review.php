<?php
require_once '../db_con.php';
session_start();

$fbowner_id = isset($_POST['fbowner_id']) ? intval($_POST['fbowner_id']) : 0;
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
$reviewer_name = 'Anonymous';

// If user is logged in, get their name
if ($user_id) {
    $stmt = $conn->prepare("SELECT name FROM accounts WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name);
    if ($stmt->fetch()) {
        $reviewer_name = $name;
    }
    $stmt->close();
}

// Handle photo upload
$photo_path = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photo_name = uniqid('review_photo_', true) . '.' . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $photo_dir = '../uploads/review_photos/';
    if (!is_dir($photo_dir)) mkdir($photo_dir, 0777, true);
    move_uploaded_file($_FILES['photo']['tmp_name'], $photo_dir . $photo_name);
    $photo_path = 'uploads/review_photos/' . $photo_name;
}

// Handle video upload
$video_path = null;
if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
    $video_name = uniqid('review_video_', true) . '.' . pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
    $video_dir = '../uploads/review_videos/';
    if (!is_dir($video_dir)) mkdir($video_dir, 0777, true);
    move_uploaded_file($_FILES['video']['tmp_name'], $video_dir . $video_name);
    $video_path = 'uploads/review_videos/' . $video_name;
}

// Insert review into database
$stmt = $conn->prepare("INSERT INTO reviews (fbowner_id, user_id, reviewer_name, rating, comment, photo, video) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iisisss", $fbowner_id, $user_id, $reviewer_name, $rating, $comment, $photo_path, $video_path);
$stmt->execute();
$stmt->close();

echo json_encode(['success' => true]);
?>