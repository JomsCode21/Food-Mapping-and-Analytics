<?php
require_once '../db_con.php';

$data = json_decode(file_get_contents('php://input'), true);
$business_name = $data['business_name'] ?? '';

if ($business_name) {
    // Update the status to 'approved'
    $stmt = $conn->prepare("UPDATE business_applications SET status='approved' WHERE business_name=? AND status='pending'");
    $stmt->bind_param("s", $business_name);
    $success = $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false]);
}
?>