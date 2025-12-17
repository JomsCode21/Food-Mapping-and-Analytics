<?php
header('Content-Type: application/json');
include '../db_con.php'; // adjust the path if needed

// Query to count businesses by barangay
$query = "
  SELECT 
    TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(fb_address, ',', 2), ',', -1)) AS businessBarangay,
    COUNT(*) AS total
  FROM fb_owner
  WHERE fb_address IS NOT NULL AND fb_address != ''
  GROUP BY businessBarangay
  ORDER BY total DESC
";

$result = mysqli_query($conn, $query);

$barangays = [];
$total = [];

while ($row = mysqli_fetch_assoc($result)) {
  $barangays[] = html_entity_decode($row['businessBarangay'], ENT_QUOTES);
  $total[] = (int)$row['total'];
}

echo json_encode(['barangays' => $barangays, 'total' => $total]);
?>
