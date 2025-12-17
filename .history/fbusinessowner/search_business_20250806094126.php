<?php
require_once '../db_con.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

while ($row = $result->fetch_assoc()) {
    $name = strtolower(trim($row['fb_name']));
    $q = strtolower(trim($query));
    $score = similar_text($q, $name, $percent);
    // Show if fuzzy match OR substring match
    if (
        $percent > 10 ||
        strpos($name, $q) !== false
    ) {
        $row['score'] = $percent;
        $results[] = $row;
    }

    // Sort by score descending
    usort($results, function($a, $b) { return $b['score'] <=> $a['score']; });
}
echo json_encode($results);
?>