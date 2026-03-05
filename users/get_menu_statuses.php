<?php
require_once '../db_con.php';
header('Content-Type: application/json');

$fbowner_id = isset($_GET['fbowner_id']) ? intval($_GET['fbowner_id']) : 0;

if ($fbowner_id <= 0) {
    echo json_encode(['statuses' => []]);
    exit;
}

$stmt = $conn->prepare('SELECT menu_id, is_available FROM menus WHERE fbowner_id = ?');
$stmt->bind_param('i', $fbowner_id);
$stmt->execute();
$result = $stmt->get_result();

$statuses = [];
while ($row = $result->fetch_assoc()) {
    $statuses[] = [
        'menu_id' => (int)$row['menu_id'],
        'is_available' => isset($row['is_available']) ? (int)$row['is_available'] : 1,
    ];
}

$stmt->close();
echo json_encode(['statuses' => $statuses]);
?>