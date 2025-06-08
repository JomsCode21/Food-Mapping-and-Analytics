<?php
header('Content-Type: application/json');
session_start(); // Start the session

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
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$password = $data['password'];

// Retrieve the user's record from the database based on the email
$stmt = $conn->prepare("SELECT user_id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $stored_hash = $user['password'];

    // Verify the password
    if (password_verify($password, $stored_hash)) {
        // Passwords match! Login successful
        $_SESSION['user_id'] = $user['user_id']; // Store user ID in session
        echo json_encode(['success' => true, 'message' => 'Login successful!']);
    } else {
        // Passwords do not match
        echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
    }
} else {
    // User not found
    echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
}

$conn->close();
?>