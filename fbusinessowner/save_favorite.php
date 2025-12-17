<?php
session_start();
require_once '../db_con.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$fbowner_id = $_POST['fbowner_id'];

if (!$fbowner_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing business id']);
    exit;
}

// Get current favorites
$stmt = $conn->prepare("SELECT user_favorites FROM accounts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($favorites_json);
$stmt->fetch();
$stmt->close();

$favorites = $favorites_json ? json_decode($favorites_json, true) : [];
if (!in_array($fbowner_id, $favorites)) {
    $favorites[] = $fbowner_id;
}

// Save back to DB
$new_json = json_encode($favorites);
$stmt = $conn->prepare("UPDATE accounts SET user_favorites = ? WHERE user_id = ?");
$stmt->bind_param("si", $new_json, $user_id);
$stmt->execute();
$stmt->close();

echo json_encode(['success' => true, 'favorites' => $favorites]);
?>