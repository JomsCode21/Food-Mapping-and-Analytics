<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
session_start();

require_once 'db_con.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received.']);
    exit;
}

if (empty($data['email']) || empty($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Please enter your email and password.']);
    exit;
}

$email = trim($data['email']);
$password = $data['password'];

$stmt = $conn->prepare("SELECT user_id, email, password, user_type, email_verified FROM accounts WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($row['email_verified'] == 0) {
        echo json_encode(['success' => false, 'message' => 'Please verify your email before logging in.']);
        exit;
    }

    if (password_verify($password, $row['password'])) {
        // ✅ Store core session data
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['user_type'] = $row['user_type'];
        $_SESSION['email'] = $row['email']; // ✅ Added line for both users & fb_owners
        $_SESSION['logged_in'] = true;

        // ✅ If fb_owner, fetch fbowner_id & fb_email_address
        if ($row['user_type'] === 'fb_owner') {
            $stmt2 = $conn->prepare("SELECT fbowner_id, fb_email_address FROM fb_owner WHERE user_id = ?");
            $stmt2->bind_param("i", $row['user_id']);
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            if ($owner = $res2->fetch_assoc()) {
                $_SESSION['fbowner_id'] = $owner['fbowner_id'];
                $_SESSION['fb_email_address'] = $owner['fb_email_address'];
            }
            $stmt2->close();
        }

        // ✅ Response
        $redirect = isset($data['redirect']) ? $data['redirect'] : '';
        echo json_encode([
            "success" => true,
            "user_type" => $row['user_type'],
            "redirect" => $redirect,
            "fbowner_id" => $_SESSION['fbowner_id'] ?? null
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Incorrect Password."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "User not found."
    ]);
}

$stmt->close();
$conn->close();
?>
