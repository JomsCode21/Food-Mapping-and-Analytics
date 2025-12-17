<?php
require_once '../db_con.php';

function normalize($str) {
    return preg_replace('/\s+/', ' ', trim(mb_strtolower($str, 'UTF-8')));
}

$query = isset($_GET['q']) ? normalize($_GET['q']) : '';
$results = [];

if ($query !== '') {
    $stmt = $conn->prepare("SELECT fbowner_id, user_id, fb_name, fb_address, fb_photo, fb_status FROM fb_owner");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $fbName = normalize($row['fb_name']);
        $score = similar_text($query, $fbName, $percent);

        if ($percent > 5 || strpos($fbName, $query) !== false) {
            $row['score'] = $percent;
            $results[] = $row;
        }
    }

    usort($results, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });
}

echo json_encode($results);
?>
