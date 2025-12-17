<?php
session_start();
require_once '../db_con.php';

// 1. Check Auth
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

// 2. Validate Input
if (!isset($_POST['fbowner_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing ID']);
    exit();
}

$user_id = $_SESSION['user_id'];
$fbowner_id = strval($_POST['fbowner_id']); // Ensure it's a string for comparison

// 3. Get Current Favorites
$stmt = $conn->prepare("SELECT user_favorites FROM accounts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$current_favorites = $row['user_favorites'];

// Convert comma-separated string to Array (remove empty entries)
$fav_array = [];
if (!empty($current_favorites)) {
    $fav_array = explode(',', $current_favorites);
}

// 4. Toggle Logic
if (in_array($fbowner_id, $fav_array)) {
    // ID exists: REMOVE it
    $fav_array = array_diff($fav_array, [$fbowner_id]);
    $action = 'removed';
} else {
    // ID doesn't exist: ADD it
    $fav_array[] = $fbowner_id;
    $action = 'added';
}

// 5. Save back to Database
$new_favorites_string = implode(',', $fav_array);

$update = $conn->prepare("UPDATE accounts SET user_favorites = ? WHERE user_id = ?");
$update->bind_param("si", $new_favorites_string, $user_id);

if ($update->execute()) {
    echo json_encode(['status' => 'success', 'action' => $action, 'new_list' => $new_favorites_string]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
}

$conn->close();
?>