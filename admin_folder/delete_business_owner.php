<?php
session_start();
require_once '../db_con.php';

// Check if admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "Unauthorized";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);

    // Start Transaction to ensure all data is deleted safely
    $conn->begin_transaction();

    try {
        // 1. Delete from fb_owner
        $stmt1 = $conn->prepare("DELETE FROM fb_owner WHERE user_id = ?");
        $stmt1->bind_param("i", $user_id);
        $stmt1->execute();

        // 2. Delete from business_application
        $stmt2 = $conn->prepare("DELETE FROM business_application WHERE user_id = ?");
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();

        // 3. Delete from accounts
        $stmt3 = $conn->prepare("UPDATE accounts SET user_type = 'user' WHERE user_id = ?");
        $stmt3->bind_param("i", $user_id);
        $stmt3->execute();

        // Commit changes
        $conn->commit();
        echo "success";

    } catch (Exception $e) {
        // If error, undo changes
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid Request";
}
?>