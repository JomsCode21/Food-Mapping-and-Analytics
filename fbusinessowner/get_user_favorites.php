<?php
session_start();
require_once '../db_con.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT user_favorites FROM accounts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($favorites_json);
$stmt->fetch();
$stmt->close();

$favorites = $favorites_json ? json_decode($favorites_json, true) : [];

if (count($favorites) > 0) {
    $in = implode(',', array_fill(0, count($favorites), '?'));
    $types = str_repeat('i', count($favorites));
    $sql = "SELECT fbowner_id, fb_name, fb_address, fb_photo FROM fb_owner WHERE fbowner_id IN ($in)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$favorites);
    $stmt->execute();
    $result = $stmt->get_result();
    $businesses = [];
    while ($row = $result->fetch_assoc()) {
        $businesses[] = $row;
    }
    $stmt->close();
    echo json_encode($businesses);
} else {
    echo json_encode([]);
}
?>