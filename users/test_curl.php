<?php
$url = "https://api-inference.huggingface.co/";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if ($response === false) {
    echo "❌ cURL error: " . curl_error($ch);
} else {
    echo "✅ cURL works!";
}
curl_close($ch);
?>
