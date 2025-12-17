<?php
require_once '../db_con.php';
$fbowner_id = $_GET['fbowner_id'] ?? 0;

$stmt = $conn->prepare("SELECT menu_price FROM menus WHERE fbowner_id = ?");
$stmt->bind_param("i", $fbowner_id);
$stmt->execute();
$result = $stmt->get_result();

$menus = [];
while($row = $result->fetch_assoc()){
    $menus[] = $row;
}

$stmt->close();
echo json_encode($menus);
