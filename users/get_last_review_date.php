<?php
include '../db_con.php'; // adjust path to your DB connection

$fbowner_id = $_GET['fbowner_id'] ?? null;
$response = ['status' => 'error'];

if ($fbowner_id) {
    // Get last review date
    $stmt = $conn->prepare("SELECT created_at FROM reviews WHERE fbowner_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("i", $fbowner_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Get existing summary and last summarize date
    $stmt2 = $conn->prepare("SELECT summarize_review, last_summarize_date FROM fb_owner WHERE fbowner_id = ?");
    $stmt2->bind_param("i", $fbowner_id);
    $stmt2->execute();
    $summaryRow = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();

    $response = [
        'status' => 'success',
        'last_review_date' => $result['created_at'] ?? null,
        'summarize_review' => $summaryRow['summarize_review'] ?? '',
        'last_summarize_date' => $summaryRow['last_summarize_date'] ?? null
    ];
} else {
    $response['message'] = 'fbowner_id is missing.';
}

echo json_encode($response);
?>
