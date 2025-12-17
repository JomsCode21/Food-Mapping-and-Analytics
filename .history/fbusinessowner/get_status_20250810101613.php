<?php
require_once '../db_con.php';
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$status = 'closed';
if ($user_id > 0) {
    $stmt = $conn->prepare("SELECT fb_status FROM fb_owner WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($fb_status);
    if ($stmt->fetch()) {
        $status = $fb_status;
    }
    $stmt->close();
}
echo json_encode(['status' => $status]);
?>