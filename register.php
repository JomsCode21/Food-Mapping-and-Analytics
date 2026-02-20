<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
require_once 'db_con.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';


// Decode request data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received.']);
    exit;
}

// Validate inputs
if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
    exit;
}

// Sanitize data
$name = htmlspecialchars($data['name']);
$email = strtolower(filter_var($data['email'], FILTER_SANITIZE_EMAIL));
$phone_number = htmlspecialchars($data['phone_number'] ?? '');
$password = $data['password'];
$user_type = htmlspecialchars($data['user_type']);

// Check for existing email
$checkStmt = $conn->prepare("SELECT user_id FROM accounts WHERE email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'This email has already been used. Please try a new one.']);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Generate verification code
$verification_code = bin2hex(random_bytes(16));

try {
    // Insert user into DB (unverified)
    $stmt = $conn->prepare("INSERT INTO accounts (name, email, phone_number, password, user_type, verification_code, email_verified) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("ssssss", $name, $email, $phone_number, $hashed_password, $user_type, $verification_code);
    $stmt->execute();

    // Send verification email
    $mail = new PHPMailer(true);
    try {
        $smtp_host = env_value('SMTP_HOST', 'smtp.gmail.com');
        $smtp_port = (int) env_value('SMTP_PORT', '587');
        $smtp_user = env_value('SMTP_USERNAME', '');
        $smtp_pass = env_value('SMTP_PASSWORD', '');
        $smtp_encryption = strtolower(env_value('SMTP_ENCRYPTION', 'tls'));
        $smtp_from_email = env_value('SMTP_FROM_EMAIL', $smtp_user);
        $smtp_from_name = env_value('SMTP_FROM_NAME', 'Tastelibmanan Admin');

        if ($smtp_user === '' || $smtp_pass === '' || $smtp_from_email === '') {
            throw new Exception('SMTP credentials are not configured.');
        }

        // Server settings
        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_user;
        $mail->Password = $smtp_pass;
        $mail->SMTPSecure = $smtp_encryption === 'ssl'
            ? PHPMailer::ENCRYPTION_SMTPS
            : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $smtp_port;

        // Recipients
        $mail->setFrom($smtp_from_email, $smtp_from_name);
        $mail->addAddress($email, $name);

        // Content
        $app_url = rtrim(env_value('APP_URL', 'https://tastelibmanan.systemproj.com'), '/');
        $verification_link = $app_url . "/verify_email.php?code=$verification_code";
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email - Libmanan Food';
        $mail->Body = "
            <h2>Welcome to Libmanan Food!</h2>
            <p>Hi <strong>$name</strong>,</p>
            <p>Thank you for registering. Please verify your email by clicking the link below:</p>
            <a href='$verification_link' style='display:inline-block;padding:10px 15px;background:#007bff;color:#fff;text-decoration:none;border-radius:5px;'>Verify Email</a>
            <p>If you didn't create this account, please ignore this email.</p>
        ";

        $mail->send();

        echo json_encode(['success' => true, 'message' => 'Registration successful! Verification email sent.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'User created but email failed: ' . $mail->ErrorInfo]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $conn->error]);
}

$conn->close();
?>
