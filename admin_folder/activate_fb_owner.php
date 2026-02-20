<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';

require_once '../db_con.php';

if (isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $stmt_fb_owner = $conn->prepare("UPDATE fb_owner SET activation = 'Active' WHERE user_id = ?");
    if (!$stmt_fb_owner) {
        echo "Prepared Statement Error: " . $conn->error;
    } else {
        $stmt_fb_owner->bind_param("i", $user_id);
        if ($stmt_fb_owner->execute()) {

            // Getting the email and name to send notification
            $email = null;
            $name = null;
            $stmt_notif = $conn->prepare("SELECT name, email FROM accounts WHERE user_id = ?");
            if (!$stmt_notif) {
                echo "Preperation of Statement Error: " . $conn->error;
            } else {
                $stmt_notif->bind_param("i", $user_id);
                if (!$stmt_notif->execute()) {
                    echo "Execution Error: " . $conn->error;
                } else {
                    $result_notif = $stmt_notif->get_result();
                    if($row = $result_notif->fetch_assoc()) {
                        $email = $row['email'];
                        $name = $row['name'];

                        //Sending email notification.
                        $mail = new PHPMailer(true);
                        try {
                            $smtp_host = env_value('SMTP_HOST', 'smtp.gmail.com');
                            $smtp_port = (int) env_value('SMTP_PORT', '587');
                            $smtp_user = env_value('SMTP_USERNAME', '');
                            $smtp_pass = env_value('SMTP_PASSWORD', '');
                            $smtp_encryption = strtolower(env_value('SMTP_ENCRYPTION', 'tls'));
                            $smtp_from_email = env_value('SMTP_FROM_EMAIL', $smtp_user);
                            $smtp_from_name = env_value('SMTP_FROM_NAME', 'Tastelibmanan Admin');
                            $app_url = rtrim(env_value('APP_URL', 'http://localhost/tastelibmanan/tastelibmanan'), '/');

                            if ($smtp_user === '' || $smtp_pass === '' || $smtp_from_email === '') {
                                throw new Exception('SMTP credentials are not configured.');
                            }

                            // Server Settings
                            $mail->isSMTP();
                            $mail->Host = $smtp_host;
                            $mail->SMTPAuth = true;
                            $mail->Username = $smtp_user;
                            $mail->Password = $smtp_pass;
                            $mail->SMTPSecure = $smtp_encryption === 'ssl'
                                ? PHPMailer::ENCRYPTION_SMTPS
                                : PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = $smtp_port;

                            //Receipients
                            $mail->setFrom($smtp_from_email, $smtp_from_name);
                            $mail->addAddress($email, $name);
                            $mail->isHTML(true);
                            $mail->Subject = 'Your Privilage as a Food Business Owner';
                            $mail->Body = '
                                <div style="font-family: Arial, sans-serif; background: #f9fafb; padding: 0; margin: 0;">
                                    <div style="max-width: 420px; margin: 24px auto; background: #fff; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 24px;">
                                        <p style="font-size: 1.1rem; color: #374151; margin-bottom: 8px;">Dear <strong>' . htmlspecialchars($name) . '</strong>,</p>
                                        <p style="font-size: 1rem; color: #374151; margin-bottom: 20px;">
                                            Congratulations! 🎉 Your business registration with the Business Permits and Licensing Office (BPLO) - Libmanan is now <span style="color: #22c55e; font-weight: bold;">CONFIRMED.</span>
                                            <br>
                                            You may now log in using your BPLO system account to: <br>
                                            • Monitor and manage your business information. <br>
                                            •Use system features designed to help you in the growth of your business. <br>
                                            • Explore marketing opportunities and showcase your business so that more customers can discover it. <br><br>
                                            We are excited to be part of your journey and we look forward to supporting your success.
                                        </p>
                                        <div style="text-align:center; margin-bottom: 24px;">
                                            <p style="font-size: 1rem; color: #374151; margin-bottom: 20px;">
                                                Click the button below to fully access your account:
                                            </p>
                                            <a href="' . htmlspecialchars($app_url, ENT_QUOTES, 'UTF-8') . '/index.php" style="display:inline-block; background: #2563eb; color: #fff; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 1rem; box-shadow: 0 1px 4px rgba(37,99,235,0.12);">TasteLibmanan</a>
                                        </div>
                                        <p style="font-size: 0.95rem; color: #6b7280; text-align:left;">Sincerly,<br><span style="color: #2563eb;">BPLO - Libmanan</span></p>
                                    </div>
                                </div>
                            ';
                            $mail->AltBody = "Congratulations $name! Your account has been verified and updated to fb_owner status. You can now log in.";
                            $mail->send();
                        } catch (Exception $e) {
                            error_log("Email could not be sent, Mailer Error: {$mail->ErrorInfo}");
                        }
                    }
                    echo "success";
                }
            }
        } else {
            echo "Execution Error: " . $conn->error;
        }
    }
}
?>