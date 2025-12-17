<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require_once 'db_con.php';

$data = json_decode(file_get_contents('php://input'), true);
$step = $data['step'] ?? '';

function generateOTP() {
    return rand(100000, 999999);
}

function hashToken($email, $otp) {
    return hash('sha256', $email . $otp . 'your-secret-key');
}

if ($step === 'send_otp') {
    $email = $data['email'] ?? '';

    if (!$email) {
        echo json_encode(['success' => false, 'message' => 'Email is required']);
        exit;
    }

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Email not found']);
        exit;
    }

    $otp = generateOTP();
    $otp_token = hashToken($email, $otp);

    // Send email
    $mail = new PHPMailer(true);
    try {
        // SMTP config
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Use your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'tastelibmanangit@gmail.com'; // Your email
        $mail->Password = 'jurz haki zrvm jjrk'; // App password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('tastelibmanangit@gmail.com', 'TasteLibmanan');
        $mail->addAddress($email);
        $mail->Subject = 'Account Recovery - OTP Code';
        $mail->Body = '
        <div style="font-family: Arial, sans-serif; line-height: 1.5;">
            <h2 style="color: #2d3748;">Account Recovery</h2>
            <p>You requested to recover your account.</p>
            <p><strong>Your One-Time Password (OTP) is:</strong></p>
            <h1 style="color: #1a202c;">' . $otp . '</h1>
            <p>Please enter this code in the recovery form to proceed.</p>
            <p>If you did not make this request, you can safely ignore this email.</p>
            <br>
            <p style="color: #4a5568;">— TasteLibmanan Support</p>
        </div>
        ';
        $mail->AltBody = 'Account Recovery - Your OTP is: ' . $otp . '. Enter this code to recover your account.';
                
       // Send the email
        $mail->send();
        echo json_encode(['success' => true, 'otp_token' => $otp_token]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
    }

} elseif ($step === 'verify_otp') {
    $email = $data['email'] ?? '';
    $otp = $data['otp'] ?? '';
    $otp_token = $data['otp_token'] ?? '';

    if (hashToken($email, $otp) === $otp_token) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid OTP']);
    }

} elseif ($step === 'change_password') {
    $email = $data['email'] ?? '';
    $new_password = $data['new_password'] ?? '';
    $otp_token = $data['otp_token'] ?? '';

    if (!$email || !$new_password || !$otp_token) {
        echo json_encode(['success' => false, 'message' => 'Missing fields']);
        exit;
    }

    // Optional: re-check token validity if you stored OTPs somewhere

    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE accounts SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashedPassword, $email);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
