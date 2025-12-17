<?php
include '../db_con.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fbowner_id = intval($_POST['fbowner_id'] ?? 0);
    $user_id = intval($_POST['user_id'] ?? 0);
    $reviewer_name = 'Anonymous';
    $rating = intval($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    // 🔹 Get user name from accounts table (if exists)
    if ($user_id > 0) {
        $stmtUser = $conn->prepare("SELECT name FROM accounts WHERE user_id = ?");
        $stmtUser->bind_param("i", $user_id);
        $stmtUser->execute();
        $stmtUser->bind_result($dbName);
        if ($stmtUser->fetch()) {
            $reviewer_name = $dbName;
        }
        $stmtUser->close();
    }

    // 🔹 Base folder for uploads
    $uploadDir = "../review_uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // create folder if missing
    }

    // 🔹 Handle photo upload
    $photoPath = null;
    if (!empty($_FILES['photo']['name'])) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photoName = "photo_" . time() . "_" . uniqid() . "." . $ext;
        $photoPath = "review_uploads/" . $photoName; // relative path for DB
        move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoName);
    }

    // 🔹 Handle video upload
    $videoPath = null;
    if (!empty($_FILES['video']['name'])) {
        $ext = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
        $videoName = "video_" . time() . "_" . uniqid() . "." . $ext;
        $videoPath = "review_uploads/" . $videoName; // relative path for DB
        move_uploaded_file($_FILES['video']['tmp_name'], $uploadDir . $videoName);
    }

    // 🔹 Insert into DB
    $stmt = $conn->prepare("INSERT INTO reviews 
        (fbowner_id, user_id, reviewer_name, rating, `comment`, photo, video) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "iisisss", 
        $fbowner_id,     // i
        $user_id,        // i
        $reviewer_name,  // s
        $rating,         // i
        $comment,        // s
        $photoPath,      // s
        $videoPath       // s
    );

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "DB Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
