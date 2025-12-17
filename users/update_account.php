<?php
session_start();
include '../db_con.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // ✅ For users: update email (and password if given)
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'fb_owner') {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE accounts SET email='$email', password='$hashedPassword' WHERE user_id='$user_id'";
        } else {
            $sql = "UPDATE accounts SET email='$email' WHERE user_id='$user_id'";
        }

        if (mysqli_query($conn, $sql)) {
            $_SESSION['email'] = $email;
            header("Location: user.php?status=success");
            exit();
        } else {
            echo "Error updating account: " . mysqli_error($conn);
        }
    }

    // ✅ For fb_owners: update their fb_owner email too
    elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'fb_owner') {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE accounts SET password='$hashedPassword' WHERE user_id='$user_id'";
            mysqli_query($conn, $sql);
        }

        $fb_email_update = "UPDATE fb_owner SET fb_email_address='$email' WHERE user_id='$user_id'";
        if (mysqli_query($conn, $fb_email_update)) {
            $_SESSION['fb_email_address'] = $email;
            header("Location: user.php?status=success");
            exit();
        } else {
            echo "Error updating fb_owner email: " . mysqli_error($conn);
        }
    }
}
?>
