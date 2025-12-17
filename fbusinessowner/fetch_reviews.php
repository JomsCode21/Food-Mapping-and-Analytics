<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/../db_con.php';
session_start();

$week = isset($_GET['week']) ? intval($_GET['week']) : 0;
$fbowner_id = isset($_GET['fbowner_id']) 
    ? intval($_GET['fbowner_id']) 
    : ($_SESSION['fbowner_id'] ?? 0);

// Removed duplicate check
if ($fbowner_id <= 0) {
    echo "<p>Invalid business owner.</p>";
    exit;
}

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');

if ($week < 1 || $week > 6) {
    echo "<p class='text-gray-500 italic'>Invalid week number.</p>";
    exit;
}

$sql = "
  SELECT reviewer_name, rating, comment, created_at, updated_at, photo, video
  FROM (
    SELECT 
        reviewer_name, rating, comment, photo, video, created_at, updated_at,
        CASE 
            WHEN updated_at IS NOT NULL AND updated_at != '0000-00-00 00:00:00' AND updated_at != created_at THEN updated_at 
            ELSE created_at 
        END as effective_date
    FROM reviews
    WHERE fbowner_id = ?
  ) AS t
  WHERE YEAR(effective_date) = ?
    AND MONTH(effective_date) = ?
    AND (WEEK(effective_date, 1) - WEEK(DATE_SUB(effective_date, INTERVAL DAYOFMONTH(effective_date)-1 DAY), 1) + 1) = ?
  ORDER BY effective_date DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "<p style='color:red'>Prepare failed: " . htmlspecialchars($conn->error) . "</p>";
    exit;
}

if (!$stmt->bind_param("iiii", $fbowner_id, $year, $month, $week)) {
    echo "<p style='color:red'>Bind failed: " . htmlspecialchars($stmt->error) . "</p>";
    exit;
}

if (!$stmt->execute()) {
    echo "<p style='color:red'>Execute failed: " . htmlspecialchars($stmt->error) . "</p>";
    exit;
}

$res = $stmt->get_result();
if ($res === false) {
    echo "<p style='color:red'>get_result failed: " . htmlspecialchars($stmt->error) . "</p>";
    exit;
}

if ($res->num_rows === 0) {
    echo "<p class='text-gray-500 italic'>No reviews for this week.</p>";
    exit;
}

echo "<div class='space-y-6'>";
while ($row = $res->fetch_assoc()) {
    $reviewer = htmlspecialchars($row['reviewer_name']);
    $comment = nl2br(htmlspecialchars($row['comment']));

    $displayRawDate = $row['created_at'];
    $editedText = "";

    if (!empty($row['updated_at']) && $row['updated_at'] != '0000-00-00 00:00:00' && $row['updated_at'] != $row['created_at']) {
        $displayRawDate = $row['updated_at'];
        $editedText = " (Edited)";
    }
    $date = date("M d, Y", strtotime($displayRawDate)) . $editedText;

    $rating = (int)$row['rating'];
    $stars = str_repeat("★", $rating) . str_repeat("☆", 5 - $rating);

    $media_html = '';
    
    // For photo
    if (!empty($row['photo'])) {
        $photo_path = '../' . ltrim($row['photo'], './'); 
        $media_html .= "
        <div class='mt-3'>
            <img src='{$photo_path}' 
                 onclick=\"openMediaModal('{$photo_path}', 'image')\"
                 class='w-full h-48 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition shadow-sm' 
                 alt='Review Photo'>
        </div>";
    }

    // For video
    if (!empty($row['video'])) {
        $video_path = '../' . ltrim($row['video'], './');
        $ext = strtolower(pathinfo($video_path, PATHINFO_EXTENSION));
        
        $media_html .= "
        <div class='mt-3'>
            <video controls class='w-full h-48 object-cover rounded-lg border border-gray-200 bg-black'>
                <source src='{$video_path}' type='video/{$ext}'>
                Your browser does not support the video tag.
            </video>
        </div>";
    }
    
    echo "
    <div class='border-b pb-4'>
      <div class='flex items-center mb-2'>
        <span class='font-semibold mr-2 text-gray-800'>{$reviewer}</span>
        <span class='text-yellow-500 tracking-wide'>{$stars}</span>
        <span class='ml-auto text-xs text-gray-400'>{$date}</span>
      </div>
      
      <p class='text-gray-600 text-sm leading-relaxed'>{$comment}</p>
      
      {$media_html}
    </div>";
}
echo "</div>";

$stmt->close();
?>