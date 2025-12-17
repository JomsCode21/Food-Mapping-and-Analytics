<?php
require_once '../db_con.php'; // Adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_id = $_POST['menu_id'];
    $status = $_POST['is_available']; // 1 or 0

    $stmt = $conn->prepare("UPDATE menus SET is_available = ? WHERE menu_id = ?");
    $stmt->bind_param("ii", $status, $menu_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    $stmt->close();
}
?>