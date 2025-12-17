<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once '../db_con.php';

    if (isset($_POST['ba_id'])) {
        $ba_id = intval($_POST['ba_id']);
        $stmt_remove = $conn->prepare("DELETE FROM business_application WHERE ba_id = ?");
        if (!$stmt_remove) {
            echo "Prepare statement error: " . $conn->error;
            exit;
        }
        $stmt_remove->bind_param("i", $ba_id);
        if (!$stmt_remove->execute()) {
            echo "Execution error: " . $stmt_remove->error;
        } else {
            echo "success";
        }
    }
?>