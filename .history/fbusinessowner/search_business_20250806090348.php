<?php
require_once '../db_con.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if ($query !== '') {
    // Get all business names and info
    $stmt = $conn->prepare("SELECT fb_name, fb_address, fb_photo, user_id, fb_status FROM fb_owner");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        // Calculate similarity score
        $score = similar_text(strtolower($query), strtolower($row['fb_name']), $percent);
        if ($percent > 50) { // You can adjust this threshold
            $row['score'] = $percent;
            $results[] = $row;
        }
    }
    // Sort by score descending
    usort($results, function($a, $b) { return $b['score'] <=> $a['score']; });
}
echo json_encode($results);
?>