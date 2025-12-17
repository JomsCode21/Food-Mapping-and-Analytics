<?php
require_once '../db_con.php';
$data = json_decode(file_get_contents('php://input'), true);
$business_name = $data['business_name'] ?? '';
if ($business_name) {
    $stmt = $conn->prepare("DELETE FROM business_applications WHERE business_name=? AND status='rejected'");
    $stmt->bind_param("s", $business_name);
    $success = $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false]);
}
?>