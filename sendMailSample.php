<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/env.php';

// Include PHPMailer classes
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    $smtp_host = env_value('SMTP_HOST', 'smtp.gmail.com');
    $smtp_port = (int) env_value('SMTP_PORT', '587');
    $smtp_user = env_value('SMTP_USERNAME', '');
    $smtp_pass = env_value('SMTP_PASSWORD', '');
    $smtp_encryption = strtolower(env_value('SMTP_ENCRYPTION', 'tls'));
    $smtp_from_email = env_value('SMTP_FROM_EMAIL', $smtp_user);
    $smtp_from_name = env_value('SMTP_FROM_NAME', 'TasteLibmanan');
    $smtp_to_email = env_value('SMTP_TO_EMAIL', '');
    $smtp_to_name = env_value('SMTP_TO_NAME', 'Recipient Name');

    if ($smtp_user === '' || $smtp_pass === '' || $smtp_from_email === '' || $smtp_to_email === '') {
        throw new Exception('SMTP settings are not configured.');
    }

    // Server settings
    $mail->isSMTP();                                    // Use SMTP
    $mail->Host       = $smtp_host;                     // Set SMTP server
    $mail->SMTPAuth   = true;                           // Enable SMTP auth
    $mail->Username   = $smtp_user;                     // SMTP username
    $mail->Password   = $smtp_pass;                     // SMTP password
    $mail->SMTPSecure = $smtp_encryption === 'ssl'
        ? PHPMailer::ENCRYPTION_SMTPS
        : PHPMailer::ENCRYPTION_STARTTLS;               // TLS/SSL encryption
    $mail->Port       = $smtp_port;                     // TCP port

    // Recipients
    $mail->setFrom($smtp_from_email, $smtp_from_name);
    $mail->addAddress($smtp_to_email, $smtp_to_name);
    $otp = rand(100000, 999999); // Make sure OTP is set
    $mail->isHTML(true); // Email format is HTML
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
    echo 'Message has been sent successfully';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
