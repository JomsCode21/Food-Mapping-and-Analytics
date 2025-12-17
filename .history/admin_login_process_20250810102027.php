<?php
header('Content-Type: application/json');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once 'db_con.php';

// Get data from the request
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received.']);
    exit;
}

// Validate data
if (empty($data['email']) || empty($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Please enter your email and password.']);
    exit;
}

$email = trim($data['email']); // Trim whitespace
$password = $data['password'];

// Sanitize input
$email = mysqli_real_escape_string($conn, $email);

// Query the database to check if the admin exists
//TEMPORARY CHANGE - QUERY BY admin_id
$stmt = $conn->prepare("SELECT admin_id, admin_username, admin_password FROM admins WHERE admin_id = 1");  //Hardcoded admin_id = 1
//$stmt = $conn->prepare("SELECT admin_id, admin_username, admin_password FROM admins WHERE admin_username = ?");
//$stmt->bind_param("s", $email);

if (!$stmt->execute()) {
    error_log("admin_login_process.php: Execute failed: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    exit;
}

$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $admin = $result->fetch_assoc();

    // Debugging: Inspect the values
    error_log("Email: " . $email);
    error_log("Password: " . $password);
    error_log("Hashed Password from DB: " . $admin['admin_password']);

    // Verify the password using password_verify()
    if (password_verify($password, $admin['admin_password'])) {
        // Admin login successful
        // Set session variables
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_username'] = $admin['admin_username'];
        $_SESSION['admin_logged_in'] = true;

        echo json_encode(['success' => true, 'message' => 'Login successful. Redirecting...']);
    } else {
        // Incorrect password
        error_log("Password verification failed.");
        echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
    }
} else {
    // Admin not found
    error_log("Admin not found.");
    echo json_encode(['success' => false, 'message' => 'Admin not found.']);
}

$conn->close();
?>