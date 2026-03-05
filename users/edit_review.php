<?php
include '../db_con.php';
require_once '../upload_utils.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = intval($_POST['review_id'] ?? 0);
    $user_id   = $_SESSION['user_id'] ?? 0;
    $rating    = intval($_POST['rating'] ?? 0);
    $comment   = trim($_POST['comment'] ?? '');

    if ($review_id <= 0 || $user_id <= 0) {
        echo json_encode(['status' => 'error', 'msg' => 'Invalid request']);
        exit();
    }

    // Check ownership
    $stmt = $conn->prepare("SELECT user_id, photo, video FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    $stmt->bind_result($ownerId, $oldPhoto, $oldVideo);
    $stmt->fetch();
    $stmt->close();

    if ($ownerId != $user_id) {
        echo json_encode(['status' => 'error', 'msg' => 'Unauthorized']);
        exit();
    }

    // Handle photo upload with compression.
    $photoPath = $oldPhoto;
    $photoReplaced = false;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $ext = $ext !== '' ? $ext : 'jpg';
        $photoName = 'uploads/photos/' . uniqid('review_photo_', true) . '.' . $ext;
        if (tlm_store_uploaded_with_compression($_FILES['photo']['tmp_name'], "../$photoName")) {
            $photoPath = $photoName;
            $photoReplaced = true;
        }
    }

    // Handle video upload.
    $videoPath = $oldVideo;
    $videoReplaced = false;
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
        $ext = $ext !== '' ? $ext : 'mp4';
        $videoName = 'uploads/videos/' . uniqid('review_video_', true) . '.' . $ext;
        if (tlm_move_uploaded_fallback($_FILES['video']['tmp_name'], "../$videoName")) {
            $videoPath = $videoName;
            $videoReplaced = true;
        }
    }

    // Update database
    $update = $conn->prepare("UPDATE reviews SET rating=?, comment=?, photo=?, video=?, updated_at=NOW() WHERE id=?");
    $update->bind_param("isssi", $rating, $comment, $photoPath, $videoPath, $review_id);

    if ($update->execute()) {
        if ($photoReplaced && !empty($oldPhoto) && tlm_normalize_stored_path($oldPhoto) !== tlm_normalize_stored_path($photoPath)) {
            tlm_delete_storage_file($oldPhoto, dirname(__DIR__));
        }
        if ($videoReplaced && !empty($oldVideo) && tlm_normalize_stored_path($oldVideo) !== tlm_normalize_stored_path($videoPath)) {
            tlm_delete_storage_file($oldVideo, dirname(__DIR__));
        }

        // Add cache-busting query string
        $photoUrl = $photoPath ? $photoPath . '?v=' . time() : '';
        $videoUrl = $videoPath ? $videoPath . '?v=' . time() : '';
        echo json_encode(['status' => 'success', 'photo' => $photoUrl, 'video' => $videoUrl]);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Failed to update review']);
    }
    $update->close();
}
?>
