<?php
session_start();
require_once '../db_con.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request method.";
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to perform this action.";
    exit;
}

if (!isset($_POST['review_id'])) {
    echo "Review ID is missing.";
    exit;
}

$review_id = intval($_POST['review_id']);
$user_id = intval($_SESSION['user_id']);


$stmt_check = $conn->prepare("SELECT photo, video FROM reviews WHERE id = ? AND user_id = ?");
$stmt_check->bind_param("ii", $review_id, $user_id);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows === 0) {
    echo "Review not found or you do not have permission to delete it.";
    $stmt_check->close();
    exit;
}

$review_data = $result->fetch_assoc();
$stmt_check->close();

// 5. Delete the record from Database
$stmt_delete = $conn->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
$stmt_delete->bind_param("ii", $review_id, $user_id);

if ($stmt_delete->execute()) {
    
    // Delete Photo
    if (!empty($review_data['photo'])) {
        $photo_path = "../" . $review_data['photo'];
        if (file_exists($photo_path)) {
            unlink($photo_path);
        }
    }

    // Delete Video
    if (!empty($review_data['video'])) {
        $video_path = "../" . $review_data['video']; 
        if (file_exists($video_path)) {
            unlink($video_path);
        }
    }

    echo "success";
} else {
    echo "Database error: " . $conn->error;
}

$stmt_delete->close();
$conn->close();
?>