<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';

require_once '../db_con.php';

    if (isset($_POST['user_id'], $_POST['ba_id'])) {
        $user_id = intval($_POST['user_id']);
        $ba_id = intval($_POST['ba_id']);
        $stmt_fbowner = $conn->prepare("UPDATE accounts SET user_type = 'fb_owner' WHERE user_id = ?");
        if (!$stmt_fbowner) {
            echo "prepare_error: " . $conn->error;
            exit;
        } else {
            $stmt_fbowner->bind_param("i", $user_id);
            if ($stmt_fbowner->execute()) {
                
                // Getting owner's email and sending notification
                $email = '';
                $name = '';
                $stmt_email = $conn->prepare("SELECT name, email FROM accounts WHERE user_id = ?");
                if ($stmt_email) {
                    $stmt_email->bind_param("i", $user_id);
                    $stmt_email->execute();
                    $result = $stmt_email->get_result();
                    if ($row = $result->fetch_assoc()) {
                        $email = $row['email'];
                        $name = $row['name'];

                        // Send email notification
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
                            $mail->addAddress($email, $name);
                            $mail->isHTML(true);
                            $mail->Subject = 'Your Business is Now Confirmed and Ready to Grow - BPLO Libmanan';
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
                                            <a href="http://localhost/tastelibmanan/tastelibmanan/index.php" style="display:inline-block; background: #2563eb; color: #fff; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 1rem; box-shadow: 0 1px 4px rgba(37,99,235,0.12);">TasteLibmanan</a>
                                        </div>
                                        <p style="font-size: 0.95rem; color: #6b7280; text-align:left;">Sincerly,<br><span style="color: #2563eb;">BPLO - Libmanan</span></p>
                                    </div>
                                </div>
                            ';
                            $mail->AltBody = "Congratulations $name! Your account has been verified and updated to fb_owner status. You can now log in.";
                            $mail->send();
                        } catch (Exception $e) {
                            // Log email sending error but do not fail the main operation
                            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
                        }
                    }
                    $stmt_email->close();
                }
                
                echo "success";

                // INSERTING THE DATA OF THE APPROVED BUSINESS TO THE fb_owner TABLE
                        // GETTING SOME INFORMATION FROM THE business_details TABLE
                        
                        // FETCHING BUSINESS DETAILS
                        $stmt_business_details = $conn->prepare("SELECT business_name, business_address, mobile_no, email_address FROM business_details WHERE ba_id = ?");
                        if (!$stmt_business_details) {
                            echo "Prepare error: " . $conn->error;
                            exit;
                        } else {
                            $stmt_business_details->bind_param("i", $ba_id);
                            if (!$stmt_business_details->execute()) {
                                echo "Execute Error: " . $conn->error;
                                exit;
                            } else {
                                $result_business_details = $stmt_business_details->get_result();
                                if ($row_business = $result_business_details->fetch_assoc()) {
                                    $business_name = $row_business['business_name'];
                                    $business_address = $row_business['business_address'];
                                    $business_contact_number = $row_business['mobile_no'];
                                    $business_email_address = $row_business['email_address'];

                                    // SINCE THE NEEDED DATA IS FETCHED, NOW INSERTING IT TO THE fb_owner TABLE
                                    $stmt_insert_fbowner = $conn->prepare("INSERT INTO `fb_owner`(`user_id`, `fb_name`, `fb_phone_number`, `fb_email_address`, `fb_address`,`created_at`) VALUES (?, ?, ?, ?, ?, CURDATE()) ");
                                    if (!$stmt_insert_fbowner) {
                                        echo "Prepare Error: " . $conn->error;
                                        exit;
                                    } else {
                                        $stmt_insert_fbowner->bind_param("issss", $user_id, $business_name, $business_contact_number, $business_email_address, $business_address);
                                        if (!$stmt_insert_fbowner->execute()) {
                                            echo "Execute Error: " . $conn-> error;
                                            exit;
                                        } else {
                                            // Successfully inserted into fb_owner
                                        }
                                    }

                                } else {
                                    echo "No business details found for ba_id: " . $conn->error;
                                    exit;
                                }
                            }
                        }

            } else {
                echo "error: " . $stmt_fbowner->error;
            }
        }
        $stmt_fbowner->close();
    } else {
        echo "invalid";
    }
?>