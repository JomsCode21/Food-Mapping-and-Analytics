<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once '../db_con.php';

// INITIALIZING GLOBAL VARIABLES
$ba_id = '';
$user_id = '';
$business_name = '';
$business_address = '';
$business_contact_number = '';
$business_email_address = '';

    if (isset($_POST['ba_id'])) {
        $ba_id = intval($_POST['ba_id']);
        $stmt_approved = $conn->prepare("UPDATE business_application SET status = 'approved' WHERE ba_id = ?");
        if (!$stmt_approved) {
            echo "prepare_error: " . $conn->error;
            exit;
        }
        $stmt_approved->bind_param("i", $ba_id);
        if ($stmt_approved->execute()) {

            // Getting the user's ID
            $user_id = '';
            $stmt_user = $conn->prepare("SELECT user_id FROM business_application WHERE ba_id = ?");
            if (!$stmt_user) {
                echo "prepare_error: " . $conn->error;
                exit;
            } else {
                $stmt_user->bind_param("i", $ba_id);
                if ($stmt_user->execute()) {
                    $result_user_id = $stmt_user->get_result();
                    if ($row = $result_user_id->fetch_assoc()) {
                        $user_id = $row['user_id'];

                        // Getting the user's email using the user_id from business_application
                        $user_email = '';
                        $user_name = '';
                        $stmt_email = $conn->prepare(("SELECT name, email FROM accounts WHERE user_id = ?"));
                        if (!$stmt_email) {
                            echo "Prepare_error: " . $conn->error;
                            exit;
                        } else {
                            $stmt_email->bind_param("i", $user_id);
                            if (!$stmt_email->execute()) {
                                echo "Execute error: " . $conn->error;
                                exit;
                            } else {
                                $result_email = $stmt_email->get_result();
                                if ($row_email = $result_email->fetch_assoc()) {
                                    $user_email = $row_email['email'];
                                    $user_name = $row_email['name'];

                                    // Sending email notification
                                    if (!empty($user_email) && !empty($user_name)) {
                                        $mail = new PHPMailer(true);
                                        try {
                                            // Server Settings
                                            $mail->isSMTP();
                                            $mail->Host = 'smtp.gmail.com';
                                            $mail->SMTPAuth = true;
                                            $mail->Username = 'tastelibmanangit@gmail.com';
                                            $mail->Password = 'jurz haki zrvm jjrk';
                                            $mail->SMTPSecure = 'tls';
                                            $mail->Port = 587;

                                            // Recipients
                                            $mail->setFrom('tastelibmanangit@gmail.com', 'Tastelibmanan Admin');
                                            $mail->addAddress($user_email, $user_name);
                                            $mail->isHTML(true);
                                            $mail->Subject = 'Congratulations! Your Business Permit Application is Approved - BPLO Libmanan';
                                            $mail->Body = '
                                                <div style="font-family: Arial, sans-serif; background: #f9fafb; padding: 0; margin: 0;">
                                                    <div style="max-width: 420px; margin: 24px auto; background: #fff; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 24px;">
                                                        <p style="font-size: 1.1rem; color: #374151; margin-bottom: 8px;">Dear <strong>' . htmlspecialchars($user_name) . '</strong>,</p>
                                                        <p style="font-size: 1rem; color: #374151; margin-bottom: 20px;">
                                                            We are pleased to inform you that your business permit application has been APPROVED by the Business Permits and Licensing Office (BPLO) - Libmanan.</p>
                                                        <p style="font-size: 1rem; color: #374151; margin-bottom: 20px;">
                                                            ✅ Next Steps: <br><br>
                                                            Since you have already submitted your application and documents, you may now proceed to the BPLO office to settle the required fees, assessments, and other related concerns. <br>
                                                            Once payment and verification are completed, your official business permit will be released.
                                                        </p>
                                                        <p style="font-size: 1rem; color: #374151; margin-bottom: 20px;">
                                                            Thank you for your cooperation and compliance with the requirements. We look forward to supporting you as you begin or continue your business operations in our municipality.
                                                        </p>
                                                        <p style="font-size: 0.95rem; color: #6b7280; text-align:left;">Sincerly,<br><span style="color: #2563eb;">BPLO - Libmanan</span></p>
                                                    </div>
                                                </div>
                                            ';
                                            $mail->AltBody = "We are pleased to inform you that your business permit application has been APPROVED by the Business Permits and Licensing Office (BPLO) - Libmanan.";
                                            $mail->send();
                                        } catch (Exception $e) {
                                            // Log email sending error but do not fail the main operation
                                            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
                                        }
                                    }
                                } else {
                                    echo "No email found for user_id: " . $conn->error;
                                    exit;
                                }
                            }
                        }

                        

                    }
                } else {
                    echo "execute_error: " . $stmt_user->error;
                    exit;
                }
            }

            echo "success";
        } else {
            echo "error: " . $stmt_approved->error;
        }
        $stmt_approved->close();
    } else {
        echo "invalid";
    }
?>