<?php
require 'db_con.php';

$uploaded_files = [];
$upload_dir = 'uploads/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (!empty($_FILES['images'])) {
    foreach ($_FILES['images']['tmp_name'] as $index => $tmp_name) {
        $original_name = basename($_FILES['images']['name'][$index]);
        $ext = pathinfo($original_name, PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $destination = $upload_dir . $filename;

        if (move_uploaded_file($tmp_name, $destination)) {
            $uploaded_files[] = $destination;
        }
    }

    // Store JSON in database
    if (!empty($uploaded_files)) {
        $json = json_encode($uploaded_files);
        $stmt = $pdo->prepare("INSERT INTO tes_galleries (image_paths) VALUES (:paths)");
        $stmt->execute(['paths' => $json]);
        echo "Uploaded and saved to DB.<br><a href='index.php'>Go Back</a>";
    } else {
        echo "Failed to upload files.";
    }
} else {
    echo "No files selected.";
}
?>
