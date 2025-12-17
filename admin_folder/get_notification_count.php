<?php
require_once '../db_con.php';
$result = $conn->query("SELECT COUNT(*) AS cnt FROM notification WHERE is_read = 0");
$count = ($result && $row = $result->fetch_assoc()) ? $row['cnt'] : 0;
echo $count;
?>