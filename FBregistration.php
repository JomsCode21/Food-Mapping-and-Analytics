<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require  'PHPMailer/src/Exception.php';
require  'PHPMailer/src/PHPMailer.php';
require  'PHPMailer/src/SMTP.php';

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0; 
require_once 'db_con.php';

// Initialize SweetAlert variables
$swal_trigger = false;
$swal_title = "";
$swal_text = "";
$swal_icon = "";
$swal_redirect = "";

// Business Application ID
$ba_id = null;

if (!$user_id) {
    echo "<script>alert('Please log in to access this page.'); window.location.href = 'index.php';</script>";
    exit();
}

  if (isset($_POST['submit_application'])) {
    $fileFields = [
        'barangay_clearance_owner',
        'barangay_business_clearance',
        'police_clearance',
        'cedula',
        'lease_contract',
        'business_permit_form',
        'dti_registration',
        'sec_registration',
        'rhu_permit',
        'meo_clearance',
        'mpdc_clearance',
        'menro_clearance',
        'bfp_certificate',
        'applicantSignature'
    ];
    $filePaths = [];
    $businessName = isset($_POST['businessName']) ? trim($_POST['businessName']) : 'unknown_business';
    $safeBusinessName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $businessName);
    $currentDate = date('Ymd');
    $folderName = $safeBusinessName . '_' . $currentDate;

    $uploadBase = __DIR__ . '/uploads/requirements/new/' . $folderName . '/';

    foreach ($fileFields as $field) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === 0) {
            $extension = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
            $uniqueName = uniqid($field . '_', true) . '.' . $extension;
            $targetDir = $uploadBase . $field . '/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $targetFile = $targetDir . $uniqueName;
            if (move_uploaded_file($_FILES[$field]['tmp_name'], $targetFile)) {
                $filePaths[$field] = 'uploads/requirements/new/' . $folderName . '/' . $field . '/' . $uniqueName;
            } else {
                $filePaths[$field] = '';
            }
        } else {
            $filePaths[$field] = '';
        }
    }
    
    $paymentMode = $_POST['paymentMode'] ?? '';
    $applicationType = isset($_POST['applicationType']) ? $_POST['applicationType'] : '';

    $applicationDate = $_POST['applicationDate'] ?? '';
      if (empty($applicationDate)) {
          $applicationDate = date('Y-m-d');
      }  
    $tinNo = $_POST['tinNo'] ?? '';
    $dtiRegNo = $_POST['dtiRegNo'] ?? '';
    $dtiRegDate = $_POST['dtiRegDate'] ?? '';
      if (empty($dtiRegDate)) {
        $dtiRegDate = date('Y-m-d');
      }
    $businessType = $_POST['businessType'] ?? '';
    $taxIncentive = isset($_POST['taxIncentive']) ? $_POST['taxIncentive'] : '';
    $taxEntity = $_POST['taxEntity'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $firstName = $_POST['firstName'] ?? '';
    $middleName = $_POST['middleName'] ?? '';
    $ownername = $_POST['ownername'] ?? '';
    $businessName = $_POST['businessName'] ?? '';
    $tradeName = $_POST['tradeName'] ?? '';
    $businessZone = $_POST['businessZone'] ?? '';
    $businessBarangay = $_POST['businessBarangay'] ?? '';
    $municipality = $_POST['municipality'] ?? '';
    $businessAddress = $businessZone . ', ' . $businessBarangay . ', ' . $municipality;
    $postalCode = $_POST['postalCode'] ?? '';
    $telephoneNo = $_POST['telephoneNo'] ?? '';
    $emailAddress = $_POST['emailAddress'] ?? '';
    $mobileNo = $_POST['mobileNo'] ?? '';
    $ownerAddress = $_POST['ownerAddress'] ?? '';
    $ownerPostalCode = $_POST['ownerPostalCode'] ?? '';
    $ownerTelephoneNo = $_POST['ownerTelephoneNo'] ?? '';
    $ownerEmailAddress = $_POST['ownerEmailAddress'] ?? '';
    $ownerMobileNo = $_POST['ownerMobileNo'] ?? '';
    $emergencyContactName = $_POST['emergencyContactName'] ?? '';
    $emergencyContactPhone = $_POST['emergencyContactPhone'] ?? '';
    $emergencyContactEmail = $_POST['emergencyContactEmail'] ?? '';
    $businessArea = $_POST['businessArea'] ?? '';
    $totalEmployees = $_POST['totalEmployees'] ?? '';
    $maleEmployees = $_POST['maleEmployees'] ?? '';
    $femaleEmployees = $_POST['femaleEmployees'] ?? '';
    $lguEmployees = $_POST['lguEmployees'] ?? '';
    $lessorFullName = $_POST['lessorFullName'] ?? '';
    $lessorAddress = $_POST['lessorAddress'] ?? '';
    $lessorPhone = $_POST['lessorPhone'] ?? '';
    $lessorEmail = $_POST['lessorEmail'] ?? '';
    $monthlyRental = $_POST['monthlyRental'] ?? '';
    $lineOfBusiness = $_POST['lineOfBusiness'] ?? '';
    $noOfUnits = $_POST['noOfUnits'] ?? '';
    $capitalization = $_POST['capitalization'] ?? '';
    $positionTitle = $_POST['positionTitle'] ?? '';
    $status = 'pending'; 
    
    try {
      $conn->begin_transaction();

      $stmt_business_application = $conn->prepare("INSERT INTO business_application (application_type, application_date, payment_mode, status, user_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
      $stmt_business_application->bind_param("ssssi", $applicationType, $applicationDate, $paymentMode, $status, $user_id);
      $stmt_business_application->execute();

      $ba_id = $conn->insert_id;
      if ($ba_id <= 0) {
          throw new Exception("Failed to retrieve last insert ID for business_application.");
      }

      $stmt_business_details = $conn->prepare("INSERT INTO business_details (`ba_id`,`tin_no`,`dti_reg_no`,`dti_reg_date`,`business_type`,`tax_incentive`,`tax_entity`,`business_name`,`trade_name`,`business_address`,`postal_code`,`telephone_no`,`email_address`,`mobile_no`,`business_area`,`total_employee`,`male_employee`,`female_employee`,`lgu_employee`,`line_of_business`,`no_of_units`,`capitalization`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt_business_details->bind_param("isssssssssissssiiiisii", $ba_id, $tinNo, $dtiRegNo, $dtiRegDate, $businessType, $taxIncentive, $taxEntity, $businessName, $tradeName, $businessAddress, $postalCode, $telephoneNo, $emailAddress, $mobileNo, $businessArea, $totalEmployees, $maleEmployees, $femaleEmployees, $lguEmployees, $lineOfBusiness, $noOfUnits, $capitalization);
      $stmt_business_details->execute();

      $stmt_emergency_contact = $conn->prepare("INSERT INTO `emergency_contact`(`ba_id`, `contact_name`, `contact_phone`, `contact_email`) VALUES (?, ?, ?, ?)");
      $stmt_emergency_contact->bind_param("isss", $ba_id, $emergencyContactName, $emergencyContactPhone, $emergencyContactEmail);
      $stmt_emergency_contact->execute();

      $stmt_lessor_details = $conn->prepare("INSERT INTO `lessor_details`(`ba_id`, `lessor_fullname`, `lessor_fulladdress`, `lessor_mobile_no`, `lessor_email_address`, `montly_rental`) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt_lessor_details->bind_param("issssi", $ba_id, $lessorFullName, $lessorAddress, $lessorPhone, $lessorEmail, $monthlyRental);
      $stmt_lessor_details->execute();

      $stmt_owner_details = $conn->prepare("INSERT INTO `owner_details`(`ba_id`,`owner_name`,`owner_address`, `owner_postal_code`, `owner_telephone_no`, `owner_email_address`, `owner_mobile_no`, `position_title`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt_owner_details->bind_param("ississss", $ba_id, $ownername, $ownerAddress, $ownerPostalCode, $ownerTelephoneNo, $ownerEmailAddress, $ownerMobileNo, $positionTitle);
      $stmt_owner_details->execute();

      $stmt_taxpayer_details = $conn->prepare("INSERT INTO `taxpayer_details`(`ba_id`, `last_name`, `first_name`, `middle_name`) VALUES (?, ?, ?, ?)");
      $stmt_taxpayer_details->bind_param("isss", $ba_id, $lastName, $firstName, $middleName);
      $stmt_taxpayer_details->execute();

      $stmt_business_documents = $conn->prepare("INSERT INTO `application_documents`(`ba_id`, `barangay_clearance_owner`, `barangay_business_clearance`, `police_clearance`, `cedula`, `lease_contract`, `business_permit_form`, `dti_registration`, `sec_registration`, `rhu_permit`, `meo_clearance`, `mpdc_clearance`, `menro_clearance`, `bfp_certificate`, `applicantSignature`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

      if (!$stmt_business_documents) {
        die ("Prepare failed: " . $conn->error);
      }

      $stmt_business_documents->bind_param("issssssssssssss", $ba_id, 
          $filePaths['barangay_clearance_owner'], 
          $filePaths['barangay_business_clearance'], 
          $filePaths['police_clearance'], 
          $filePaths['cedula'], 
          $filePaths['lease_contract'], 
          $filePaths['business_permit_form'], 
          $filePaths['dti_registration'], 
          $filePaths['sec_registration'], 
          $filePaths['rhu_permit'], 
          $filePaths['meo_clearance'], 
          $filePaths['mpdc_clearance'], 
          $filePaths['menro_clearance'], 
          $filePaths['bfp_certificate'], 
          $filePaths['applicantSignature']
      );
      if (!$stmt_business_documents->execute()) {
        die ("Execute failed: " . $stmt_business_documents->error);
      }

      $conn->commit();

      if ($stmt_business_application -> affected_rows > 0) {
          
          // SET SWEETALERT SUCCESS
          $swal_trigger = true;
          $swal_title = "Application Submitted!";
          $swal_text = "Please wait for the approval. We will contact your EMAIL or PHONE NUMBER soon.";
          $swal_icon = "success";
          $swal_redirect = "index.php";
          
          $user_name = null;
          $user_email = null;

          $get_credential = $conn->prepare("SELECT name, email FROM accounts WHERE user_id = ?");
          if (!$get_credential) {
              throw new Exception("Prepare failed: " . $conn->error);
          } else {
            $get_credential->bind_param("i", $user_id);
            if (!$get_credential->execute()) {
                throw new Exception("Execute failed: " . $get_credential->error);
            } else {
                $result = $get_credential->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $user_name = $row['name'];
                    $user_email = $row['email'];

                    if (!empty($user_email) && !empty($user_name)) {
                        $mail = new PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'tastelibmanangit@gmail.com';
                            $mail->Password = 'jurz haki zrvm jjrk';
                            $mail->SMTPSecure = 'tls';
                            $mail->Port = 587;

                            $mail->setFrom('tastelibmanangit@gmail.com', 'Tastelibmanan Admin');
                            $mail->addAddress($user_email, $user_name);
                            $mail->isHTML(true);
                            $mail->Subject = 'Your Business Permit Application is Pending - BPLO Libmanan';
                            $mail->Body = '
                                <div style="font-family: Arial, sans-serif; background: #f9fafb; padding: 0; margin: 0;">
                                    <div style="max-width: 420px; margin: 24px auto; background: #fff; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 24px;">
                                        <p style="font-size: 1.1rem; color: #374151; margin-bottom: 8px;">Dear <strong>' . htmlspecialchars($user_name) . '</strong>,</p>
                                        <p style="font-size: 1rem; color: #374151; margin-bottom: 20px;"> 
                                          We would like to inform you that your application status is currently <strong> PENDING. </strong>This means that we are still reviewing your submitted requirements and verifying them with the concerned offices.
                                        </p>
                                        <p style="font-size: 0.95rem; color: #6b7280; text-align:left;">Best regards,<br><span style="color: #2563eb;">BPLO - Libmanan</span></p>
                                    </div>
                                </div>
                            ';
                            $mail->AltBody = 'Thank you for submitting your business permit application.';
                            $mail->send();

                        } catch (Exception $e) {
                           // Mailer error ignored
                        }

                        $message = "submit New Application.";
                        $type = "Registration";
                        $stmt_notif = $conn->prepare("INSERT INTO notification (user_id, type, ref_id, message, created_at) VALUES (?, ?, ?, ?, NOW())");
                        $stmt_notif->bind_param("isis", $user_id, $type, $ba_id, $message);
                        $stmt_notif->execute();
                      }
                }
            }
          }

      } else {
           $swal_trigger = true;
           $swal_title = "Failed";
           $swal_text = "Failed to submit application. Please try again.";
           $swal_icon = "error";
           $swal_redirect = "users/user.php";
      }
  
    } catch (Exception $e) {
      $conn->rollback();
      $swal_trigger = true;
      $swal_title = "Error";
      $swal_text = "Error: " . $e->getMessage();
      $swal_icon = "error";
      $swal_redirect = "";
    }
  }


  // ==========================================
  // LOGIC FOR RENEWAL APPLICATION
  // ==========================================
  $ba_id_renewal = null;

  if (isset($_POST['submit_application_renewal'])) {
    $fileFields_renewal = [
        'barangay_clearance_owner_renewal',
        'barangay_business_clearance_renewal',
        'cedula_renewal',
        'lease_contract_renewal',
        'business_permit_form_renewal',
        'sec_registration_renewal',
        'rhu_permit_renewal',
        'meo_clearance_renewal',
        'mpdc_clearance_renewal',
        'menro_clearance_renewal',
        'bfp_certificate_renewal',
        'applicantSignature_renewal'
    ];
    $filePaths_renewal = [];
    $businessName_renewal = isset($_POST['businessName_renewal']) ? trim($_POST['businessName_renewal']) : 'unknown_business';
    $safeBusinessName_renewal = preg_replace('/[^A-Za-z0-9_\-]/', '_', $businessName_renewal);
    $currentDate_renewal = date('Ymd');
    $folderlName_renewal = $safeBusinessName_renewal . '_' . $currentDate_renewal;
  
    $uploadBase_renewal = __DIR__ . '/uploads/requirements/renewal/' . $folderlName_renewal . '/';
  
    foreach ($fileFields_renewal as $field_renewal) {
        if (isset($_FILES[$field_renewal]) && $_FILES[$field_renewal]['error'] === 0) {
            $extension_renewal = pathinfo($_FILES[$field_renewal]['name'], PATHINFO_EXTENSION);
            $uniqueName_renewal = uniqid($field_renewal . '_', true) . '.' . $extension_renewal;
            $targetDir_renewal = $uploadBase_renewal . $field_renewal . '/';
            if (!is_dir($targetDir_renewal)) {
                mkdir($targetDir_renewal, 0777, true);
            }
            $targetFile_renewal = $targetDir_renewal . $uniqueName_renewal;
            if (move_uploaded_file($_FILES[$field_renewal]['tmp_name'], $targetFile_renewal)) {
                $filePaths_renewal[$field_renewal] = 'uploads/requirements/renewal/' . $folderlName_renewal . '/' . $field_renewal . '/' . $uniqueName_renewal;
            } else {
                $filePaths_renewal[$field_renewal] = '';
            }
        } else {
            $filePaths_renewal[$field_renewal] = '';
        }
    }

    $paymentMode_renewal = $_POST['paymentMode_renewal'] ?? '';
    $applicationType_renewal = isset($_POST['applicationType_renewal']) ? $_POST['applicationType_renewal'] : ''; 
  
    $applicationDate_renewal = $_POST['applicationDate_renewal'] ?? '';
      if (empty($applicationDate_renewal)) {
          $applicationDate_renewal = date('Y-m-d');
      }  
    $tinNo_renewal = $_POST['tinNo_renewal'] ?? '';
    $dtiRegNo_renewal = $_POST['dtiRegNo_renewal'] ?? '';
    $dtiRegDate_renewal = $_POST['dtiRegDate_renewal'] ?? '';
      if (empty($dtiRegDate_renewal)) {
        $dtiRegDate_renewal = date('Y-m-d');
      }
    $businessType_renewal = $_POST['businessType_renewal'] ?? '';
    $taxIncentive_renewal = isset($_POST['taxIncentive_renewal']) ? $_POST['taxIncentive_renewal'] : '';
    $taxEntity_renewal = $_POST['taxEntity_renewal'] ?? '';
    $lastName_renewal = $_POST['lastName_renewal'] ?? '';
    $firstName_renewal = $_POST['firstName_renewal'] ?? '';
    $middleName_renewal = $_POST['middleName_renewal'] ?? '';
    $ownername_renewal = $_POST['ownername_renewal'] ?? '';
    $businessName_renewal = $_POST['businessName_renewal'] ?? '';
    $tradeName_renewal = $_POST['tradeName_renewal'] ?? '';
    $businessZone_renewal = $_POST['businessZone_renewal'] ?? '';
    $businessBarangay_renewal = $_POST['businessBarangay_renewal'] ?? '';
    $municipality_renewal = $_POST['municipality_renewal'] ?? '';
    $businessAddress_renewal = $businessZone_renewal . ', ' . $businessBarangay_renewal . ', ' . $municipality_renewal;
    $postalCode_renewal = $_POST['postalCode_renewal'] ?? '';
    $telephoneNo_renewal = $_POST['telephoneNo_renewal'] ?? '';
    $emailAddress_renewal = $_POST['emailAddress_renewal'] ?? '';
    $mobileNo_renewal = $_POST['mobileNo_renewal'] ?? '';
    $ownerAddress_renewal = $_POST['ownerAddress_renewal'] ?? '';
    $ownerPostalCode_renewal = $_POST['ownerPostalCode_renewal'] ?? '';
    $ownerTelephoneNo_renewal = $_POST['ownerTelephoneNo_renewal'] ?? '';
    $ownerEmailAddress_renewal = $_POST['ownerEmailAddress_renewal'] ?? '';
    $ownerMobileNo_renewal = $_POST['ownerMobileNo_renewal'] ?? '';
    $emergencyContactName_renewal = $_POST['emergencyContactName_renewal'] ?? '';
    $emergencyContactPhone_renewal = $_POST['emergencyContactPhone_renewal'] ?? '';
    $emergencyContactEmail_renewal = $_POST['emergencyContactEmail_renewal'] ?? '';
    $businessArea_renewal = $_POST['businessArea_renewal'] ?? '';
    $totalEmployees_renewal = $_POST['totalEmployees_renewal'] ?? '';
    $maleEmployees_renewal = $_POST['maleEmployees_renewal'] ?? '';
    $femaleEmployees_renewal = $_POST['femaleEmployees_renewal'] ?? '';
    $lguEmployees_renewal = $_POST['lguEmployees_renewal'] ?? '';
    $lessorFullName_renewal = $_POST['lessorFullName_renewal'] ?? '';
    $lessorAddress_renewal = $_POST['lessorAddress_renewal'] ?? '';
    $lessorPhone_renewal = $_POST['lessorPhone_renewal'] ?? '';
    $lessorEmail_renewal = $_POST['lessorEmail_renewal'] ?? '';
    $monthlyRental_renewal = $_POST['monthlyRental_renewal'] ?? '';
    $lineOfBusiness_renewal = $_POST['lineOfBusiness_renewal'] ?? '';
    $noOfUnits_renewal = $_POST['noOfUnits_renewal'] ?? '';
    $capitalization_renewal = $_POST['capitalization_renewal'] ?? '';
    $positionTitle_renewal = $_POST['positionTitle_renewal'] ?? '';
    $status_renewal = 'pending'; 
    
    try {
      $conn->begin_transaction();
    
      $stmt_business_application_renewal = $conn->prepare("INSERT INTO business_application (application_type, application_date, payment_mode, status, user_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
      $stmt_business_application_renewal->bind_param("ssssi", $applicationType_renewal, $applicationDate_renewal, $paymentMode_renewal, $status_renewal, $user_id);
      if (!$stmt_business_application_renewal->execute()) {
        throw New Exception("Error saving Business Application: " . $stmt_business_application_renewal->error);
      }
    
      $ba_id_renewal = $conn->insert_id;
      if ($ba_id_renewal <= 0) {
          throw new Exception("Failed to retrieve last insert ID for business_application.");
      }

      $stmt_business_details_renewal = $conn->prepare("INSERT INTO business_details (`ba_id`,`tin_no`,`dti_reg_no`,`dti_reg_date`,`business_type`,`tax_incentive`,`tax_entity`,`business_name`,`trade_name`,`business_address`,`postal_code`,`telephone_no`,`email_address`,`mobile_no`,`business_area`,`total_employee`,`male_employee`,`female_employee`,`lgu_employee`,`line_of_business`,`no_of_units`,`capitalization`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt_business_details_renewal->bind_param("isssssssssissssiiiisii", $ba_id_renewal, $tinNo_renewal, $dtiRegNo_renewal, $dtiRegDate_renewal, $businessType_renewal, $taxIncentive_renewal, $taxEntity_renewal, $businessName_renewal, $tradeName_renewal, $businessAddress_renewal, $postalCode_renewal, $telephoneNo_renewal, $emailAddress_renewal, $mobileNo_renewal, $businessArea_renewal, $totalEmployees_renewal, $maleEmployees_renewal, $femaleEmployees_renewal, $lguEmployees_renewal, $lineOfBusiness_renewal, $noOfUnits_renewal, $capitalization_renewal);
      if (!$stmt_business_details_renewal->execute()) {
        throw New Exception("Error saving Business Details: " . $stmt_business_details_renewal->error);
      }
    
      $stmt_emergency_contact_renewal = $conn->prepare("INSERT INTO `emergency_contact`(`ba_id`, `contact_name`, `contact_phone`, `contact_email`) VALUES (?, ?, ?, ?)");
      $stmt_emergency_contact_renewal->bind_param("isss", $ba_id_renewal, $emergencyContactName_renewal, $emergencyContactPhone_renewal, $emergencyContactEmail_renewal);
      if (!$stmt_emergency_contact_renewal->execute()) {
        throw New Exception("Error saving Emergency Contact: " . $stmt_emergency_contact_renewal->error);
      }
    
      if (!empty($lessorFullName_renewal)) {
        $stmt_lessor_details_renewal = $conn->prepare("INSERT INTO `lessor_details`(`ba_id`, `lessor_fullname`, `lessor_fulladdress`, `lessor_mobile_no`, `lessor_email_address`, `montly_rental`) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_lessor_details_renewal->bind_param("issssi", $ba_id_renewal, $lessorFullName_renewal, $lessorAddress_renewal, $lessorPhone_renewal, $lessorEmail_renewal, $monthlyRental_renewal);
        if (!$stmt_lessor_details_renewal->execute()) {
          throw New Exception("Error saving Lessor Details: " . $stmt_lessor_details_renewal->error);
        }
      }
    
      $stmt_owner_details_renewal = $conn->prepare("INSERT INTO `owner_details`(`ba_id`, `owner_name`, `owner_address`, `owner_postal_code`, `owner_telephone_no`, `owner_email_address`, `owner_mobile_no`, `position_title`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt_owner_details_renewal->bind_param("ississss", $ba_id_renewal, $ownername_renewal, $ownerAddress_renewal, $ownerPostalCode_renewal, $ownerTelephoneNo_renewal, $ownerEmailAddress_renewal, $ownerMobileNo_renewal, $positionTitle_renewal);
      if (!$stmt_owner_details_renewal->execute()) {
        throw New Exception("Error saving Owner Details: " . $stmt_owner_details_renewal->error);
      }

      $stmt_taxpayer_details_renewal = $conn->prepare("INSERT INTO `taxpayer_details`(`ba_id`, `last_name`, `first_name`, `middle_name`) VALUES (?, ?, ?, ?)");
      $stmt_taxpayer_details_renewal->bind_param("isss", $ba_id_renewal, $lastName_renewal, $firstName_renewal, $middleName_renewal);
      if (!$stmt_taxpayer_details_renewal->execute()) {
        throw New Exception("Error saving Taxpayer Details: " . $stmt_taxpayer_details_renewal->error);
      }
    
      $stmt_business_documents_renewal = $conn->prepare("INSERT INTO `application_documents`(`ba_id`, `barangay_clearance_owner`, `barangay_business_clearance`, `cedula`, `lease_contract`, `business_permit_form`, `sec_registration`, `rhu_permit`, `meo_clearance`, `mpdc_clearance`, `menro_clearance`, `bfp_certificate`, `applicantSignature`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      
      if (!$stmt_business_documents_renewal) {
        die ("Prepare failed: " . $conn->error);
      }
    
      $stmt_business_documents_renewal->bind_param("issssssssssss", $ba_id_renewal, 
          $filePaths_renewal['barangay_clearance_owner_renewal'], 
          $filePaths_renewal['barangay_business_clearance_renewal'],
          $filePaths_renewal['cedula_renewal'], 
          $filePaths_renewal['lease_contract_renewal'], 
          $filePaths_renewal['business_permit_form_renewal'],  
          $filePaths_renewal['sec_registration_renewal'], 
          $filePaths_renewal['rhu_permit_renewal'], 
          $filePaths_renewal['meo_clearance_renewal'], 
          $filePaths_renewal['mpdc_clearance_renewal'], 
          $filePaths_renewal['menro_clearance_renewal'], 
          $filePaths_renewal['bfp_certificate_renewal'], 
          $filePaths_renewal['applicantSignature_renewal']
      );
      if (!$stmt_business_documents_renewal->execute()) {
        throw New Exception ("Error saving Business Documents: " . $stmt_business_documents_renewal->error);
      }
    
      $conn->commit();
    
        $user_name_renewal = null;
        $user_email_renewal = null;

        $get_credential_renewal = $conn->prepare("SELECT name, email FROM accounts WHERE user_id = ?");
        if ($get_credential_renewal) {
            $get_credential_renewal->bind_param("i", $user_id);
            if ($get_credential_renewal->execute()) {
                $result_renewal = $get_credential_renewal->get_result();
                if ($result_renewal->num_rows > 0) {
                    $row_renewal = $result_renewal->fetch_assoc();
                    $user_name_renewal = $row_renewal['name'];
                    $user_email_renewal = $row_renewal['email'];
                }
            }
        }

        if (!empty($user_email_renewal) && !empty($user_name_renewal)) {
            $mail_renewal = new PHPMailer(true);
            try {
                $mail_renewal->isSMTP();
                $mail_renewal->Host = 'smtp.gmail.com';
                $mail_renewal->SMTPAuth = true;
                $mail_renewal->Username = 'tastelibmanangit@gmail.com';
                $mail_renewal->Password = 'jurz haki zrvm jjrk';
                $mail_renewal->SMTPSecure = 'tls';
                $mail_renewal->Port = 587;
              
                $mail_renewal->setFrom('tastelibmanangit@gmail.com', 'Tastelibmanan Admin');
                $mail_renewal->addAddress($user_email_renewal, $user_name_renewal);
                $mail_renewal->isHTML(true);
                $mail_renewal->Subject = 'Your Business Permit Application is Pending - BPLO Libmanan';
                $mail_renewal->Body = '
                    <div style="font-family: Arial, sans-serif; background: #f9fafb; padding: 0; margin: 0;">
                        <div style="max-width: 420px; margin: 24px auto; background: #fff; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 24px;">
                            <p style="font-size: 1.1rem; color: #374151; margin-bottom: 8px;">Dear <strong>' . htmlspecialchars($user_name_renewal) . '</strong>,</p>
                            <p style="font-size: 1rem; color: #374151; margin-bottom: 20px;"> 
                              We would like to inform you that your application status is currently <strong> PENDING. </strong>This means that we are still reviewing your submitted requirements and verifying them with the concerned offices.
                            </p>
                            <p style="font-size: 0.95rem; color: #6b7280; text-align:left;">Best regards,<br><span style="color: #2563eb;">BPLO - Libmanan</span></p>
                        </div>
                    </div>
                ';
                $mail_renewal->AltBody = 'Your application is Pending.';
                $mail_renewal->send();
              
            } catch (Exception $e) {
                // Email fail ignored
            }
        }

        $message_renewal = "submit Renewal Application.";
        $type_renewal = "Renewal";

        $stmt_notif_renewal = $conn->prepare("INSERT INTO notification (user_id, type, ref_id, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt_notif_renewal->bind_param("isis", $user_id, $type_renewal, $ba_id_renewal, $message_renewal);
        $stmt_notif_renewal->execute();

        // SET SWEETALERT SUCCESS
        $swal_trigger = true;
        $swal_title = "Application Submitted!";
        $swal_text = "Please wait for the approval. We will contact your EMAIL or PHONE NUMBER soon.";
        $swal_icon = "success";
        $swal_redirect = "users/user.php";

    } catch (Exception $e) {
      $conn->rollback();
      $swal_trigger = true;
      $swal_title = "Error";
      $swal_text = "Error: " . $e->getMessage();
      $swal_icon = "error";
      $swal_redirect = "";
    }

  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Business Registration - TasteLibmanan</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: { 
                primary: "#A80000", 
                secondary: "#FF6B00",
                dark: "#1F2937",
                light: "#F3F4F6"
            },
            fontFamily: {
                brand: ['Pacifico', 'cursive'],
                sans: ['Inter', 'sans-serif'],
            },
            boxShadow: {
                'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.1)',
                'card': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
            }
          },
        },
      }
    </script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
    <style>
        /* Prevent auto-zoom on iOS inputs */
        @media screen and (max-width: 768px) {
            input, select, textarea {
                font-size: 16px !important; 
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center">
                    <a href="users/user.php" class="font-brand text-2xl md:text-3xl text-primary hover:text-red-700 transition">
                        Taste<span class="text-gray-800">Libmanan</span>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="users/user.php" class="text-gray-600 hover:text-primary font-medium text-sm transition">Home</a>
                    <a href="fbusinessowner/categories.php" class="text-gray-600 hover:text-primary font-medium text-sm transition">Businesses</a>
                    <a href="FBregistration.php" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-800 transition shadow-sm flex items-center">
                        <i class="ri-store-3-line mr-1"></i> Register Business
                    </a>
                    
                    <div class="relative group">
                         <button class="flex items-center space-x-2 text-gray-600 hover:text-primary transition focus:outline-none">
                            <span class="font-medium text-sm">Account</span>
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-card border border-gray-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right">
                            <a href="#" onclick="openAccountModal()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-primary"><i class="ri-settings-3-line mr-2"></i>Settings</a>
                            <a href="users/favorites.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-primary">
                                <i class="ri-heart-line mr-2"></i>My Favorites
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="ri-logout-box-r-line mr-2"></i>Logout</a>
                        </div>
                    </div>
                </div>

                <div class="flex md:hidden items-center">
                    <button id="hamburger-btn" class="text-gray-600 hover:text-primary focus:outline-none p-2">
                        <i class="ri-menu-3-line text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 absolute w-full shadow-lg">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="users/user.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">Home</a>
                <a href="fbusinessowner/categories.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">Businesses</a>     
                <a href="users/favorites.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">My Favorites</a>
                <a href="FBregistration.php" class="block px-3 py-3 rounded-md text-base font-medium text-white bg-primary">Register Business</a>  
                <a href="#" onclick="openAccountModal(); document.getElementById('mobile-menu').classList.add('hidden');" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">Account Settings</a>
                <a href="logout.php" class="block px-3 py-3 rounded-md text-base font-medium text-red-600 hover:bg-red-50">Logout</a>
            </div>
        </div>
    </nav>

    <div id="accountModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-[60] transition-opacity p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 md:p-8 relative transform scale-100 transition-transform">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl md:text-2xl font-bold text-gray-800">Account Settings</h2>
                <button onclick="closeAccountModal()" class="text-gray-400 hover:text-gray-600 transition p-2"><i class="ri-close-line text-2xl"></i></button>
            </div>
            <form id="accountForm" method="POST" action="users/update_account.php">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Email Address</label>
                    <div class="relative">
                        <i class="ri-mail-line absolute left-3 top-3.5 text-gray-400"></i>
                        <?php 
                            $display_email = isset($user_email) ? $user_email : ($_SESSION['email'] ?? '');
                        ?>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($display_email); ?>" class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition text-base md:text-sm" required>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">New Password</label>
                    <div class="relative">
                        <i class="ri-lock-line absolute left-3 top-3.5 text-gray-400"></i>
                        <input type="password" name="password" placeholder="••••••••" class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition text-base md:text-sm" minlength="8" maxlength="16">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password.</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAccountModal()" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary hover:bg-red-800 rounded-lg shadow-md transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <main class="flex-grow pt-8 md:pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        
        <section id="selectionSection" class="max-w-4xl mx-auto">
            <div class="text-center mb-6 md:mb-10">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Food Business <span class="text-primary">Registration</span></h2>
                <p class="mt-2 text-base md:text-lg text-gray-600">Select the type of application to proceed.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                <div class="bg-white rounded-2xl shadow-md p-6 md:p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100 group cursor-pointer" id="startNewApplication">
                    <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mb-6 group-hover:bg-primary transition-colors duration-300">
                        <i class="ri-file-add-line text-2xl text-primary group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 group-hover:text-primary transition-colors">New Application</h3>
                    <p class="text-gray-500 mb-6 text-sm md:text-base">Register a brand new food business and get listed on our platform.</p>
                    <button class="w-full bg-white border-2 border-primary text-primary font-semibold px-4 py-3 md:py-2 rounded-lg group-hover:bg-primary group-hover:text-white transition-all duration-300">
                        Start Application
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-md p-6 md:p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100 group cursor-pointer" id="startRenewalApplication">
                    <div class="w-14 h-14 bg-orange-50 rounded-full flex items-center justify-center mb-6 group-hover:bg-secondary transition-colors duration-300">
                        <i class="ri-refresh-line text-2xl text-secondary group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 group-hover:text-secondary transition-colors">Renewal Application</h3>
                    <p class="text-gray-500 mb-6 text-sm md:text-base">Renew your existing business registration to stay active.</p>
                    <button class="w-full bg-white border-2 border-secondary text-secondary font-semibold px-4 py-3 md:py-2 rounded-lg group-hover:bg-secondary group-hover:text-white transition-all duration-300">
                        Renew Application
                    </button>
                </div>
            </div>
        </section>

        <section id="newApplicationFormSection" class="application-form max-w-6xl mx-auto hidden">
            <button class="backToSelection mb-6 flex items-center text-gray-500 hover:text-primary transition-colors font-medium p-2 -ml-2">
                <i class="ri-arrow-left-line mr-2"></i> Back to Selection
            </button>

            <form method="POST" enctype="multipart/form-data" class="space-y-6 md:space-y-8">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-primary/5 px-4 md:px-6 py-4 border-b border-primary/10">
                        <h3 class="text-lg font-bold text-primary flex items-center">
                            <i class="ri-folder-upload-line mr-2"></i> Requirements
                        </h3>
                    </div>
                    <div class="p-4 md:p-6">
                        <p class="text-sm text-gray-500 mb-6 flex items-center"><i class="ri-information-line mr-1"></i> Upload clear images or PDF files.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <?php 
                            $reqs = [
                                'barangay_clearance_owner' => 'Brgy. Clearance (Owner)',
                                'barangay_business_clearance' => 'Brgy. Business Clearance',
                                'police_clearance' => 'Police Clearance',
                                'cedula' => 'Cedula',
                                'lease_contract' => 'Lease Contract (if rented)',
                                'business_permit_form' => 'Business Permit Form',
                                'dti_registration' => 'DTI / SEC Registration',
                                'sec_registration' => 'SEC Registration (Corps)',
                                'rhu_permit' => 'RHU / Sanitary Permit',
                                'meo_clearance' => 'MEO Clearance',
                                'mpdc_clearance' => 'MPDC Clearance',
                                'menro_clearance' => 'MENRO Clearance',
                                'bfp_certificate' => 'BFP Certificate',
                                'applicantSignature' => 'Signature of Applicant'
                            ];
                            foreach ($reqs as $name => $label): 
                            ?>
                            <div class="relative group">
                                <label class="block text-xs font-semibold text-gray-700 mb-1 ml-1"><?php echo $label; ?></label>
                                <div class="file-upload-card rounded-lg p-3 flex items-center justify-between bg-gray-50 cursor-pointer relative overflow-hidden border border-gray-200 hover:border-primary transition-colors h-14">
                                    <input type="file" name="<?php echo $name; ?>" accept="image/*,.pdf" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" onchange="updateFileName(this)">
                                    <div class="flex items-center space-x-3 w-full">
                                        <div class="bg-white p-2 rounded shadow-sm">
                                            <i class="ri-upload-cloud-2-line text-primary"></i>
                                        </div>
                                        <span class="text-xs text-gray-500 truncate file-name-display w-full">Choose file...</span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 md:px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="ri-file-list-3-line mr-2 text-secondary"></i> Application Details
                        </h3>
                    </div>
                    <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Application Type</label>
                            <div class="flex items-center space-x-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <input type="checkbox" name="applicationType" value="New" checked class="w-4 h-4 text-primary rounded focus:ring-primary border-gray-300">
                                <span class="text-sm font-medium">New Application</span>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Date</label>
                            <input type="date" name="applicationDate" class="w-full px-4 py-3 md:py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors text-base md:text-sm" value="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Mode of Payment</label>
                            <div class="flex flex-wrap gap-4 mt-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="paymentMode" value="Annually" class="text-primary focus:ring-primary h-5 w-5" checked>
                                    <span class="ml-2 text-sm">Annually</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="paymentMode" value="Semi-Annually" class="text-primary focus:ring-primary h-5 w-5">
                                    <span class="ml-2 text-sm">Semi-Annually</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="paymentMode" value="Quarterly" class="text-primary focus:ring-primary h-5 w-5">
                                    <span class="ml-2 text-sm">Quarterly</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 md:px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="ri-store-2-line mr-2 text-secondary"></i> Business Information
                        </h3>
                    </div>
                    <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <div class="col-span-1 md:col-span-2">
                             <label class="block text-xs font-bold text-gray-500 mb-1">Business Name</label>
                             <input type="text" name="businessName" placeholder="Enter Business Name" class="w-full px-4 py-3 md:py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary text-base md:text-sm" required>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Trade Name / Franchise</label>
                            <input type="text" name="tradeName" class="w-full px-4 py-3 md:py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary text-base md:text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">TIN No.</label>
                            <input type="text" name="tinNo" class="w-full px-4 py-3 md:py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary text-base md:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">DTI/SEC No.</label>
                            <input type="text" name="dtiRegNo" class="w-full px-4 py-3 md:py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary text-base md:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Registration Date</label>
                            <input type="date" name="dtiRegDate" class="w-full px-4 py-3 md:py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary text-base md:text-sm">
                        </div>

                        <div class="col-span-1 md:col-span-3 border-t border-dashed border-gray-200 pt-4 mt-2">
                            <span class="block text-xs font-bold text-gray-700 mb-2">Type of Business</span>
                            <div class="flex flex-wrap gap-3">
                                <label class="inline-flex items-center bg-white border px-3 py-2 rounded-lg cursor-pointer hover:border-primary w-full sm:w-auto">
                                    <input type="radio" name="businessType" value="Single" class="text-primary h-5 w-5" checked>
                                    <span class="ml-2 text-sm">Single Proprietorship</span>
                                </label>
                                <label class="inline-flex items-center bg-white border px-3 py-2 rounded-lg cursor-pointer hover:border-primary w-full sm:w-auto">
                                    <input type="radio" name="businessType" value="Partnership" class="text-primary h-5 w-5">
                                    <span class="ml-2 text-sm">Partnership</span>
                                </label>
                                <label class="inline-flex items-center bg-white border px-3 py-2 rounded-lg cursor-pointer hover:border-primary w-full sm:w-auto">
                                    <input type="radio" name="businessType" value="Corporation" class="text-primary h-5 w-5">
                                    <span class="ml-2 text-sm">Corporation</span>
                                </label>
                                <label class="inline-flex items-center bg-white border px-3 py-2 rounded-lg cursor-pointer hover:border-primary w-full sm:w-auto">
                                    <input type="radio" name="businessType" value="Cooperative" class="text-primary h-5 w-5">
                                    <span class="ml-2 text-sm">Cooperative</span>
                                </label>
                            </div>
                        </div>

                         <div class="col-span-1 md:col-span-3 bg-blue-50 p-4 rounded-lg flex flex-col md:flex-row items-center gap-4">
                            <div class="flex-1 w-full">
                                <label class="text-sm font-medium text-gray-700 block mb-2">Enjoying tax incentive from Gov't Entity?</label>
                                <div class="flex space-x-6">
                                    <label class="flex items-center"><input type="radio" name="taxIncentive" value="yes" class="text-primary h-5 w-5 mr-2"> Yes</label>
                                    <label class="flex items-center"><input type="radio" name="taxIncentive" value="no" class="text-primary h-5 w-5 mr-2" checked> No</label>
                                </div>
                            </div>
                            <div class="flex-1 w-full">
                                <input type="text" name="taxEntity" id="taxEntity" placeholder="If yes, specify entity" class="w-full px-4 py-3 md:py-2 rounded-lg border border-gray-300 text-base md:text-sm" disabled>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 md:px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="ri-map-pin-line mr-2 text-secondary"></i> Location & Contact
                        </h3>
                    </div>
                    <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="col-span-1">
                            <label class="label-text">Zone / Street</label>
                            <input type="text" name="businessZone" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm" placeholder="Zone 1">
                        </div>
                        <div class="col-span-1">
                            <label class="label-text">Barangay</label>
                            <select name="businessBarangay" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm bg-white" required>
                                <option value="">Select Barangay</option>
                                <option value="Aslong">Aslong</option>
                                <option value="Awayan">Awayan</option>
                                <option value="Bagacay">Bagacay</option>
                                <option value="Bagadion">Bagadion</option>
                                <option value="Bagamelon">Bagamelon</option>
                                <option value="Bagumbayan">Bagumbayan</option>
                                <option value="Bahao">Bahao</option>
                                <option value="Bahay">Bahay</option>
                                <option value="Beguito Nuevo">Beguito Nuevo</option>
                                <option value="Beguito Viejo">Beguito Viejo</option>
                                <option value="Begajo Norte">Begajo Norte</option>
                                <option value="Begajo Sur">Begajo Sur</option>
                                <option value="Bikal">Bikal</option>
                                <option value="Busak">Busak</option>
                                <option value="Caima">Caima</option>
                                <option value="Calabnigan">Calabnigan</option>
                                <option value="Camambugan">Camambugan</option>
                                <option value="Cambalidio">Cambalidio</option>
                                <option value="Candami">Candami</option>
                                <option value="Candato">Candato</option>
                                <option value="Cawayan">Cawayan</option>
                                <option value="Concepcion">Concepcion</option>
                                <option value="Cuyapi">Cuyapi</option>
                                <option value="Danawan">Danawan</option>
                                <option value="Duang Niog">Duang Niog</option>
                                <option value="Handong">Handong</option>
                                <option value="Ibid">Ibid</option>
                                <option value="Inalahan">Inalahan</option>
                                <option value="Labao">Labao</option>
                                <option value="Libod I">Libod I</option>
                                <option value="Libod II">Libod II</option>
                                <option value="Loba-loba">Loba-loba</option>
                                <option value="Mabini">Mabini</option>
                                <option value="Malansad Nuevo">Malansad Nuevo</option>
                                <option value="Malansad Viejo">Malansad Viejo</option>
                                <option value="Malbogon">Malbogon</option>
                                <option value="Malinao">Malinao</option>
                                <option value="Mambalite">Mambalite</option>
                                <option value="Mambayawas">Mambayawas</option>
                                <option value="Mambulo Nuevo">Mambulo Nuevo</option>
                                <option value="Mambulo Viejo">Mambulo Viejo</option>
                                <option value="Mancawayan">Mancawayan</option>
                                <option value="Mandacanan">Mandacanan</option>
                                <option value="Mantalisay">Mantalisay</option>
                                <option value="Padlos">Padlos</option>
                                <option value="Pag-Oring Nuevo">Pag-Oring Nuevo</option>
                                <option value="Pag-Oring Viejo">Pag-Oring Viejo</option>
                                <option value="Palangon">Palangon</option>
                                <option value="Palong">Palong</option>
                                <option value="Patag">Patag</option>
                                <option value="Planza">Planza</option>
                                <option value="Poblacion">Poblacion</option>
                                <option value="Potot">Potot</option>
                                <option value="Puro-Batia">Puro-Batia</option>
                                <option value="Rongos">Rongos</option>
                                <option value="Salvacion">Salvacion</option>
                                <option value="San Isidro">San Isidro</option>
                                <option value="San Juan">San Juan</option>
                                <option value="San Pablo">San Pablo</option>
                                <option value="San Vicente">San Vicente</option>
                                <option value="Sibujo">Sibujo</option>
                                <option value="Sigamot">Sigamot</option>
                                <option value="Station-Church Site">Station-Church Site</option>
                                <option value="Taban-Fundado">Taban-Fundado</option>
                                <option value="Tampuhan">Tampuhan</option>
                                <option value="Tanag">Tanag</option>
                                <option value="Tarum">Tarum</option>
                                <option value="Tinalmud Nuevo">Tinalmud Nuevo</option>
                                <option value="Tinalmud Viejo">Tinalmud Viejo</option>
                                <option value="Tinangkihan">Tinangkihan</option>
                                <option value="Udoc">Udoc</option>
                                <option value="Umalo">Umalo</option>
                                <option value="Uson">Uson</option>
                                <option value="Villasocorro">Villasocorro</option>
                                <option value="Villadima (Santa Cruz)">Villadima (Santa Cruz)</option>
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label class="label-text">Municipality</label>
                            <input type="text" name="municipality" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm" value="Libmanan">
                        </div>
                         <div class="col-span-1">
                            <label class="label-text">Postal Code</label>
                            <input type="text" name="postalCode" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>

                        <div class="col-span-1 md:col-span-2">
                             <label class="label-text">Email Address</label>
                             <input type="email" name="emailAddress" pattern="[^ @]*@[^ @]*" title="Must enter valid email address" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div class="col-span-1">
                             <label class="label-text">Mobile No.</label>
                             <input type="tel" name="mobileNo" maxlength="11" minlength="11" pattern="\d{11}" title="Please enter exactly 11 digits (e.g., 09123456789)" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" placeholder="e.g. 09123456789" class="w-full border-gray-300 rounded-lg text-base md:text-sm focus:ring-primary focus:border-primary px-3 py-3 md:py-2">
                        </div>
                        <div class="col-span-1">
                             <label class="label-text">Tel No.</label>
                             <input type="tel" name="telephoneNo" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 md:px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="ri-user-star-line mr-2 text-secondary"></i> Owner Information
                        </h3>
                    </div>
                    <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                             <label class="label-text">Last Name</label>
                             <input type="text" name="lastName" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm" required>
                        </div>
                        <div>
                             <label class="label-text">First Name</label>
                             <input type="text" name="firstName" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm" required>
                        </div>
                        <div>
                             <label class="label-text">Middle Name</label>
                             <input type="text" name="middleName" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div class="col-span-1 md:col-span-3">
                             <label class="label-text">Owner Full Name (For Cert)</label>
                             <input type="text" name="ownername" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                         <div class="col-span-1 md:col-span-3">
                             <label class="label-text">Owner Address</label>
                             <input type="text" name="ownerAddress" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        
                         <div>
                            <label class="label-text">Postal Code</label>
                            <input type="text" name="ownerPostalCode" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div>
                             <label class="label-text">Owner Email</label>
                             <input type="email" name="ownerEmailAddress" pattern="[^ @]*@[^ @]*" title="Must enter valid email address" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div>
                             <label class="label-text">Owner Tel No.</label>
                             <input type="tel" name="ownerTelephoneNo" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                         <div>
                             <label class="label-text">Owner Mobile</label>
                             <input type="tel" name="ownerMobileNo" maxlength="11" minlength="11" pattern="\d{11}" title="Please enter exactly 11 digits (e.g., 09123456789)" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" placeholder="e.g. 09123456789" class="w-full border-gray-300 rounded-lg text-base md:text-sm focus:ring-primary focus:border-primary px-3 py-3 md:py-2">
                        </div>
                         <div>
                             <label class="label-text">Position Title</label>
                             <input type="text" name="positionTitle" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                     <div class="bg-gray-50 px-4 md:px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="ri-building-line mr-2 text-secondary"></i> Operational Details
                        </h3>
                    </div>
                    <div class="p-4 md:p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <label class="label-text">Business Area (sq.m)</label>
                            <input type="number" name="businessArea" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="label-text">Total Employees</label>
                            <input type="number" name="totalEmployees" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                         <div class="col-span-1">
                            <label class="label-text">Male</label>
                            <input type="number" name="maleEmployees" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                         <div class="col-span-1">
                            <label class="label-text">Female</label>
                            <input type="number" name="femaleEmployees" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                         <div class="col-span-2 md:col-span-1">
                            <label class="label-text">LGU Residing Emp.</label>
                            <input type="number" name="lguEmployees" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        
                         <div class="col-span-2 md:col-span-3">
                            <label class="label-text">Line of Business</label>
                            <input type="text" name="lineOfBusiness" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>

                         <div class="col-span-2 md:col-span-1">
                            <label class="label-text">No. of Units</label>
                            <input type="number" name="noOfUnits" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        
                         <div class="col-span-2 md:col-span-1">
                            <label class="label-text">Capitalization</label>
                            <input type="number" name="capitalization" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                    </div>
                </div>

                 <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 md:px-6 py-4 border-b border-gray-100">
                       <h3 class="text-lg font-bold text-gray-800 flex items-center">
                           <i class="ri-home-wifi-line mr-2 text-secondary"></i> Lessor Details (If Rented)
                       </h3>
                    </div>
                    <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-1 md:col-span-2">
                           <label class="label-text">Lessor Full Name</label>
                           <input type="text" name="lessorFullName" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div class="col-span-1 md:col-span-2">
                           <label class="label-text">Lessor Address</label>
                           <input type="text" name="lessorAddress" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div>
                           <label class="label-text">Lessor Phone</label>
                           <input type="tel" name="lessorPhone" maxlength="11" minlength="11" pattern="\d{11}" title="Please enter exactly 11 digits (e.g., 09123456789)" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" placeholder="e.g. 09123456789" class="w-full border-gray-300 rounded-lg text-base md:text-sm focus:ring-primary focus:border-primary px-3 py-3 md:py-2">
                        </div>
                        <div>
                           <label class="label-text">Lessor Email</label>
                           <input type="email" name="lessorEmail" pattern="[^ @]*@[^ @]*" title="Must enter valid email" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div>
                           <label class="label-text">Monthly Rental</label>
                           <input type="number" name="monthlyRental" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-red-50 px-4 md:px-6 py-4 border-b border-red-100">
                        <h3 class="text-lg font-bold text-red-800 flex items-center">
                            <i class="ri-alarm-warning-line mr-2"></i> Emergency Contact
                        </h3>
                    </div>
                    <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                         <div>
                            <label class="label-text">Contact Name</label>
                            <input type="text" name="emergencyContactName" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div>
                            <label class="label-text">Contact Phone</label>
                            <input type="tel" name="emergencyContactPhone" maxlength="11" minlength="11" pattern="\d{11}" title="Please enter exactly 11 digits (e.g., 09123456789)" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" placeholder="e.g. 09123456789" class="w-full border-gray-300 rounded-lg text-base md:text-sm focus:ring-primary focus:border-primary px-3 py-3 md:py-2">
                        </div>
                         <div>
                            <label class="label-text">Contact Email</label>
                            <input type="email" name="emergencyContactEmail" pattern="[^ @]*@[^ @]*" title="Must enter enter a valid email" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                    </div>
                 </div>

                <div class="flex justify-center md:justify-end pt-6">
                     <button type="submit" name="submit_application" class="w-full md:w-auto bg-primary text-white font-bold py-4 md:py-3 px-8 rounded-lg shadow-lg hover:bg-red-800 transform hover:-translate-y-1 transition-all duration-200 flex items-center justify-center text-lg md:text-base">
                        <i class="ri-send-plane-fill mr-2"></i> Submit Application
                     </button>
                </div>
            </form>
        </section>


        <section id="renewalApplicationFormSection" class="application-form max-w-6xl mx-auto hidden">
             <button class="backToSelection mb-6 flex items-center text-gray-500 hover:text-secondary transition-colors font-medium p-2 -ml-2">
                <i class="ri-arrow-left-line mr-2"></i> Back to Selection
            </button>

             <form method="POST" enctype="multipart/form-data" class="space-y-6 md:space-y-8">
                <div class="bg-gradient-to-r from-secondary to-orange-400 rounded-xl p-6 text-white shadow-lg">
                    <h2 class="text-2xl font-bold flex items-center"><i class="ri-refresh-line mr-2"></i> Renewal Application Form</h2>
                    <p class="opacity-90">Please ensure all details are up to date.</p>
                </div>

                 <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-orange-50 px-4 md:px-6 py-4 border-b border-orange-100">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="ri-folder-upload-line mr-2 text-secondary"></i> Renewal Requirements
                        </h3>
                    </div>
                    <div class="p-4 md:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <?php 
                            $reqs_renew = [
                                'barangay_clearance_owner_renewal' => 'Brgy. Clearance (Owner)',
                                'barangay_business_clearance_renewal' => 'Brgy. Business Clearance',
                                'cedula_renewal' => 'Cedula',
                                'lease_contract_renewal' => 'Lease Contract',
                                'business_permit_form_renewal' => 'Business Permit Form',
                                'sec_registration_renewal' => 'SEC Registration',
                                'rhu_permit_renewal' => 'RHU / Sanitary Permit',
                                'meo_clearance_renewal' => 'MEO Clearance',
                                'mpdc_clearance_renewal' => 'MPDC Clearance',
                                'menro_clearance_renewal' => 'MENRO Clearance',
                                'bfp_certificate_renewal' => 'BFP Certificate',
                                'applicantSignature_renewal' => 'Signature'
                            ];
                            foreach ($reqs_renew as $name => $label): 
                            ?>
                            <div class="relative group">
                                <label class="block text-xs font-semibold text-gray-700 mb-1 ml-1"><?php echo $label; ?></label>
                                <div class="file-upload-card rounded-lg p-3 flex items-center justify-between bg-gray-50 cursor-pointer relative overflow-hidden hover:border-secondary hover:bg-orange-50 border border-gray-200 h-14">
                                    <input type="file" name="<?php echo $name; ?>" accept="image/*,.pdf" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" onchange="updateFileName(this)">
                                    <div class="flex items-center space-x-3 w-full">
                                        <div class="bg-white p-2 rounded shadow-sm">
                                            <i class="ri-upload-cloud-2-line text-secondary"></i>
                                        </div>
                                        <span class="text-xs text-gray-500 truncate file-name-display w-full">Choose file...</span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 md:px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="ri-file-list-3-line mr-2 text-secondary"></i> Application Details
                        </h3>
                    </div>
                    <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Application Type</label>
                            <div class="flex items-center space-x-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <input type="checkbox" name="applicationType_renewal" value="Renewal" checked class="w-4 h-4 text-secondary rounded focus:ring-secondary border-gray-300">
                                <span class="text-sm font-medium">Renewal Application</span>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Date</label>
                            <input type="date" name="applicationDate_renewal" class="w-full px-4 py-3 md:py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-colors text-base md:text-sm" value="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Mode of Payment</label>
                            <div class="flex flex-wrap gap-4 mt-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="paymentMode_renewal" value="Annually" class="text-secondary focus:ring-secondary h-5 w-5" checked>
                                    <span class="ml-2 text-sm">Annually</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="paymentMode_renewal" value="Semi-Annually" class="text-secondary focus:ring-secondary h-5 w-5">
                                    <span class="ml-2 text-sm">Semi-Annually</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="paymentMode_renewal" value="Quarterly" class="text-secondary focus:ring-secondary h-5 w-5">
                                    <span class="ml-2 text-sm">Quarterly</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 md:px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="ri-store-2-line mr-2 text-secondary"></i> Business Information
                        </h3>
                    </div>
                    <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <div class="col-span-1 md:col-span-2">
                             <label class="block text-xs font-bold text-gray-500 mb-1">Business Name</label>
                             <input type="text" name="businessName_renewal" placeholder="Enter Business Name" class="w-full px-4 py-3 md:py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-secondary/20 focus:border-secondary text-base md:text-sm" required>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Trade Name / Franchise</label>
                            <input type="text" name="tradeName_renewal" class="w-full px-4 py-3 md:py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-secondary/20 focus:border-secondary text-base md:text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">TIN No.</label>
                            <input type="text" name="tinNo_renewal" class="w-full px-4 py-3 md:py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-secondary/20 focus:border-secondary text-base md:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">DTI/SEC No.</label>
                            <input type="text" name="dtiRegNo_renewal" class="w-full px-4 py-3 md:py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-secondary/20 focus:border-secondary text-base md:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Registration Date</label>
                            <input type="date" name="dtiRegDate_renewal" class="w-full px-4 py-3 md:py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-secondary/20 focus:border-secondary text-base md:text-sm">
                        </div>

                        <div class="col-span-1 md:col-span-3 border-t border-dashed border-gray-200 pt-4 mt-2">
                            <span class="block text-xs font-bold text-gray-700 mb-2">Type of Business</span>
                            <div class="flex flex-wrap gap-3">
                                <label class="inline-flex items-center bg-white border px-3 py-2 rounded-lg cursor-pointer hover:border-secondary w-full sm:w-auto">
                                    <input type="radio" name="businessType_renewal" value="Single" class="text-secondary h-5 w-5" checked>
                                    <span class="ml-2 text-sm">Single Proprietorship</span>
                                </label>
                                <label class="inline-flex items-center bg-white border px-3 py-2 rounded-lg cursor-pointer hover:border-secondary w-full sm:w-auto">
                                    <input type="radio" name="businessType_renewal" value="Partnership" class="text-secondary h-5 w-5">
                                    <span class="ml-2 text-sm">Partnership</span>
                                </label>
                                <label class="inline-flex items-center bg-white border px-3 py-2 rounded-lg cursor-pointer hover:border-secondary w-full sm:w-auto">
                                    <input type="radio" name="businessType_renewal" value="Corporation" class="text-secondary h-5 w-5">
                                    <span class="ml-2 text-sm">Corporation</span>
                                </label>
                                <label class="inline-flex items-center bg-white border px-3 py-2 rounded-lg cursor-pointer hover:border-secondary w-full sm:w-auto">
                                    <input type="radio" name="businessType_renewal" value="Cooperative" class="text-secondary h-5 w-5">
                                    <span class="ml-2 text-sm">Cooperative</span>
                                </label>
                            </div>
                        </div>

                         <div class="col-span-1 md:col-span-3 bg-orange-50 p-4 rounded-lg flex flex-col md:flex-row items-center gap-4">
                            <div class="flex-1 w-full">
                                <label class="text-sm font-medium text-gray-700 block mb-2">Enjoying tax incentive from Gov't Entity?</label>
                                <div class="flex space-x-6">
                                    <label class="flex items-center"><input type="radio" name="taxIncentive_renewal" value="yes" class="text-secondary h-5 w-5 mr-2"> Yes</label>
                                    <label class="flex items-center"><input type="radio" name="taxIncentive_renewal" value="no" class="text-secondary h-5 w-5 mr-2" checked> No</label>
                                </div>
                            </div>
                            <div class="flex-1 w-full">
                                <input type="text" name="taxEntity_renewal" id="taxEntity_renewal" placeholder="If yes, specify entity" class="w-full px-4 py-3 md:py-2 rounded-lg border border-gray-300 text-base md:text-sm" disabled>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 md:px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="ri-map-pin-line mr-2 text-secondary"></i> Location & Contact
                        </h3>
                    </div>
                    <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="col-span-1">
                            <label class="label-text">Zone / Street</label>
                            <input type="text" name="businessZone_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm" placeholder="Zone 1" required>
                        </div>
                        <div class="col-span-1">
                            <label class="label-text">Barangay</label>
                            <select name="businessBarangay_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm bg-white" required>
                                <option value="">Select Barangay</option>
                                <option value="Aslong">Aslong</option>
                                <option value="Awayan">Awayan</option>
                                <option value="Bagacay">Bagacay</option>
                                <option value="Bagadion">Bagadion</option>
                                <option value="Bagamelon">Bagamelon</option>
                                <option value="Bagumbayan">Bagumbayan</option>
                                <option value="Bahao">Bahao</option>
                                <option value="Bahay">Bahay</option>
                                <option value="Beguito Nuevo">Beguito Nuevo</option>
                                <option value="Beguito Viejo">Beguito Viejo</option>
                                <option value="Begajo Norte">Begajo Norte</option>
                                <option value="Begajo Sur">Begajo Sur</option>
                                <option value="Bikal">Bikal</option>
                                <option value="Busak">Busak</option>
                                <option value="Caima">Caima</option>
                                <option value="Calabnigan">Calabnigan</option>
                                <option value="Camambugan">Camambugan</option>
                                <option value="Cambalidio">Cambalidio</option>
                                <option value="Candami">Candami</option>
                                <option value="Candato">Candato</option>
                                <option value="Cawayan">Cawayan</option>
                                <option value="Concepcion">Concepcion</option>
                                <option value="Cuyapi">Cuyapi</option>
                                <option value="Danawan">Danawan</option>
                                <option value="Duang Niog">Duang Niog</option>
                                <option value="Handong">Handong</option>
                                <option value="Ibid">Ibid</option>
                                <option value="Inalahan">Inalahan</option>
                                <option value="Labao">Labao</option>
                                <option value="Libod I">Libod I</option>
                                <option value="Libod II">Libod II</option>
                                <option value="Loba-loba">Loba-loba</option>
                                <option value="Mabini">Mabini</option>
                                <option value="Malansad Nuevo">Malansad Nuevo</option>
                                <option value="Malansad Viejo">Malansad Viejo</option>
                                <option value="Malbogon">Malbogon</option>
                                <option value="Malinao">Malinao</option>
                                <option value="Mambalite">Mambalite</option>
                                <option value="Mambayawas">Mambayawas</option>
                                <option value="Mambulo Nuevo">Mambulo Nuevo</option>
                                <option value="Mambulo Viejo">Mambulo Viejo</option>
                                <option value="Mancawayan">Mancawayan</option>
                                <option value="Mandacanan">Mandacanan</option>
                                <option value="Mantalisay">Mantalisay</option>
                                <option value="Padlos">Padlos</option>
                                <option value="Pag-Oring Nuevo">Pag-Oring Nuevo</option>
                                <option value="Pag-Oring Viejo">Pag-Oring Viejo</option>
                                <option value="Palangon">Palangon</option>
                                <option value="Palong">Palong</option>
                                <option value="Patag">Patag</option>
                                <option value="Planza">Planza</option>
                                <option value="Poblacion">Poblacion</option>
                                <option value="Potot">Potot</option>
                                <option value="Puro-Batia">Puro-Batia</option>
                                <option value="Rongos">Rongos</option>
                                <option value="Salvacion">Salvacion</option>
                                <option value="San Isidro">San Isidro</option>
                                <option value="San Juan">San Juan</option>
                                <option value="San Pablo">San Pablo</option>
                                <option value="San Vicente">San Vicente</option>
                                <option value="Sibujo">Sibujo</option>
                                <option value="Sigamot">Sigamot</option>
                                <option value="Station-Church Site">Station-Church Site</option>
                                <option value="Taban-Fundado">Taban-Fundado</option>
                                <option value="Tampuhan">Tampuhan</option>
                                <option value="Tanag">Tanag</option>
                                <option value="Tarum">Tarum</option>
                                <option value="Tinalmud Nuevo">Tinalmud Nuevo</option>
                                <option value="Tinalmud Viejo">Tinalmud Viejo</option>
                                <option value="Tinangkihan">Tinangkihan</option>
                                <option value="Udoc">Udoc</option>
                                <option value="Umalo">Umalo</option>
                                <option value="Uson">Uson</option>
                                <option value="Villasocorro">Villasocorro</option>
                                <option value="Villadima (Santa Cruz)">Villadima (Santa Cruz)</option>
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label class="label-text">Municipality</label>
                            <input type="text" name="municipality_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm" value="Libmanan" required>
                        </div>
                         <div class="col-span-1">
                            <label class="label-text">Postal Code</label>
                            <input type="text" name="postalCode_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>

                        <div class="col-span-1 md:col-span-2">
                             <label class="label-text">Email Address</label>
                             <input type="email" name="emailAddress_renewal" pattern="[^ @]*@[^ @]*" title="Must enter a valid email" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div class="col-span-1">
                             <label class="label-text">Mobile No.</label>
                             <input type="tel" name="mobileNo_renewal" maxlength="11" minlength="11" pattern="\d{11}" title="Please enter exactly 11 digits (e.g., 09123456789)" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" placeholder="e.g. 09123456789" class="w-full border-gray-300 rounded-lg text-base md:text-sm focus:ring-primary focus:border-primary px-3 py-3 md:py-2">
                        </div>
                        <div class="col-span-1">
                             <label class="label-text">Tel No.</label>
                             <input type="tel" name="telephoneNo_renewal" maxlength="11" minlength="11" pattern="\d{11}" title="Please enter exactly 11 digits (e.g., 09123456789)" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" placeholder="e.g. 09123456789" class="w-full border-gray-300 rounded-lg text-base md:text-sm focus:ring-primary focus:border-primary px-3 py-3 md:py-2">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 md:px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="ri-user-star-line mr-2 text-secondary"></i> Owner Information
                        </h3>
                    </div>
                    <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                             <label class="label-text">Last Name</label>
                             <input type="text" name="lastName_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm" required>
                        </div>
                        <div>
                             <label class="label-text">First Name</label>
                             <input type="text" name="firstName_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm" required>
                        </div>
                        <div>
                             <label class="label-text">Middle Name</label>
                             <input type="text" name="middleName_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div class="col-span-1 md:col-span-3">
                             <label class="label-text">Owner Full Name (For Cert)</label>
                             <input type="text" name="ownername_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                         <div class="col-span-1 md:col-span-3">
                             <label class="label-text">Owner Address</label>
                             <input type="text" name="ownerAddress_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        
                        <div>
                            <label class="label-text">Postal Code</label>
                            <input type="text" name="ownerPostalCode_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div>
                             <label class="label-text">Owner Email</label>
                             <input type="email" name="ownerEmailAddress_renewal" pattern="[^ @]*@[^ @]*" title="Must enter a valid email address" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div>
                            <label class="label-text">Owner Tel No.</label>
                            <input type="tel" name="ownerTelephoneNo_renewal" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                       </div>
                         <div>
                             <label class="label-text">Owner Mobile</label>
                             <input type="tel" name="ownerMobileNo_renewal" maxlength="11" minlength="11" pattern="\d{11}" title="Please enter exactly 11 digits (e.g., 09123456789)" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" placeholder="e.g. 09123456789" class="w-full border-gray-300 rounded-lg text-base md:text-sm focus:ring-primary focus:border-primary px-3 py-3 md:py-2">
                        </div>
                         <div>
                             <label class="label-text">Position Title</label>
                             <input type="text" name="positionTitle_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                     <div class="bg-gray-50 px-4 md:px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="ri-building-line mr-2 text-secondary"></i> Operational Details
                        </h3>
                    </div>
                    <div class="p-4 md:p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <label class="label-text">Business Area (sq.m)</label>
                            <input type="number" name="businessArea_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="label-text">Total Employees</label>
                            <input type="number" name="totalEmployees_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                         <div class="col-span-1">
                            <label class="label-text">Male</label>
                            <input type="number" name="maleEmployees_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                         <div class="col-span-1">
                            <label class="label-text">Female</label>
                            <input type="number" name="femaleEmployees_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                         <div class="col-span-2 md:col-span-1">
                            <label class="label-text">LGU Residing Emp.</label>
                            <input type="number" name="lguEmployees_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        
                         <div class="col-span-2 md:col-span-3">
                            <label class="label-text">Line of Business</label>
                            <input type="text" name="lineOfBusiness_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <label class="label-text">No. of Units</label>
                            <input type="number" name="noOfUnits_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        
                         <div class="col-span-2 md:col-span-1">
                            <label class="label-text">Capitalization</label>
                            <input type="number" name="capitalization_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 md:px-6 py-4 border-b border-gray-100">
                       <h3 class="text-lg font-bold text-gray-800 flex items-center">
                           <i class="ri-home-wifi-line mr-2 text-secondary"></i> Lessor Details (If Rented)
                       </h3>
                   </div>
                   <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                       <div class="col-span-1 md:col-span-2">
                           <label class="label-text">Lessor Full Name</label>
                           <input type="text" name="lessorFullName_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                       </div>
                       <div class="col-span-1 md:col-span-2">
                           <label class="label-text">Lessor Address</label>
                           <input type="text" name="lessorAddress_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                       </div>
                        <div>
                           <label class="label-text">Lessor Phone</label>
                           <input type="tel" name="lessorPhone_renewal" maxlength="11" minlength="11" pattern="\d{11}" title="Please enter exactly 11 digits (e.g., 09123456789)" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" placeholder="e.g. 09123456789" class="w-full border-gray-300 rounded-lg text-base md:text-sm focus:ring-primary focus:border-primary px-3 py-3 md:py-2">
                       </div>
                        <div>
                           <label class="label-text">Lessor Email</label>
                           <input type="email" name="lessorEmail_renewal" pattern="[^ @]*@[^ @]*" title="Must enter a valid email" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                       </div>
                        <div>
                           <label class="label-text">Monthly Rental</label>
                           <input type="number" name="monthlyRental_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                       </div>
                   </div>
               </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-orange-50 px-4 md:px-6 py-4 border-b border-orange-100">
                        <h3 class="text-lg font-bold text-orange-800 flex items-center">
                            <i class="ri-alarm-warning-line mr-2"></i> Emergency Contact
                        </h3>
                    </div>
                    <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                         <div>
                            <label class="label-text">Contact Name</label>
                            <input type="text" name="emergencyContactName_renewal" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                        <div>
                            <label class="label-text">Contact Phone</label>
                            <input type="tel" name="emergencyContactPhone_renewal" maxlength="11" minlength="11" pattern="\d{11}" title="Please enter exactly 11 digits (e.g., 09123456789)" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" placeholder="e.g. 09123456789" class="w-full border-gray-300 rounded-lg text-base md:text-sm focus:ring-primary focus:border-primary px-3 py-3 md:py-2">
                        </div>
                        <div>
                            <label class="label-text">Contact Email</label>
                            <input type="email" name="emergencyContactEmail_renewal" pattern="[^ @]*@[^ @]*" title="Must enter a valid email" class="input-field w-full px-3 py-3 md:py-2 border rounded-lg text-base md:text-sm">
                        </div>
                    </div>
                 </div>

                 <div class="flex justify-center md:justify-end pt-6">
                     <button type="submit" name="submit_application_renewal" class="w-full md:w-auto bg-secondary text-white font-bold py-4 md:py-3 px-8 rounded-lg shadow-lg hover:bg-orange-600 transform hover:-translate-y-1 transition-all duration-200 flex items-center justify-center text-lg md:text-base">
                        <i class="ri-refresh-line mr-2"></i> Submit Renewal
                     </button>
                </div>

             </form>
        </section>

    </main>

    <footer class="bg-primary text-white py-10 mt-auto border-t border-red-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
                                  
          <h1 class="font-['Pacifico'] text-3xl mb-6">TasteLibmanan</h1>
                                  
          <nav class="flex flex-wrap justify-center gap-6 mb-6 text-sm font-medium">
            <a href="#" id="about" class="hover:text-red-200 transition-colors">About</a>
            <a href="#" id="contact" class="hover:text-red-200 transition-colors">Contact</a>
            <a href="#" id="privacy" class="hover:text-red-200 transition-colors">Privacy Policy</a>
          </nav>
                                  
          <p class="text-xs text-white/80">
            © 2025 TasteLibmanan. All rights reserved.
          </p>
                                  
        </div>
    </footer>

    <div id="aboutModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex flex-col items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white flex-1 md:flex-none overflow-y-auto p-6 md:p-8 relative shadow-2xl rounded-xl max-w-4xl w-full border border-gray-200 max-h-[90vh]">
            <button id="closeAboutModal" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 text-3xl font-bold transition-colors z-10 p-2">×</button>
            <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-center text-primary mt-4 md:mt-0">About Us</h2>
            <div class="text-sm sm:text-base text-gray-700 leading-relaxed space-y-4">
                <p><strong>About the Business Permits and Licensing Office (BPLO)</strong><br>
                • The Business Permits and Licensing Office (BPLO) is the official arm of the Local Government Unit (LGU) responsible for overseeing the regulation, registration, and monitoring of all businesses operating within the municipality. Its primary mandate is to ensure that enterprises comply with national and local laws, ordinances, and standards to promote fair, safe, and legal business practices.</p>
                
                <p><strong>Purpose and Mandate</strong><br>
                • The BPLO serves as the frontline office for business owners who wish to establish, renew, or expand their businesses. Its core mission is to streamline the permitting process, enhance transparency, and support economic growth by making business registration accessible and efficient.</p>
                
                <p><strong>The BPLO plays a vital role in:</strong><br>
                • Regulating the entry and operation of businesses.<br>
                •Issuing permits and licenses in compliance with the law.<br>
                •Promoting a business-friendly environment that fosters local economic development.</p>
                
                <p><strong>Management and Organizational Role</strong><br>
                •The BPLO operates under the supervision of the Local Chief Executive (Mayor) and works closely with other government offices such as:<br>
                1. Treasurer's Office - for payment of business taxes and fees.<br>
                2. Zoning and Planning Office - to ensure business locations comply with zoning laws.<br>
                3. Health and Sanitation Office - to guarantee compliance with public health standards.<br>
                4. Bureau of Fire Protection (BFP) - for fire safety inspection and clearance.</p>
                
                <p><strong>5. Analytics and Insights</strong><br>
                The analytics provided are for reference only and should not be considered financial or legal advice.</p>
                
                <p><strong>6. Limitation of Liability</strong><br>
                Tastelibmanan is provided “as is.” We make no warranties of uninterrupted service or error-free functionality. We are not responsible for losses, damages, or issues arising from the use of the platform.</p>
                
                <p><strong>The BPLO team is responsible for receiving applications, validating requirements, processing permits, and maintaining accurate business records.</strong></p>
                
                <p><strong>Key Processes</strong><br>
                  <strong>1. Application and Renewal of Business Permits</strong><br>
                • Business owners submit application forms with required documents (such as barangay clearance, zoning clearance, occupancy permit, fire safety clearance, and health permits).<br>
                • Applications are reviewed and verified by BPLO staff.</p>
                
                <p><strong>2. Assessment and Payment of Fees</strong><br>
                •The BPLO, in coordination with the Treasurer's Office, assesses applicable taxes, regulatory fees, and charges based on business type and size.<br>
                •Payment is made at the Treasurer's Office, and receipts are issued.</p>
                
                <p><strong>3. Evaluation and Approval</strong><br>
                •After payments, the application undergoes further review by other concerned offices (health, zoning, fire).<br>
                •Upon clearance, the BPLO endorses the approval.</p>
                
                <p><strong>4. Issuance of Business Permit/License</strong><br>
                •Once all requirements are completed and verified, the official business permit is issued to the applicant.<br>
                •Businesses are then allowed to legally operate within the municipality.</p>
                
                <p><strong>5. Monitoring and Compliance</strong><br>
                • The BPLO monitors businesses to ensure compliance with regulations.<br>
                • Inspections and audits may be conducted to verify that businesses maintain legal and safety standards.</p>
                
                <p><strong>Commitment to Service</strong><br>
                The BPLO is committed to:<br>
                •Delivering fast, efficient, and transparent services.<br>
                •Reducing bureaucratic delays by digitizing processes.<br>
                •Supporting business owners through guidance and assistance.<br>
                •Upholding accountability in all transactions.</p>
            </div>
        </div>
    </div>

    <div id="contactModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex flex-col items-center justify-center backdrop-blur-sm p-4">
      <div class="bg-white overflow-y-auto p-6 md:p-8 relative shadow-2xl rounded-xl max-w-2xl w-full max-h-[90vh]">
        <button id="closeContactModal" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 text-3xl font-bold transition-colors p-2">×</button>
        <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-center text-primary mt-4 md:mt-0">Contact Us</h2>
        <div class="space-y-6 text-gray-700">
          <p class="text-center text-base">We'd love to hear from you! Reach out to us through any of the following channels:</p>
          <div class="bg-gray-50 p-6 rounded-xl space-y-4">
            <p class="flex items-start"><i class="ri-map-pin-line mr-3 text-primary mt-1"></i> <span><strong>Address:</strong> Municipal Hall, Libmanan, Camarines Sur, Philippines</span></p>
            <p class="flex items-center"><i class="ri-phone-line mr-3 text-primary"></i> <strong>Phone:</strong> (054) 123-4567</p>
            <p class="flex items-center"><i class="ri-mail-line mr-3 text-primary"></i> <strong>Email:</strong> tastelibmanan@gmail.com</p>
            <p class="flex items-start"><i class="ri-time-line mr-3 text-primary mt-1"></i> <span><strong>Office Hours:</strong> Monday – Friday, 8:00 AM – 5:00 PM (excluding holidays)</span></p>
          </div>
        </div>
      </div>
    </div>

    <div id="privacyModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex flex-col items-center justify-center backdrop-blur-sm p-4">
      <div class="bg-white overflow-y-auto p-6 md:p-8 relative shadow-2xl rounded-xl max-w-3xl w-full max-h-[90vh]">
        <button id="closePrivacyModal" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 text-3xl font-bold transition-colors p-2">×</button>
        <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-center text-primary mt-4 md:mt-0">Privacy Policy</h2>
        <div class="text-sm sm:text-base text-gray-700 leading-relaxed space-y-4">
          <p class="text-center text-gray-500 text-xs uppercase tracking-widest mb-4">Last Updated: October 2, 2025</p>
          <div class="space-y-2">
              <p><strong>1. Introduction</strong><br>• The BPLO System respects your privacy and is committed to protecting the personal information you provide. This  Privacy Policy explains how we collect, use, and safeguard your information.</p>
              <p><strong>2. Information We Collect</strong><br>•  Personal Information: Name, address, email, contact number, and business details (when applying for permits). <br>
                          • System Data: Login credentials, account activity, and application status. <br>
                          • Technical Data: IP address, browser type, and usage logs for security and analytics.</p>
              <p><strong>3. How We Use Your Information</strong><br>We use collected data for: <br>
                          • Processing and verifying business permit applications. <br>
                          • Communicating with applicants regarding their permits or inquiries. <br>
                          • Ensuring system security and preventing unauthorized access. <br>
                          • Generating reports for transparency and internal use.</p>
              <p><strong>4. Data Sharing</strong><br>• Your information will only be shared with authorized government personnel and will not be sold or disclosed to third parties, except as required by law.</p>
              <p><strong>5. Data Security</strong><br>• We implement security measures such as encryption, access control, and regular system monitoring to safeguard your information.</p>
              <p><strong>6. User Rights</strong><br>You have the right to: <br>
                          • Access and update your personal information. <br>
                          • Request correction of inaccurate records. <br>
                          •Request deletion of your data, subject to government retention requirements.</p>
              <p><strong>7. Updates to This Policy</strong><br>• The BPLO System may update this Privacy Policy from time to time. Any changes will be posted on the system with a revised “Last Updated” date.</p>
          </div>
        </div>
      </div>
    </div>

    <script>
         // Close modal on outside click
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('accountModal');
            if (e.target == modal) {
                closeAccountModal();
            }
        });

        // Helper function to setup modal toggles
        function setupModal(triggerId, modalId, closeId) {
            const trigger = document.getElementById(triggerId);
            const modal = document.getElementById(modalId);
            const closeBtn = document.getElementById(closeId);

            if (trigger && modal && closeBtn) {
                trigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden'); // Prevent background scrolling
                });

                closeBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                });

                // Close when clicking outside the modal content
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }
                });
            }
        }

        // Initialize Modals
        document.addEventListener('DOMContentLoaded', () => {
            setupModal('about', 'aboutModal', 'closeAboutModal');
            setupModal('contact', 'contactModal', 'closeContactModal');
            setupModal('privacy', 'privacyModal', 'closePrivacyModal');
        });

        // Form Toggle Logic
        const selectionSection = document.getElementById('selectionSection');
        const newAppSection = document.getElementById('newApplicationFormSection');
        const renewAppSection = document.getElementById('renewalApplicationFormSection');
        
        document.getElementById('startNewApplication').addEventListener('click', () => {
            selectionSection.classList.add('hidden');
            newAppSection.classList.remove('hidden');
            newAppSection.style.display = 'block';
            window.scrollTo(0,0);
        });

        document.getElementById('startRenewalApplication').addEventListener('click', () => {
            selectionSection.classList.add('hidden');
            renewAppSection.classList.remove('hidden');
            renewAppSection.style.display = 'block';
            window.scrollTo(0,0);
        });

        document.querySelectorAll('.backToSelection').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                newAppSection.style.display = 'none';
                newAppSection.classList.add('hidden');
                renewAppSection.style.display = 'none';
                renewAppSection.classList.add('hidden');
                selectionSection.classList.remove('hidden');
                window.scrollTo(0,0);
            });
        });

        // File Input Name Display
        function updateFileName(input) {
            const fileNameSpan = input.parentElement.querySelector('.file-name-display');
            if (input.files && input.files[0]) {
                fileNameSpan.textContent = input.files[0].name;
                fileNameSpan.classList.add('text-gray-900', 'font-medium');
                input.parentElement.classList.add('border-primary', 'bg-red-50');
            } else {
                fileNameSpan.textContent = "Choose file...";
            }
        }

        // Tax Incentive Toggle (New Application)
        const taxIncentiveRadios = document.getElementsByName("taxIncentive");
        const taxEntityInput = document.getElementById("taxEntity");
        
        taxIncentiveRadios.forEach(radio => {
            radio.addEventListener("change", () => {
                if (radio.value === "yes") {
                    taxEntityInput.disabled = false;
                    taxEntityInput.focus();
                } else {
                    taxEntityInput.disabled = true;
                    taxEntityInput.value = "";
                }
            });
        });

        // Tax Incentive Toggle (Renewal Application)
        const taxIncentiveRadiosRenewal = document.getElementsByName("taxIncentive_renewal");
        const taxEntityInputRenewal = document.getElementById("taxEntity_renewal");
        
        taxIncentiveRadiosRenewal.forEach(radio => {
            radio.addEventListener("change", () => {
                if (radio.value === "yes") {
                    taxEntityInputRenewal.disabled = false;
                    taxEntityInputRenewal.focus();
                } else {
                    taxEntityInputRenewal.disabled = true;
                    taxEntityInputRenewal.value = "";
                }
            });
        });
        // --- Navigation & Modal Scripts ---
    function openAccountModal() { 
        const m = document.getElementById("accountModal");
        if(m) {
            m.classList.remove("hidden");
            // Small timeout to allow transition if you add one later
            setTimeout(() => { 
                const inner = m.querySelector('div');
                if(inner) inner.classList.remove('scale-95', 'opacity-0'); 
            }, 10);
        }
    }
    
    function closeAccountModal() { 
        const m = document.getElementById("accountModal");
        if(m) m.classList.add("hidden");
    }
    
    document.addEventListener('DOMContentLoaded', function () {
        // Mobile Menu Toggle
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if(hamburgerBtn && mobileMenu) {
            hamburgerBtn.addEventListener('click', () => { 
                mobileMenu.classList.toggle('hidden'); 
            });
        }
    });
    </script>
    
    <?php if ($swal_trigger): ?>
    <script>
        Swal.fire({
            title: '<?php echo $swal_title; ?>',
            text: '<?php echo $swal_text; ?>',
            icon: '<?php echo $swal_icon; ?>',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        }).then((result) => {
            <?php if (!empty($swal_redirect)): ?>
                window.location.href = '<?php echo $swal_redirect; ?>';
            <?php endif; ?>
        });
    </script>
    <?php endif; ?>
</body>
</html>