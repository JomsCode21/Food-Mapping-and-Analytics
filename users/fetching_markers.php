<?php
require_once '../db_con.php';
require_once '../status_logic.php';

// Set header to JSON
header('Content-Type: application/json');

// Query to fetch latest status and details
$sql = "SELECT fbowner_id, user_id, fb_name, fb_type, fb_description, fb_operating_hours, fb_photo, fb_latitude, fb_longitude, fb_status 
        FROM fb_owner 
        WHERE activation = 'Active'";

$result = $conn->query($sql);

$businesses = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // 1. Calculate the real-time status (and update DB if needed)
        $real_time_status = checkAndSyncBusinessStatus($conn, $row['user_id']);
        
        // 2. IMPORTANT: Update the row array with the NEW status before sending it
        $row['fb_status'] = $real_time_status; 
        
        $businesses[] = $row;
    }
}

// Return data as JSON
echo json_encode($businesses);

$conn->close();
?>