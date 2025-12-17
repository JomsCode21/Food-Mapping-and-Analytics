<?php
session_start();
require_once '../db_con.php';
header('Content-Type: application/json');

if (!isset($_SESSION['fbowner_id'])) {
  echo json_encode(['error' => 'Not logged in as business owner']);
  exit;
}

$fbowner_id = intval($_SESSION['fbowner_id']); // correct session variable

$query = "
  SELECT m.menu_id, m.menu_name, m.menu_price, m.menu_image, m.is_available,
         c.category_name, c.category_id
  FROM menus m
  LEFT JOIN menu_categories c ON m.category_id = c.category_id
  WHERE m.fbowner_id = ?
  ORDER BY m.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $fbowner_id);
$stmt->execute();
$result = $stmt->get_result();

$menus = [];
while ($row = $result->fetch_assoc()) {
  $menus[] = $row;
}

echo json_encode($menus);
