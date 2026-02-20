<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once '../db_con.php';

    if (isset($_POST['ba_id'])) {
        $ba_id = intval($_POST['ba_id']);
        $stmt_reject = $conn->prepare("UPDATE business_application SET status = 'rejected' WHERE ba_id = ?");
        if (!$stmt_reject) {
            echo "prepare_error: " . $conn->error;
            exit;
        }
        $stmt_reject->bind_param("i", $ba_id);
        if ($stmt_reject->execute()) {

            // Getting user ID to get their credential
            $user_id = null;
            $stmt_getting_user_id = $conn->prepare("SELECT user_id FROM business_application WHERE ba_id = ?");
            if (!$stmt_getting_user_id) {
                echo "Prepare Error: " . $conn->error;
            } else {
                $stmt_getting_user_id->bind_param("i", $ba_id);
                if (!$stmt_getting_user_id->execute()) {
                    echo "Execution Error: " . $conn->error;
                } else {
                    $result_getting_user_id = $stmt_getting_user_id->get_result();
                    if ($row_user = $result_getting_user_id->fetch_assoc()) {
                        $user_id = $row_user['user_id'];
                    }
                }
            }

            // Getting the email address of the user to inform that their application has been rejected.
            $email = null;
            $name = null;
            $stmt_get_credential = $conn->prepare("SELECT name, email FROM accounts WHERE user_id = ?");
            if (!$stmt_get_credential) {
                echo "Prepare Statement Error: " . $conn->error;
            } else {
                $stmt_get_credential->bind_param("i", $user_id);
                if (!$stmt_get_credential->execute()) {
                    echo "Execution Error: " . $conn->error;
                } else {
                    $result_get_credential = $stmt_get_credential->get_result();
                    if ($row_credential = $result_get_credential->fetch_assoc()) {
                        $name = $row_credential['name'];
                        $email = $row_credential['email'];

                        //Sending email notification
                        if (empty($name) && empty($email)) {
                            echo "Empty: " . $conn->error;
                        } else {
                            $mail = new PHPMailer(true);
                            try{
                                $smtp_host = env_value('SMTP_HOST', 'smtp.gmail.com');
                                $smtp_port = (int) env_value('SMTP_PORT', '587');
                                $smtp_user = env_value('SMTP_USERNAME', '');
                                $smtp_pass = env_value('SMTP_PASSWORD', '');
                                $smtp_encryption = strtolower(env_value('SMTP_ENCRYPTION', 'tls'));
                                $smtp_from_email = env_value('SMTP_FROM_EMAIL', $smtp_user);
                                $smtp_from_name = env_value('SMTP_FROM_NAME', 'TasteLibmanan Admin');

                                if ($smtp_user === '' || $smtp_pass === '' || $smtp_from_email === '') {
                                    throw new Exception('SMTP credentials are not configured.');
                                }

                                // Server Configuration
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
                                $mail->Subject = 'Business Permit Application Status - Rejected';
                                $mail->Body = '
                                    <div style="font-family: Arial, sans-serif; background: #f9fafb; padding: 0; margin: 0;">
                                        <div style="max-width: 420px; margin: 24px auto; background: #fff; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 24px;">
                                            <p style="font-size: 1.1rem; color: #374151; margin-bottom: 8px;">Dear <strong>' . htmlspecialchars($name) . '</strong>,</p>
                                            <p style="font-size: 1rem; color: #374151; margin-bottom: 20px;">
                                                We regret to inform you that your business permit application with the Business Permits and Licensing Office (BPLO) - Libmanan has been REJECTED due to incomplete or wrong requirements and other related concerns.
                                            </p>
                                            <p style="font-size: 1rem; color: #374151; margin-bottom: 20px;">
                                                📌 Next Steps: <br><br>
                                                 You may re-apply once the issues have been addressed.
                                            </p>
                                            <p style="font-size: 1rem; color: #374151; margin-bottom: 20px;">
                                                For questions, clarifications, or guidance regarding the rejection, you may proceed to the BPLO office during office hours, where our staff will assist you.
                                            </p>
                                            <p style="font-size: 1rem; color: #374151; margin-bottom: 20px;">
                                                We encourage you to complete the necessary corrections so we can help you successfully register your business.
                                            </p>
                                            <p style="font-size: 0.95rem; color: #6b7280; text-align:left;">Best Regards,<br><span style="color: #2563eb;">BPLO - Libmanan</span></p>
                                        </div>
                                    </div>';
                                    $mail->AltBody = "We regret to inform you that your business permit application with the Business Permits and Licensing Office (BPLO) - Libmanan has been REJECTED due to incomplete or wrong requirements and other related concerns.";
                                $mail->send();
                            } catch (Exception $e) {
                                // Log email sending error but do not fail the main operation
                                error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
                            }
                        }
                    }
                }
            }


            echo "success";
        } else {
            echo "error: " . $stmt_reject->error;
        }
        $stmt_reject->close();
    } else {
        echo "invalid";
    }
?>