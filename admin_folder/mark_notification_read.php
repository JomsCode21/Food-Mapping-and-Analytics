<?php
    require_once '../db_con.php';
    $id = intval($_GET['id'] ?? 0);
        $stmt = $conn->prepare("UPDATE notification SET is_read = 1 WHERE notification_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
?>