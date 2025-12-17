<?php
// fetch_summary_logic.php
require_once '../db_con.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0); // Keep JSON clean

$fbowner_id = isset($_POST['fbowner_id']) ? intval($_POST['fbowner_id']) : 0;
$rating_filter = isset($_POST['rating']) ? $_POST['rating'] : 'all'; 

if ($fbowner_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
    exit;
}

// FOLDER SETUP
$cache_dir = "summary_cache/"; 
if (!is_dir($cache_dir)) {
    @mkdir($cache_dir, 0777, true);
}
$cache_file = $cache_dir . "summary_cache_" . $fbowner_id . ".json";

// GET REVIEWS COUNT
$whereClause = "WHERE fbowner_id = ?";
$params = ["i", $fbowner_id];
$types = "i";

if ($rating_filter !== 'all') {
    $whereClause .= " AND rating = ?";
    $params[] = intval($rating_filter);
    $types .= "i";
}

// Count total reviews
$count_query = $conn->prepare("SELECT COUNT(*) as total FROM reviews $whereClause");
$count_query->bind_param($types, ...array_slice($params, 1));
$count_query->execute();
$current_count = intval($count_query->get_result()->fetch_assoc()['total']);
$count_query->close();

// CHECK CACHE
$cache_data = [];
$cached_summary = ($current_count > 0) ? "Summary temporarily unavailable." : "No reviews available for this rating.";
$last_count = 0;
$needs_update = true;

if (file_exists($cache_file)) {
    $json_content = file_get_contents($cache_file);
    $cache_data = json_decode($json_content, true) ?? [];
    
    if (isset($cache_data[$rating_filter])) {
        $cached_summary = $cache_data[$rating_filter]['summary'];
        $last_count = intval($cache_data[$rating_filter]['count']);
        
        // Only update if we have MORE reviews now than before
        if ($current_count <= $last_count) {
            $needs_update = false;
        }
    }
}

// GENERATE SUMMARY IF NEEDED
if ($current_count > 0 && $needs_update) {

    // Fetch review text (Limit to recent 30 to prevent timeouts)
    $text_query = $conn->prepare("SELECT comment FROM reviews $whereClause ORDER BY created_at DESC LIMIT 30");
    $text_query->bind_param($types, ...array_slice($params, 1));
    $text_query->execute();
    $text_result = $text_query->get_result();
    
    $reviews_text = "";
    while ($row = $text_result->fetch_assoc()) {
        // Sanitize
        $clean_comment = str_replace(["\r", "\n", "\"", "\\"], [" ", " ", "'", ""], $row['comment']);
        if (!empty(trim($clean_comment))) {
            $reviews_text .= $clean_comment . " . ";
        }
    }
    $text_query->close();

    // Only proceed if there is actual text
    if (!empty(trim($reviews_text))) {
        
        $final_prompt = "";
        if ($rating_filter === 'all') {
            $final_prompt = "Analyze these reviews and provide a short summary strictly separated into two sections: 'Advantages (Pros)' and 'Disadvantages (Cons)'. Keep it concise. Reviews: " . $reviews_text;
        } else {
            $final_prompt = "Summarize the key common points of these " . $rating_filter . "-star reviews in 3 sentences: " . $reviews_text;
        }

        // --- GOOGLE GEMINI API CALL (FREE TIER) ---
        
        // PASTE YOUR NEW GEMINI API KEY HERE
        $apiKey = 'AIzaSyDcyc26AngMYXcQ3x2W8_b-E2g1m1oGV64'; 
        
        $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;

        $apiData = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $final_prompt]
                    ]
                ]
            ]
        ];

        $ch = curl_init($apiUrl);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix for XAMPP

        $api_response = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if (!$curl_error) {
            $result = json_decode($api_response, true);
            
            // Check for success
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $new_summary = $result['candidates'][0]['content']['parts'][0]['text'];
                $new_summary = str_replace(['**', '*'], '', $new_summary); // Clean Markdown stars
                
                $cache_data[$rating_filter] = [
                    'count' => $current_count,
                    'summary' => $new_summary,
                    'last_updated' => date("Y-m-d H:i:s")
                ];
                
                file_put_contents($cache_file, json_encode($cache_data));
                
                echo json_encode(['status' => 'success', 'summary' => $new_summary, 'count' => $current_count]);
                exit;
            } 
            // ERROR HANDLING
            else {
                if (isset($result['error']['message'])) {
                    $cached_summary = "Gemini Error: " . $result['error']['message'];
                } else {
                    $cached_summary = "AI Summary unavailable. Recent feedback: " . substr($reviews_text, 0, 300) . "...";
                }
            }
        } else {
            $cached_summary = "Connection Error: " . $curl_error;
        }
    } else {
        $cached_summary = "There are ratings, but no written comments to summarize.";
    }
}

// FALLBACK
echo json_encode(['status' => 'success', 'summary' => $cached_summary, 'count' => ($current_count > 0 ? $current_count : 0)]);
?>
