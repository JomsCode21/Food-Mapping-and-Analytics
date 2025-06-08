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
$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$phone_number = htmlspecialchars($data['phone_number'] ?? ''); // Optional field
$password = $data['password'];
$user_type = htmlspecialchars($data['user_type']);

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert data into the database
try {
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone_number, password, user_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone_number, $hashed_password, $user_type);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'User registered successfully!']);

} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $conn->error]);
}

$conn->close();
?>