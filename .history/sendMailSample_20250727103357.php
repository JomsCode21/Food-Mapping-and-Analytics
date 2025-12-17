<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();                                    // Use SMTP
    $mail->Host       = 'smtp.gmail.com';               // Set SMTP server
    $mail->SMTPAuth   = true;                           // Enable SMTP auth
    $mail->Username   = 'romandelavegamgg@gmail.com';         // Your Gmail address
    $mail->Password   = 'jtlj fcga bhuq vwea';            // Your Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS encryption
    $mail->Port       = 587;                            // TCP port for TLS

    // Recipients
    $mail->setFrom('romandelavegamgg@gmail.com', 'TasteLibmanan');
    $mail->addAddress('alexedrian00@gmail.com', 'Recipient Name');
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
