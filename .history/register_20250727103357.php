<?php
header('Content-Type: application/json');

// Include database connection
require_once 'db_con.php';

// Get data from the request
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received.']);
    exit;
}

// Validate data
if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
    exit;
}

// Sanitize data
$name = htmlspecialchars($data['name']);
$email = strtolower(filter_var($data['email'], FILTER_SANITIZE_EMAIL));$phone_number = htmlspecialchars($data['phone_number'] ?? ''); // Optional field
$password = $data['password'];
$user_type = htmlspecialchars($data['user_type']);

// Check if email already exists
$checkStmt = $conn->prepare("SELECT user_id FROM accounts WHERE email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'This email has already been used. Please try a new one.']);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert data into the database
try {
    $stmt = $conn->prepare("INSERT INTO accounts (name, email, phone_number, password, user_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone_number, $hashed_password, $user_type);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'User registered successfully!']);

} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $conn->error]);
}

$conn->close();
?>