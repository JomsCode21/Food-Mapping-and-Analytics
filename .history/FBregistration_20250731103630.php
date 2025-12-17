<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;  require_once 'db_con.php';
  
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
    $uploadBase = __DIR__ . '/uploads/requirements/';

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
                $filePaths[$field] = 'uploads/requirements/' . $field . '/' . $uniqueName;
            } else {
                $filePaths[$field] = '';
            }
        } else {
            $filePaths[$field] = '';
        }
    }
    
    $paymentMode = $_POST['paymentMode'] ?? '';
    $applicationDate = $_POST['applicationDate'] ?? '';
      if (empty($applicationDate)) {
          $applicationDate = date('Y-m-d');
      }    $applicationDate = $_POST['applicationDate'] ?? '';
    $tinNo = $_POST['tinNo'] ?? '';
    $dtiRegNo = $_POST['dtiRegNo'] ?? '';
    $dtiRegDate = $_POST['dtiRegDate'] ?? '';
    $businessType = $_POST['businessType'] ?? '';
    $taxIncentive = isset($_POST['taxIncentive']) ? $_POST['taxIncentive'] : '';
    $taxEntity = $_POST['taxEntity'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $firstName = $_POST['firstName'] ?? '';
    $middleName = $_POST['middleName'] ?? '';
    $ownername = $_POST['ownername'] ?? '';
    $businessName = $_POST['businessName'] ?? '';
    $tradeName = $_POST['tradeName'] ?? '';
    $businessAddress = $_POST['businessAddress'] ?? '';
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
    $EmergencyContactEmail = $_POST['EmergencyContactEmail'] ?? '';
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
    $status = 'pending'; // Default status for new applications
    
$stmt = $conn->prepare("INSERT INTO business_applications(
payment_mode, application_type, application_date, tin_no, dti_reg_no, dti_reg_date, business_type, tax_incentive, tax_entity, last_name, first_name, middle_name, owner_name, business_name, trade_name, business_address, postal_code, telephone_no, email_address, mobile_no, owner_address, owner_postal_code, owner_telephone_no, owner_email_address, owner_mobile_no, emergency_contact_name, emergency_contact_phone, emergency_contact_email, business_area, total_employees, male_employees, female_employees, lgu_employees, lessor_full_name, lessor_address, lessor_phone, lessor_email, monthly_rental, line_of_business, no_of_units, capitalization, position_title, barangay_clearance_owner, barangay_business_clearance, police_clearance, cedula, lease_contract, business_permit_form, dti_registration, sec_registration, rhu_permit, meo_clearance, mpdc_clearance, menro_clearance, bfp_certificate, applicant_signature, status, user_id
) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,)");

   $stmt->bind_param( "sssssssssssssssssssssssssssssssssssssssssssssssssssssssssi", 
        $paymentMode, 
        $applicationType, 
        $applicationDate, 
        $tinNo, 
        $dtiRegNo, 
        $dtiRegDate, 
        $businessType, 
        $taxIncentive, 
        $taxEntity, 
        $lastName, 
        $firstName, 
        $middleName, 
        $ownername, 
        $businessName, 
        $tradeName, 
        $businessAddress, 
        $postalCode, 
        $telephoneNo, 
        $emailAddress, 
        $mobileNo, 
        $ownerAddress, 
        $ownerPostalCode, 
        $ownerTelephoneNo, 
        $ownerEmailAddress, 
        $ownerMobileNo, 
        $emergencyContactName, 
        $emergencyContactPhone, 
        $EmergencyContactEmail,
        $businessArea,
        $totalEmployees,
        $maleEmployees,
        $femaleEmployees,
        $lguEmployees,
        $lessorFullName,
        $lessorAddress,
        $lessorPhone,
        $lessorEmail,
        $monthlyRental,
        $lineOfBusiness,
        $noOfUnits,
        $capitalization,
        $positionTitle,
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
            $filePaths['applicantSignature'],
            $status,
            $user_id
        );
        $stmt->execute();
        if ($stmt->error) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $stmt->error]);
            exit();
        }
        if ($stmt->affected_rows > 0) {
            // the data to show in the pending table
            $application = [
                'business_name' => $businessName,
                'owner_name' => $ownername,
                'business_type' => $businessType,
                'application_date' => $applicationDate,
            
            ];
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'application' => $application]);
            exit();
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false]);
            exit();
        }
    
    
  }
?>
<!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Libmanan Food</title>
      <script src="https://cdn.tailwindcss.com/3.4.16"></script>
      <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: { primary: "#A80000", secondary: "#FF6B00" },
              borderRadius: {
                none: "0px",
                sm: "4px",
                DEFAULT: "8px",
                md: "12px",
                lg: "16px",
                xl: "20px",
                "2xl": "24px",
                "3xl": "32px",
                full: "9999px",
                button: "8px",
              },
            },
          },
        };
      </script>
      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      <link
        href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap"
        rel="stylesheet"
      />
      <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
      />
      <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
      />
      <link rel="stylesheet" href="style.css" />
      <style>
        :where([class^="ri-"])::before {
          content: "\f3c2";
        }
        body {
          font-family: "Inter", sans-serif;
        }

        .login-modal,
        .register-modal {
          display: none;
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.5);
          z-index: 50;
          align-items: center;
          justify-content: center;
        }
        .modal-content {
          background-color: white;
          border-radius: 8px;
          max-width: 90%;
          width: 340px;
          max-height: 90vh;
          overflow-y: auto;
        }
        .logo-large {
          height: 100px;
          width: auto;
          font-size: 50px;
          margin-left: 5cm;
        }
        .application-form {
          display: none; 
        }
      </style>
    </head>
    <body class="bg-gray-50 text-gray-800">
      <!-- Navigation Bar -->
      <nav class="fixed top-0 w-full bg-white text-gray-800 shadow-sm z-40">
        <div class="flex items-center justify-between px-4 py-3">
          <div class="flex items-center">
            <img
              src="vendors/imgsource/dtilogo.jpg"
              class="logo-large md:ml-0 ml-0"
              style="margin-left:0.5cm;"
            />
          </div>

          <div class="flex items-center space-x-3">
            <button
              id="login-btn"
              class="text-sm border border-primary text-primary px-3 py-1.5 rounded-button cursor-pointer transform transition-transform duration-300 hover:scale-105"
            >
              Login
            </button>
            <button
              id="register-btn"
              class="text-sm bg-primary text-white px-3 py-1.5 rounded-button cursor-pointer transform transition-transform duration-300 hover:scale-105"
            >
              Create Account
            </button>
          </div>
        </div>
      </nav>
      <main class="pt-16 pb-16">
        <main class="pt-20 pb-16">
          <div class="flex-1 flex justify-center">
        <button id="homeBtn" class="fixed top-4 left-1/2 transform -translate-x-1/2 text-sm bg-primary text-white px-4 py-2 rounded-lg hover:scale-105 transition-transform duration-200 z-50"
          style="margin-top: 4rem;"
        >
          <i class="ri-home-line"></i> Home
        </button>
      </div>
        
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const homeBtn = document.getElementById("homeBtn");
        
            homeBtn.addEventListener("click", function () {
                window.location.href = "index.php";
            });
        });
        </script>
      <!-- Main Content -->
      <style>
  /* Responsive logo alignment */
  @media (max-width: 768px) {
    .logo-large {
      margin-left: 0 !important;
      height: 70px !important;
    }
  }
  /* Home button margin top */
  #homeBtn {
    margin-top: 1rem; /* Adjust as needed for your design */
  }
  </style>

        <section class="px-4 py-6">
          <div class="text-center mb-6">
            <h2 class="text-2xl font-bold">Business <span class="text-primary">Registration</span></h2>
            <p class="text-gray-600 mt-2" style="font-size: 30px;">
              Select the type of application you would like to proceed with.</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <!-- New Application Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
              <h3 class="text-xl font-semibold mb-4">New Application</h3>
              <p class="text-gray-700 mb-4">
                Register a new food business and get listed on our platform.
              </p>
              <button
                id="startNewApplication"
                class="bg-primary text-white px-4 py-2 rounded-button hover:bg-secondary transition-colors duration-300"
              >
                Start New Application
              </button>
            </div>

            <!-- Renewal Application Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
              <h3 class="text-xl font-semibold mb-4">Renewal Application</h3>
              <p class="text-gray-700 mb-4">
                Renew your existing business registration to stay active on our
                platform.
              </p>
              <button id="startRenewalApplication" class="bg-primary text-white px-4 py-2 rounded-button hover:bg-secondary transition-colors duration-300">
                Renew Application
              </button>
            </div>
          </div>
        </section>

        <!-- New Application Form -->
        <section id="newApplicationFormSection" class="px-4 py-6 application-form">

        <!-- requirements -->
    
    <div class="text-center mb-6">
      <h2 class="text-2xl font-bold">List of <span class="text-primary">Requirements</span> for Business Permit</h2>
      <p class="text-gray-600 mt-2">Upload a clear image or file for each requirement.</p>
    </div>

  <form id="newApplicationForm" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 bg-white rounded-lg shadow-md p-6">
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Barangay Clearance (Owner):</label>
        <input type="file" name="barangay_clearance_owner" ...accept="image/*,.pdf" class="block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white" placeholder="Upload image or file">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Barangay Business Clearance:</label>
        <input type="file" name="barangay_business_clearance"... accept="image/*,.pdf" class="block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white" placeholder="Upload image or file">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Police Clearance (new):</label>
        <input type="file" name="police_clearance"... accept="image/*,.pdf" class="block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white" placeholder="Upload image or file">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Cedula:</label>
        <input type="file" name="cedula" ... accept="image/*,.pdf" class="block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white" placeholder="Upload image or file">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Lease Contract (if rented):</label>
        <input type="file" name="lease_contract" ... accept="image/*,.pdf" class="block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white" placeholder="Upload image or file">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Business Permit Form:</label>
        <input type="file" name="business_permit_form" ...accept="image/*,.pdf" class="block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white" placeholder="Upload image or file">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">DTI - Business Name Registration (Valid Government ID):</label>
        <input type="file" name="dti_registration" ...accept="image/*,.pdf" class="block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white" placeholder="Upload image or file">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">SEC registration (corporation/cooperative/association):</label>
        <input type="file" name="sec_registration" ...accept="image/*,.pdf" class="block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white" placeholder="Upload image or file">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">RHU (Sanitary Permit to Operate):</label>
        <input type="file" name="rhu_permit" ... accept="image/*,.pdf" class="block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white" placeholder="Upload image or file">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">MEO (Mun. Engineering Office):</label>
        <input type="file" name="meo_clearance"... accept="image/*,.pdf" class="block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white" placeholder="Upload image or file">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">MPDC (Mun. Planning & Devt. Coordinator):</label>
        <input type="file" name="mpdc_clearance"... accept="image/*,.pdf" class="block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white" placeholder="Upload image or file">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">MENRO (Solid Waste Mgt. Clearance):</label>
        <input type="file" name="menro_clearance"... accept="image/*,.pdf" class="block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white" placeholder="Upload image or file">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">BFP (Fire Safety and Inspection Certificate):</label>
        <input type="file" name="bfp_certificate"... accept="image/*,.pdf" class="block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white" placeholder="Upload image or file">
      </div>

        <br>  
        
        <!-- Column 1 -->
      <div class="w-full flex justify-center mb-6">
        <h2 class="text-2xl font-bold text-center">
        New Business <span class="text-primary">Application Form</span>
        </h2>
      </div>
        <br>

          <div>
        
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Mode of Payment:</label>
              <div class="flex flex-col">
                <label class="mr-4">
                  <input type="radio" name="paymentMode" value="annually"> Annually
                </label>
                <label>
                  <input type="radio" name="paymentMode" value="semiAnnually"> Semi-Annually
                </label>
                <label>
                  <input type="radio" name="paymentMode" value="quarterly"> Quarterly
                </label>
              </div>
            </div>
      
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Type of Application:</label>
              <label>
                <input type="checkbox" name="applicationType"  value="new"> New
              </label>
            </div>
      
            <div class="mb-4">
              <label for="applicationDate" class="block text-gray-700 text-sm font-bold mb-2">Date of Application:</label>
              <input type="date"  name="applicationDate" id="applicationDate"  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
      
            <div class="mb-4">
              <label for="tinNo" class="block text-gray-700 text-sm font-bold mb-2">TIN No.:</label>
              <input type="text" name="tinNo" id="tinNo"  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
      
            <div class="mb-4">
              <label for="dtiRegNo" class="block text-gray-700 text-sm font-bold mb-2">DTI/SEC/CDA Registration No.:</label>
              <input type="text" name="dtiRegNo" id="dtiRegNo"  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
      
            <div class="mb-4">
              <label for="dtiRegDate" class="block text-gray-700 text-sm font-bold mb-2">DTI/SEC/CDA Registration Date:</label>
              <input type="date" name="dtiRegDate"id="dtiRegDate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
      
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Type of Business:</label>
              <div class="flex flex-col">
                <label class="mr-4">
                  <input type="radio" name="businessType" value="single"> Single
                </label>
                <label>
                  <input type="radio" name="businessType" value="partnership"> Partnership
                </label>
                <label>
                  <input type="radio" name="businessType" value="corporation"> Corporation
                </label>
                <label>
                  <input type="radio" name="businessType" value="cooperative"> Cooperative
                </label>
              </div>
            </div>
      
            <!-- Amendment Section (Likely Not Applicable for New Applications) -->
      
          <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Are you enjoying tax incentive from any Government Entity?:</label>
              <div class="flex">
                <label class="mr-4">
                  <input type="radio" name="taxIncentive" value="yes"> Yes
                </label>
                <label>
                  <input type="radio" name="taxIncentive" value="no"> No
                </label>
              </div>
            </div>
      
            <div class="mb-4">
              <label for="taxEntity" class="block text-gray-700 text-sm font-bold mb-2">If yes, please specify the entity?:</label>
              <input type="text" name="taxEntity" id="taxEntity" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
      
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Name of Taxpayer / Registrant:</label>
              <input type="text" name="lastName" id="lastName" placeholder="Last Name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
              <input type="text" name="firstName" id="firstName" placeholder="First Name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
              <input type="text" name="middleName" id="middleName" placeholder="Middle Name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
          
            <div class="mb-4">
              <label for="ownername" ... class="block text-gray-700 text-sm font-bold mb-2">Owner Name:</label>
              <input type="text" name="ownername" id="ownername" placeholder="Owner Name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
              <label for="businessName" ...class="block text-gray-700 text-sm font-bold mb-2">Business Name:</label>
              <input type="text" name="businessName" id="businessName" placeholder="Business Name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
      
            <div class="mb-4">
              <label for="tradeName" class="block text-gray-700 text-sm font-bold mb-2">Trade Name / Franchise:</label>
              <input type="text" name="tradeName" id="tradeName" placeholder="Trade Name / Franchise" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
      
            <div class="mb-4">
              <label for="businessAddress" class="block text-gray-700 text-sm font-bold mb-2">Business Address:</label>
              <input type="text" name="businessAddress" id="businessAddress" placeholder="Business Address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
      
            <div class="mb-4">
              <label for="postalCode" class="block text-gray-700 text-sm font-bold mb-2">Postal Code:</label>
              <input type="text" name="postalCode" id="postalCode" placeholder="Postal Code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
              <label for="telephoneNo" class="block text-gray-700 text-sm font-bold mb-2">Telephone No.:</label>
            <input type="text" name="telephoneNo" id="telephoneNo" placeholder="Telephone No." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
      
            <div class="mb-4">
              <label for="emailAddress" class="block text-gray-700 text-sm font-bold mb-2">Email Address:</label>
            <input type="email" name="emailAddress" id="emailAddress" placeholder="Email Address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
      
            <div class="mb-4">
              <label for="mobileNo" class="block text-gray-700 text-sm font-bold mb-2">Mobile No.:</label>
            <input type="tel" name="mobileNo" id="mobileNo" placeholder="Mobile No." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">          </div>
      
            <div class="mb-4">
              <label for="ownerAddress" class="block text-gray-700 text-sm font-bold mb-2">Owner's Address:</label>
              <input type="text" name="ownerAddress" id="ownerAddress" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
          </div>
      
          <!-- Column 2 -->
          <div>
            <br> 
            <br>
          <div class="mb-4">
              <label for="ownerPostalCode" class="block text-gray-700 text-sm font-bold mb-2">Postal Code:</label>
              <input type="text" name="ownerPostalCode" id="ownerPostalCode" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
              <label for="ownerTelephoneNo" class="block text-gray-700 text-sm font-bold mb-2">Telephone No.:</label>
              <input type="text" name="ownerTelephoneNo" id="ownerTelephoneNo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
              <label for="ownerEmailAddress" class="block text-gray-700 text-sm font-bold mb-2">Email Address:</label>
              <input type="email" name="ownerEmailAddress" id="ownerEmailAddress" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
              <label for="ownerMobileNo" class="block text-gray-700 text-sm font-bold mb-2">Mobile No.:</label>
              <input type="tel" name="ownerMobileNo" id="ownerMobileNo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
              <label for="emergencyContactName" class="block text-gray-700 text-sm font-bold mb-2">In case of emergency, provide name of contact person:</label>
              <input type="text" name="emergencyContactName" id="emergencyContactName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
              <label for="emergencyContactPhone" class="block text-gray-700 text-sm font-bold mb-2">Telephone / Mobile No.:</label>
              <input type="text" name="emergencyContactPhone" id="emergencyContactPhone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
              <label for="EmergencyContactEmail" class="block text-gray-700 text-sm font-bold mb-2">Email Address:</label>
              <input type="email" name="EmergencyContactEmail" id="EmergencyContactEmail" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
              <label for="businessArea" class="block text-gray-700 text-sm font-bold mb-2">Business Area (in sq. m.):</label>
              <input type="number" name="businessArea" id="businessArea" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Total No. of Employees in Establishment:</label>
              <input type="number" name="totalEmployees" id="totalEmployees" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Male:</label>
              <input type="number" name="maleEmployees" id="maleEmployees" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Female:</label>
              <input type="number" name="femaleEmployees" id="femaleEmployees" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
              <label for="lguEmployees" class="block text-gray-700 text-sm font-bold mb-2">No. of Employees Residing within LGU:</label>
              <input type="number" name="lguEmployees" id="lguEmployees" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Note: Fill Up Only If Business Place is Rented:</label>
              <input type="text" name="lessorFullName" id="lessorFullName" placeholder="Lessor's Full Name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
              <input type="text" name="lessorAddress" id="lessorAddress" placeholder="Lessor's Full Address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
              <input type="text" name="lessorPhone" id="lessorPhone" placeholder="Lessor's Telephone / Mobile No." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
              <input type="email" name="lessorEmail" id="lessorEmail" placeholder="Lessor's Email Address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
              <input type="number" name="monthlyRental" id="monthlyRental" placeholder="Monthly Rental" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">3. BUSINESS ACTIVITY</label>
              <input type="text" name="lineOfBusiness" id="lineOfBusiness" placeholder="Line of Business" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
              <input type="number" name="noOfUnits" id="noOfUnits" placeholder="No. of Units" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
              <input type="number" name="capitalization" id="capitalization" placeholder="Capitalization (for New Business)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
            </div>
      
            <!-- Gross/Sales Receipt (Not Applicable for New Applications) -->
      
            <div class="mb-4">
              <label for="applicantSignature" class="block text-gray-700 text-sm font-bold mb-2">SIGNATURE OF APPLICANT/TAXPAYER OVER PRINTED NAME:</label>
              <input type="file" name="applicantSignature" id="applicantSignature" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
      
            <div class="mb-4">
              <label for="positionTitle" class="block text-gray-700 text-sm font-bold mb-2">POSITION / TITLE:</label>
            <input type="text" name="positionTitle" id="positionTitle" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">          </div>
          </div>
      
  <!-- Submit Button -->
  <button type="submit" name="submit_application" id="submitApplicationBtn" class="bg-primary text-white px-4 py-2 rounded-button hover:bg-secondary transition-colors duration-300">Submit Application</button>
    <input type="hidden" name="submit_application" value="1">
    <button class="bg-gray-400 text-white px-4 py-2 rounded-button hover:bg-gray-500 transition-colors duration-300 ml-2">Discard</button>
  </form>


  <!-- First Confirmation Modal -->
  <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center z-50">
      <div class="bg-white p-5 rounded-lg shadow-md max-w-md w-full text-center">
          <h2 class="text-lg font-semibold mb-4">Confirmation</h2>
          <p class="mb-4">Are you sure that all inputs are correct?</p>
          <div class="flex justify-end">
              <button id="reviewBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-button hover:bg-gray-400 mr-2">Review</button>
              <button id="confirmSubmitBtn" name="confirmSubmitBtn" class="bg-primary text-white px-4 py-2 rounded-button hover:bg-secondary" onclick="submitForm()">Submit</button>
          </div>
      </div>
  </div>

  <!-- Success Notification Modal (Application Submitted) -->
  <div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-md max-w-md w-full text-center">
      <h2 class="text-xl font-semibold mb-4">Application Submitted</h2>
      <p class="mb-6">Please wait for the approval, we will contact your EMAIL or PHONE NUMBER soon.</p>
      <button id="okayBtn" class="bg-primary text-white px-4 py-2 rounded-button hover:bg-secondary">Okay</button>
    </div>
  </div>



  <script>
  document.addEventListener("DOMContentLoaded", function () {
      const submitApplicationBtn = document.getElementById("submitApplicationBtn");
      const okayBtn = document.getElementById("okayBtn");
      const confirmationModal = document.getElementById("confirmationModal");
      const reviewBtn = document.getElementById("reviewBtn");
      const confirmSubmitBtn = document.getElementById("confirmSubmitBtn");
      const successModal = document.getElementById("successModal");

      const form = document.getElementById("newApplicationForm");
      form.addEventListener("submit", function (e) {
      e.preventDefault();

    const formData = new FormData(form);

    fetch('FBregistration.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // Add new row to Pending table in BPLO via window.postMessage
        window.parent.postMessage({type: 'newApplication', application: data.application}, '*');
        // Optionally show a modal or success message
        document.getElementById("successModal").classList.remove("hidden");
        document.getElementById("successModal").classList.add("flex");
      } else {
        alert('Submission failed.');
      }
    })
    .catch(() => alert('Network error.'));
  });
}); // Add this line
      
      const taxIncentiveRadios = document.getElementsByName("taxIncentive");
      const taxEntityInput = document.getElementById("taxEntity");


      // Simulate clicking the Okay button after a delay
      okayBtn.addEventListener("click", function () {
        setTimeout(function() {
          successModal.classList.remove("flex");
          successModal.classList.add("hidden");
          window.location.href = "index.php"; // Redirect to the landing page
        }, 2000); // Adjust the delay as needed (e.g., 3000ms for 3 seconds)
        });
/*************************************************************************************** 
      submitApplicationBtn.addEventListener("click", function (e) {
          e.preventDefault();
          confirmationModal.classList.remove("hidden");
          confirmationModal.classList.add("flex");
      });

      reviewBtn.addEventListener("click", function () {
          confirmationModal.classList.add("hidden");
          confirmationModal.classList.remove("flex");
      });

      confirmSubmitBtn.addEventListener("click", function () {
          confirmationModal.classList.add("hidden");
          confirmationModal.classList.remove("flex");

          successModal.classList.remove("hidden");
          successModal.classList.add("flex");
      });

      function submitForm() {
        form.submit(); // Submit the form programmatically
      }
*************************************************************************************************/
      function toggleTaxEntity() {
        if (document.querySelector('input[name="taxIncentive"]:checked')?.value === "no") {
          taxEntityInput.value = "";
          taxEntityInput.disabled = true;
        } else {
          taxEntityInput.disabled = false;
        }
      }

      taxIncentiveRadios.forEach(radio => {
        radio.addEventListener("change", toggleTaxEntity);
      }); 

  // Run on page load in case of pre-filled value
  toggleTaxEntity();
  
  </script>      
        
      </section>

      <!--renewal Application Form -->

      <section id="renewalApplicationForm"class="px-4 py-6 application-form">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold">Renewal Business <span class="text-primary">Application Form</span></h2>
      </div>
      <form class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Column 1 -->
      <div>
        <fieldset class="mb-4">
        <legend class="block text-gray-700 text-sm font-bold mb-2">Mode of Payment:</legend>
        <div class="flex flex-col">
          <label><input type="radio" name="paymentModeRenewal" value="annually"> Annually</label>
          <label><input type="radio" name="paymentModeRenewal" value="semiAnnually"> Semi-Annually</label>
          <label><input type="radio" name="paymentModeRenewal" value="quarterly"> Quarterly</label>
        </div>
        </fieldset>
    
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Type of Application:</label>
          <label>
            <input type="checkbox" name="applicationTypeRenewal" value="renewal" checked disabled> Renewal
          </label>
        </div>
    
        <div class="mb-4">
          <label for="applicationDateRenewal" class="block text-gray-700 text-sm font-bold mb-2">Date of Application:</label>
          <input type="date" id="applicationDateRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="tinNoRenewal" class="block text-gray-700 text-sm font-bold mb-2">TIN No.:</label>
          <input type="text" id="tinNoRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="dtiRegNoRenewal" class="block text-gray-700 text-sm font-bold mb-2">DTI/SEC/CDA Registration No.:</label>
          <input type="text" id="dtiRegNoRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="dtiRegDateRenewal" class="block text-gray-700 text-sm font-bold mb-2">DTI/SEC/CDA Registration Date:</label>
          <input type="date" id="dtiRegDateRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Type of Business:</label>
          <div class="flex flex-col">
            <label class="mr-4">
              <input type="radio" name="businessTypeRenewal" value="single"> Single
            </label>
            <label>
              <input type="radio" name="businessTypeRenewal" value="partnership"> Partnership
            </label>
            <label>
              <input type="radio" name="businessTypeRenewal" value="corporation"> Corporation
            </label>
            <label>
              <input type="radio" name="businessTypeRenewal" value="cooperative"> Cooperative
            </label>
          </div>
        </div>
    
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Amendment:</label>
          <input type="text" id="amendmentFrom" placeholder="From" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
          <input type="text" id="amendmentTo" placeholder="To" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Are you enjoying tax incentive from any Government Entity?:</label>
          <div class="flex">
            <label class="mr-4">
              <input type="radio" name="taxIncentiveRenewal" value="yes"> Yes
            </label>
            <label>
              <input type="radio" name="taxIncentiveRenewal" value="no"> No
            </label>
          </div>
        </div>
    
        <div class="mb-4">
          <label for="taxEntityRenewal" class="block text-gray-700 text-sm font-bold mb-2">Please specify the entity?:</label>
          <input type="text" id="taxEntityRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Name of Taxpayer / Registrant:</label>
          <input type="text" id="lastNameRenewal" placeholder="Last Name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
          <input type="text" id="firstNameRenewal" placeholder="First Name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
          
        </div>
    
        <div class="mb-4">
          <label for="businessNameRenewal" class="block text-gray-700 text-sm font-bold mb-2">Business Name:</label>
          <input type="text" id="businessNameRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="tradeNameRenewal" class="block text-gray-700 text-sm font-bold mb-2">Trade Name / Franchise:</label>
          <input type="text" id="tradeNameRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="businessAddressRenewal" class="block text-gray-700 text-sm font-bold mb-2">Business Address:</label>
          <input type="text" id="businessAddressRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="postalCodeRenewal" class="block text-gray-700 text-sm font-bold mb-2">Postal Code:</label>
          <input type="text" id="postalCodeRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="telephoneNoRenewal" class="block text-gray-700 text-sm font-bold mb-2">Telephone No.:</label>
          <input type="text" id="telephoneNoRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="emailAddressRenewal" class="block text-gray-700 text-sm font-bold mb-2">Email Address:</label>
          <input type="email" id="emailAddressRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="mobileNoRenewal" class="block text-gray-700 text-sm font-bold mb-2">Mobile No.:</label>
          <input type="tel" id="mobileNoRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
      </div>
      <!-- Column 2 -->
      <div>
        <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Name of Taxpayer / Registrant:</label>
        <input type="text" id="middleNameRenewal" placeholder="Middle Name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
      </div>
        <div class="mb-4">
          <label for="ownerAddressRenewal" class="block text-gray-700 text-sm font-bold mb-2">Owner's Address:</label>
          <input type="text" id="ownerAddressRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="ownerPostalCodeRenewal" class="block text-gray-700 text-sm font-bold mb-2">Postal Code:</label>
          <input type="text" id="ownerPostalCodeRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="ownerTelephoneNoRenewal" class="block text-gray-700 text-sm font-bold mb-2">Telephone No.:</label>
          <input type="text" id="ownerTelephoneNoRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="ownerEmailAddressRenewal" class="block text-gray-700 text-sm font-bold mb-2">Email Address:</label>
          <input type="email" id="ownerEmailAddressRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="ownerMobileNoRenewal" class="block text-gray-700 text-sm font-bold mb-2">Mobile No.:</label>
          <input type="tel" id="ownerMobileNoRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="emergencyContactNameRenewal" class="block text-gray-700 text-sm font-bold mb-2">In case of emergency, provide name of contact person:</label>
          <input type="text" id="emergencyContactNameRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="emergencyContactPhoneRenewal" class="block text-gray-700 text-sm font-bold mb-2">Telephone / Mobile No.:</label>
          <input type="text" id="emergencyContactPhoneRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="emergencyContactEmailRenewal" class="block text-gray-700 text-sm font-bold mb-2">Email Address:</label>
          <input type="email" id="emergencyContactEmailRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="businessAreaRenewal" class="block text-gray-700 text-sm font-bold mb-2">Business Area (in sq. m.):</label>
          <input type="number" id="businessAreaRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Total No. of Employees in Establishment:</label>
          <input type="number" id="totalEmployeesRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Male:</label>
          <input type="number" id="maleEmployeesRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Female:</label>
          <input type="number" id="femaleEmployeesRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="lguEmployeesRenewal" class="block text-gray-700 text-sm font-bold mb-2">No. of Employees Residing within LGU:</label>
          <input type="number" id="lguEmployeesRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Note: Fill Up Only If Business Place is Rented:</label>
          <input type="text" id="lessorFullNameRenewal" placeholder="Lessor's Full Name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
          
        </div>
    
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">3. BUSINESS ACTIVITY</label>
          <input type="text" id="lineOfBusinessRenewal" placeholder="Line of Business" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
          
        </div>
    
        <div class="mb-4">
          <label for="grossSalesReceiptRenewal" class="block text-gray-700 text-sm font-bold mb-2">Gross/Sales Receipt (for Renewal):</label>
          <input type="number" id="grossSalesReceiptRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="essentialRenewal" class="block text-gray-700 text-sm font-bold mb-2">Essential:</label>
          <input type="number" id="essentialRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="nonEssentialRenewal" class="block text-gray-700 text-sm font-bold mb-2">Non-Essential:</label>
          <input type="number" id="nonEssentialRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="applicantSignatureRenewal" class="block text-gray-700 text-sm font-bold mb-2">SIGNATURE OF APPLICANT/TAXPAYER OVER PRINTED NAME:</label>
          <input type="file" id="applicantSignatureRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    
        <div class="mb-4">
          <label for="positionTitleRenewal" class="block text-gray-700 text-sm font-bold mb-2">POSITION / TITLE:</label>
          <input type="text" id="positionTitleRenewal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
      </div>
      
  <!-- Submit Button -->
  <button id="submitApplicationBtnRenewal" class="bg-primary text-white px-4 py-2 rounded-button hover:bg-secondary transition-colors duration-300">Submit Application</button>

  <!-- First Confirmation Modal -->
  <div id="confirmationModalRenewal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center z-50">
  <div class="bg-white p-5 rounded-lg shadow-md max-w-md w-full text-center">
      <h2 class="text-lg font-semibold mb-4">Confirmation</h2>
      <p class="mb-4">Are you sure that all inputs are correct?</p>
      <div class="flex justify-end">
          <button id="reviewBtnRenewal" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-button hover:bg-gray-400 mr-2">Review</button>
          <button id="confirmSubmitBtnRenewal" class="bg-primary text-white px-4 py-2 rounded-button hover:bg-secondary">Submit</button>
      </div>
  </div>
  </div>

  <!-- Success Notification Modal (Application Submitted) -->
  <div id="successModalRenewal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center z-50">
  <div class="bg-white p-6 rounded-lg shadow-md max-w-md w-full text-center">
      <h2 class="text-xl font-semibold mb-4">Application Submitted</h2>
      <p class="mb-6">Please wait for the approval, we will contact you shortly.</p>
      <button id="okayBtnRenewal" class="bg-primary text-white px-4 py-2 rounded-button hover:bg-secondary">Okay</button>
  </div>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", function () {
  const submitApplicationBtnRenewal = document.getElementById("submitApplicationBtnRenewal");
  const confirmationModalRenewal = document.getElementById("confirmationModalRenewal");
  const reviewBtnRenewal = document.getElementById("reviewBtnRenewal");
  const confirmSubmitBtnRenewal = document.getElementById("confirmSubmitBtnRenewal");
  const successModalRenewal = document.getElementById("successModalRenewal");
  const okayBtnRenewal = document.getElementById("okayBtnRenewal");

  submitApplicationBtnRenewal.addEventListener("click", function (e) {
      e.preventDefault();
      confirmationModalRenewal.classList.remove("hidden");
      confirmationModalRenewal.classList.add("flex");
  });

  reviewBtnRenewal.addEventListener("click", function () {
      confirmationModalRenewal.classList.add("hidden");
      confirmationModalRenewal.classList.remove("flex");
  });

  confirmSubmitBtnRenewal.addEventListener("click", function () {
      confirmationModalRenewal.classList.add("hidden");
      confirmationModalRenewal.classList.remove("flex");

      successModalRenewal.classList.remove("hidden");
      successModalRenewal.classList.add("flex");

      // Simulate clicking the Okay button after a delay
      setTimeout(function() {
          okayBtnRenewal.click();
      }, 2000); // Adjust the delay as needed (e.g., 2000ms for 2 seconds)
  });

  okayBtnRenewal.addEventListener("click", function () {
      window.location.href = "index.php";
  });
  });
  </script>
      <button class="bg-gray-400 text-white px-4 py-2 rounded-button hover:bg-gray-500 transition-colors duration-300 ml-2">Discard</button>
    </form>
  </section>
      </main>

      <footer class="bg-primary text-white mt-10 px-4 py-6">
        <div
          class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0"
        >
          <h1 class="font-['Pacifico'] text-lg">
            Taste<span class="text-white font-light">Libmanan</span>
          </h1>
          <div class="flex space-x-4 text-sm">
            <a href="#" class="hover:underline">About</a>
            <a href="#" class="hover:underline">Contact</a>
            <a href="#" class="hover:underline">Privacy Policy</a>
          </div>
          <p class="text-xs text-gray-200">
            &copy; 2025 TasteLibmanan. All rights reserved.
          </p>
        </div>
      </footer>

      <!-- Login Modal -->
      <div id="login-modal" class="login-modal">
        <div class="modal-content p-5">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Login</h2>
            <button
              class="close-modal w-8 h-8 flex items-center justify-center cursor-pointer"
            >
              <i class="ri-close-line ri-lg"></i>
            </button>
          </div>
          <form id="login-form">
            <div class="mb-4">
              <label
                for="login-email"
                class="block text-sm font-medium text-gray-700 mb-1"
                >Email</label
              >
              <input
                type="email"
                id="login-email"
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
                placeholder="Enter your email"
                required
              />
            </div>
            <div class="mb-4">
              <label
                for="login-password"
                class="block text-sm font-medium text-gray-700 mb-1"
                >Password</label
              >
              <input
                type="password"
                id="login-password"
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
                placeholder="Enter your password"
                required
              />
            </div>
            <div class="flex justify-between items-center mb-4">
              <div class="flex items-center">
                <input type="checkbox" id="remember-me" class="mr-2" />
                <label for="remember-me" class="text-sm text-gray-600"
                  >Remember me</label
                >
              </div>
              <a href="#" class="text-sm text-primary">Forgot password?</a>
            </div>
            <button
              type="submit"
              class="w-full bg-primary text-white py-2 rounded-button font-medium cursor-pointer"
            >
              Login
            </button>
            <div class="mt-4 text-center">
              <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="#" id="switch-to-register" class="text-primary"
                  >Create Account</a
                >
              </p>
            </div>
          </form>
        </div>
      </div>
      <!-- Register Modal -->
      <div id="register-modal" class="register-modal">
        <div class="modal-content p-5">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Create Account</h2>
            <button
              class="close-modal w-8 h-8 flex items-center justify-center cursor-pointer"
            >
              <i class="ri-close-line ri-lg"></i>
            </button>
          </div>
          <form id="register-form">
            <div class="mb-4">
              <label
                for="register-name"
                class="block text-sm font-medium text-gray-700 mb-1"
                >Full Name</label
              >
              <input
                type="text"
                id="register-name"
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
                placeholder="Enter your full name"
                required
              />
            </div>
            <div class="mb-4">
              <label
                for="register-email"
                class="block text-sm font-medium text-gray-700 mb-1"
                >Email</label
              >
              <input
                type="email"
                id="register-email"
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
                placeholder="Enter your email"
                required
              />
            </div>
            <div class="mb-4">
              <label
                for="register-phone"
                class="block text-sm font-medium text-gray-700 mb-1"
                >Phone Number (Optional)</label
              >
              <input
                type="tel"
                id="register-phone"
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
                placeholder="Enter your phone number"
              />
            </div>
            <div class="mb-4">
              <label
                for="register-password"
                class="block text-sm font-medium text-gray-700 mb-1"
                >Password</label
              >
              <input
                type="password"
                id="register-password"
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
                placeholder="Create a password"
                required
              />
            </div>
            <div class="mb-4">
              <label
                for="register-confirm-password"
                class="block text-sm font-medium text-gray-700 mb-1"
                >Confirm Password</label
              >
              <input
                type="password"
                id="register-confirm-password"
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
                placeholder="Confirm your password"
                required
              />
            </div>
            <div class="mb-4">
              <div class="flex items-start">
                <input type="checkbox" id="terms" class="mt-1 mr-2" required />
                <label for="terms" class="text-sm text-gray-600"
                  >I agree to the
                  <a href="#" class="text-primary">Terms of Service</a> and
                  <a href="#" class="text-primary">Privacy Policy</a></label
                >
              </div>
            </div>
            <button
              type="submit"
              class="w-full bg-primary text-white py-2 rounded-button font-medium cursor-pointer"
            >
              Create Account
            </button>
            <div class="mt-4 text-center">
              <p class="text-sm text-gray-600">
                Already have an account?
                <a href="#" id="switch-to-login" class="text-primary">Login</a>
              </p>
            </div>
          </form>
        </div>
      </div>

      <script>
        document.addEventListener("DOMContentLoaded", function () {
          // Modal elements
          const loginModal = document.getElementById("login-modal");
          const registerModal = document.getElementById("register-modal");
          const loginBtns = [document.getElementById("login-btn")];
          const registerBtns = [document.getElementById("register-btn")];
          const closeModalBtns = document.querySelectorAll(".close-modal");
          const switchToRegister = document.getElementById("switch-to-register");
          const switchToLogin = document.getElementById("switch-to-login");
          // Login form
          const loginForm = document.getElementById("login-form");
          // Register form
          const registerForm = document.getElementById("register-form");

          // Open login modal
          loginBtns.forEach((btn) => {
            btn.addEventListener("click", function () {
              loginModal.style.display = "flex";
            });
          });

          // Open register modal
          registerBtns.forEach((btn) => {
            btn.addEventListener("click", function () {
              registerModal.style.display = "flex";
            });
          });

          // Close modals
          closeModalBtns.forEach((btn) => {
            btn.addEventListener("click", function () {
              loginModal.style.display = "none";
              registerModal.style.display = "none";
            });
          });

          // Switch between modals
          switchToRegister.addEventListener("click", function (e) {
            e.preventDefault();
            loginModal.style.display = "none";
            registerModal.style.display = "flex";
          });

          switchToLogin.addEventListener("click", function (e) {
            e.preventDefault();
            registerModal.style.display = "none";
            loginModal.style.display = "flex";
          });

          // Close modal when clicking outside
          window.addEventListener("click", function (e) {
            if (e.target === loginModal) {
              loginModal.style.display = "none";
            }
            if (e.target === registerModal) {
              registerModal.style.display = "none";
            }
          });

          // Handle login form submission
          loginForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const email = document.getElementById("login-email").value;
            const password = document.getElementById("login-password").value;
            // Here you would normally send the login data to your server
            console.log("Login attempt:", { email, password });

            // For demo purposes, show success message
            const successModal = document.createElement("div");
            successModal.className =
              "fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50";
            successModal.innerHTML = `
                <div class="bg-white p-5 rounded-lg max-w-xs w-full text-center">
                  <div class="w-16 h-16 mx-auto flex items-center justify-center bg-green-100 rounded-full text-green-500 mb-4">
                    <i class="ri-check-line ri-2x"></i>
                  </div>
                  <h3 class="text-lg font-semibold mb-2">Login Successful!</h3>
                  <p class="text-gray-600 mb-4">Welcome back to Libmanan Food.</p>
                  <button class="w-full bg-primary text-white py-2 rounded-button">Continue</button>
                </div>
              `;
            document.body.appendChild(successModal);

            // Redirect to user.html after 2 seconds (simulate successful login)
            setTimeout(() => {
              document.body.removeChild(successModal);
              loginModal.style.display = "none";
              window.location.href = "tastelibmanan/tastelibmanan/users/user.php"; // Redirect to user page
            }, 2000);
          });

          // Handle register form submission
          registerForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const name = document.getElementById("register-name").value;
            const email = document.getElementById("register-email").value;
            const phone = document.getElementById("register-phone").value;
            const password = document.getElementById("register-password").value;
            const confirmPassword = document.getElementById(
              "register-confirm-password"
            ).value;
            // Here you would normally validate and send the registration data to your server
            console.log("Registration attempt:", { name, email, phone, password });

            // For demo purposes, show success message
            const successModal = document.createElement("div");
            successModal.className =
              "fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50";
            successModal.innerHTML = `
                <div class="bg-white p-5 rounded-lg max-w-xs w-full text-center">
                  <div class="w-16 h-16 mx-auto flex items-center justify-center bg-green-100 rounded-full text-green-500 mb-4">
                    <i class="ri-check-line ri-2x"></i>
                  </div>
                  <h3 class="text-lg font-semibold mb-2">Registration Successful!</h3>
                  <p class="text-gray-600 mb-4">Welcome to Libmanan Food. Your account has been created.</p>
                  <button class="w-full bg-primary text-white py-2 rounded-button">Continue</button>
                </div>
              `;
            document.body.appendChild(successModal);

            // Close the success modal after 2 seconds and show the login modal again
            setTimeout(() => {
              document.body.removeChild(successModal);
              registerModal.style.display = "none"; // Close registration modal
              loginModal.style.display = "flex"; // Show login modal
            }, 2000);
          });

          // Show/Hide Application Forms

    const startNewApplicationBtn = document.getElementById("startNewApplication");
    const newApplicationFormSection = document.getElementById("newApplicationFormSection");
    const startRenewalApplicationBtn = document.getElementById("startRenewalApplication");
    const renewalApplicationForm = document.getElementById("renewalApplicationForm");

    startNewApplicationBtn.addEventListener("click", function (e) {
      e.preventDefault();
      newApplicationFormSection.style.display = "block";
      renewalApplicationForm.style.display = "none";
    });

    startRenewalApplicationBtn.addEventListener("click", function (e) {
      e.preventDefault();
      renewalApplicationForm.style.display = "block";
      newApplicationFormSection.style.display = "none";
    });
  });
      
      </script>
    </body>
  </html>