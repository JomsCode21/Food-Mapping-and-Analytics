<?php
// status_logic.php
date_default_timezone_set('Asia/Manila');

function checkAndSyncBusinessStatus($conn, $user_id) {
    // 1. Fetch current data
    $stmt = $conn->prepare("SELECT fb_status, fb_operating_hours, status_last_updated FROM fb_owner WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $db_status = $row['fb_status'];
        $operating_hours_str = $row['fb_operating_hours'];
        $last_updated = $row['status_last_updated'];

        // If no hours set, just return current status
        if (empty($operating_hours_str)) return $db_status;

        // Parse "8:00 AM - 5:00 PM"
        $times = explode('-', $operating_hours_str);
        if (count($times) !== 2) return $db_status; // Invalid format

        $open_time_str = trim($times[0]);
        $close_time_str = trim($times[1]);

        $now = time();
        $today_open = strtotime($open_time_str);
        $today_close = strtotime($close_time_str);
        $last_manual_change = strtotime($last_updated);

        $final_status = $db_status; // Default assumption

        // LOGIC START
        
        // A. OUTSIDE OPERATING HOURS
        if ($now < $today_open || $now > $today_close) {
            // Force CLOSE
            $final_status = 'closed';
        } 
        // B. INSIDE OPERATING HOURS
        else {
            // Check manual override timestamp
            // If the last manual change was BEFORE today's opening time, it's a fresh day -> RESET to OPEN
            if ($last_manual_change < $today_open) {
                $final_status = 'open';
            } 
            // If manual change was TODAY (after opening), we do nothing. 
            // We respect the database status (which the user set to 'closed' manually).
        }

        // C. UPDATE DATABASE IF NEEDED
        if ($final_status !== $db_status) {
            // Important: We do NOT update 'status_last_updated' here. 
            // We only update that column when a human clicks the button.
            $update_stmt = $conn->prepare("UPDATE fb_owner SET fb_status = ? WHERE user_id = ?");
            $update_stmt->bind_param("si", $final_status, $user_id);
            $update_stmt->execute();
            $update_stmt->close();
        }

        return $final_status;
    }
    return 'closed'; // Fallback
}
?>