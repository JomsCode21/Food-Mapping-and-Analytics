<?php
// filepath: c:\xampp\htdocs\TASTELIBMANAN\owner_login_process.php
header('Content-Type: application/json');
session_start();

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

$email = trim($data['email']);
$password = $data['password'];

// Query the database to check if the owner exists
$stmt = $conn->prepare("SELECT user_id, password, user_type FROM accounts WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Verify the password using password_verify()
    if (password_verify($password, $row['password'])) {
        // Owner login successful
        // Set session variables
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['user_type'] = $row['user_type'];
        $_SESSION['logged_in'] = true;

        echo json_encode([
            "success" => true,
            "user_type" => $row['user_type']
        ]);
    } else {
        // Incorrect password
        echo json_encode([
            "success" => false,
            "message" => "Incorrect Password."
        ]);
    }
} else {
    // Owner not found
    echo json_encode([
        "success" => false,
        "message" => "User not found."
    ]);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$stmt->close();
$conn->close();
?>