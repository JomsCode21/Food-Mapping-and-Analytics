<?php
session_start();
require_once '../db_con.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Prepare the query to fetch the menu_images column
$stmt = $conn->prepare("SELECT menu_images FROM fb_owner WHERE user_id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $json_data = $row['menu_images'];
        
        // Decode JSON to ensure it is valid, then output it
        if (!empty($json_data)) {
            $data = json_decode($json_data, true);
            // Return empty array if decode failed, otherwise return the data
            echo json_encode(is_array($data) ? $data : []);
        } else {
            echo json_encode([]); // Column is empty
        }
    } else {
        echo json_encode([]); // No user found
    }
} else {
    echo json_encode([]); // Query failed
}

$stmt->close();
?>