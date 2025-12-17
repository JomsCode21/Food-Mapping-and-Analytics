<?php
include '../db_con.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = intval($_POST['review_id'] ?? 0);
    $user_id   = $_SESSION['user_id'] ?? 0;
    $rating    = intval($_POST['rating'] ?? 0);
    $comment   = trim($_POST['comment'] ?? '');

    if ($review_id <= 0 || $user_id <= 0) {
        echo json_encode(['status'=>'error','msg'=>'Invalid request']);
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
        echo json_encode(['status'=>'error','msg'=>'Unauthorized']);
        exit();
    }

    // Handle photo upload
    $photoPath = $oldPhoto;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photoName = 'uploads/photos/' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], "../$photoName")) {
            $photoPath = $photoName;
        }
    }

    // Handle video upload
    $videoPath = $oldVideo;
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
        $videoName = 'uploads/videos/' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['video']['tmp_name'], "../$videoName")) {
            $videoPath = $videoName;
        }
    }

    // Update database
    $update = $conn->prepare("UPDATE reviews SET rating=?, comment=?, photo=?, video=?, updated_at=NOW() WHERE id=?");
    $update->bind_param("isssi", $rating, $comment, $photoPath, $videoPath, $review_id);

    if ($update->execute()) {
        // Add cache-busting query string
        $photoUrl = $photoPath ? $photoPath . '?v=' . time() : '';
        $videoUrl = $videoPath ? $videoPath . '?v=' . time() : '';
        echo json_encode(['status'=>'success','photo'=>$photoUrl,'video'=>$videoUrl]);
    } else {
        echo json_encode(['status'=>'error','msg'=>'Failed to update review']);
    }
    $update->close();
}
?>
