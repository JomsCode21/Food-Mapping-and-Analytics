<?php
require_once '../db_con.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if ($query !== '') {
    // Get all business names and info
    $stmt = $conn->prepare("SELECT fb_name, fb_address, fb_photo, user_id, fb_status FROM fb_owner");
     $q = strtolower(trim($query));
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
    $score = similar_text(strtolower($query), strtolower($row['fb_name']), $percent);
    // Show if fuzzy match OR substring match
    if (
        $percent > 5 || // lower fuzzy threshold
        strpos(strtolower($row['fb_name']), strtolower($query)) !== false // substring match
    ) {
        $row['score'] = $percent;
        $results[] = $row;
    }
}
    // Sort by score descending
    usort($results, function($a, $b) { return $b['score'] <=> $a['score']; });
}
echo json_encode($results);
?>