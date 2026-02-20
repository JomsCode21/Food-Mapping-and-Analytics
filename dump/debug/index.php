<?php
require_once __DIR__ . '/../../env.php';

$db_host = env_value('DB_HOST', 'localhost');
$db_name = env_value('DB_NAME', 'tastelibmanan');
$db_user = env_value('DB_USER', 'root');
$db_pass = env_value('DB_PASS', '');
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadedFiles = $_FILES['files'];
    $fileData = [];

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Process each uploaded file
    foreach ($uploadedFiles['name'] as $index => $name) {
        $tmpName = $uploadedFiles['tmp_name'][$index];
        $type = $uploadedFiles['type'][$index];
        $size = $uploadedFiles['size'][$index];

        // Create unique filename to avoid overwrite
        $uniqueName = uniqid() . "_" . basename($name);
        $targetPath = $uploadDir . $uniqueName;

        if (move_uploaded_file($tmpName, $targetPath)) {
            $fileData[] = [
                'name' => $uniqueName,
                'type' => $type,
                'size' => $size,
                'path' => $targetPath
            ];
        }
    }

    // Store in database
    if (!empty($fileData)) {
        $jsonFiles = json_encode($fileData);

        $pdo = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
        $stmt = $pdo->prepare("INSERT INTO test_galleries (files_json) VALUES (:files_json)");
        $stmt->execute(['files_json' => $jsonFiles]);

        header("Location: index.php"); // Redirect to avoid resubmission
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Multi File Upload</title>
</head>
<body>
    <h2>Upload Multiple Files</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="files[]" multiple>
        <button type="submit">Upload</button>
    </form>

    <hr>

    <h3>Uploaded Files:</h3>
    <?php
    // Display all uploaded files
    $pdo = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
    $stmt = $pdo->query("SELECT * FROM test_galleries ORDER BY created_at DESC");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $files = json_decode($row['files_json'], true);
        echo "<ul>";
        foreach ($files as $file) {
            echo "<li>";
            if (str_starts_with($file['type'], 'image/')) {
                echo "<img src='{$file['path']}' width='100' alt='Uploaded Image'><br>";
            }
            echo "Name: {$file['name']}<br>";
            echo "Type: {$file['type']}<br>";
            echo "Size: " . round($file['size'] / 1024, 2) . " KB<br>";
            echo "<a href='{$file['path']}' download>Download</a>";
            echo "</li><hr>";
        }
        echo "</ul>";
    }
    ?>
</body>
</html>
