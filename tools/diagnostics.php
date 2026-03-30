<?php
/**
 * Database Diagnostic Tool
 * Check database connection, tables, and show any issues
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tastelibmanan - Database Diagnostics</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        h1 { color: #A80000; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #A80000;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn:hover { background: #8a0000; }
    </style>

    <link rel='stylesheet' href='../vendors/css/theme-toggle.css'/>
</head>
<body>
    <h1>🔍 Tastelibmanan Database Diagnostics</h1>
    
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Test 1: Environment file
    echo '<div class="card">';
    echo '<h2>1. Environment Configuration</h2>';
    if (file_exists(__DIR__ . '/../.env')) {
        echo '<p class="success">✓ .env file exists</p>';
        require_once __DIR__ . '/../env.php';
        $dbHost = env_value('DB_HOST');
        $dbName = env_value('DB_NAME');
        echo "<p>Database Host: <strong>$dbHost</strong></p>";
        echo "<p>Database Name: <strong>$dbName</strong></p>";
    } else {
        echo '<p class="error">✗ .env file missing</p>';
    }
    echo '</div>';
    
    // Test 2: Database connection
    echo '<div class="card">';
    echo '<h2>2. Database Connection</h2>';
    try {
        require_once __DIR__ . '/../db_con.php';
        if ($conn && $conn->ping()) {
            echo '<p class="success">✓ Database connection successful</p>';
            echo '<p>Server Version: ' . $conn->server_info . '</p>';
            
            // Test 3: Check required tables
            echo '</div><div class="card">';
            echo '<h2>3. Required Tables</h2>';
            
            $required_tables = ['accounts', 'fb_owner', 'business_applications', 'reviews'];
            echo '<table>';
            echo '<tr><th>Table Name</th><th>Status</th><th>Row Count</th></tr>';
            
            foreach ($required_tables as $table) {
                $result = $conn->query("SHOW TABLES LIKE '$table'");
                if ($result && $result->num_rows > 0) {
                    $count_result = $conn->query("SELECT COUNT(*) as count FROM $table");
                    $count = $count_result ? $count_result->fetch_assoc()['count'] : 0;
                    echo "<tr><td>$table</td><td class='success'>✓ Exists</td><td>$count rows</td></tr>";
                } else {
                    echo "<tr><td>$table</td><td class='error'>✗ Missing</td><td>-</td></tr>";
                }
            }
            echo '</table>';
            
            // Test 4: Check accounts table structure
            echo '</div><div class="card">';
            echo '<h2>4. Accounts Table Structure</h2>';
            $result = $conn->query("DESCRIBE accounts");
            if ($result) {
                echo '<table>';
                echo '<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th></tr>';
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td></tr>";
                }
                echo '</table>';
            }
            
            // Test 5: Check reviews table structure
            echo '</div><div class="card">';
            echo '<h2>5. Reviews Table Structure</h2>';
            $result = $conn->query("DESCRIBE reviews");
            if ($result) {
                echo '<table>';
                echo '<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th></tr>';
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td></tr>";
                }
                echo '</table>';
            } else {
                echo '<p class="warning">⚠ Reviews table not found (this might be expected)</p>';
            }
            
            $conn->close();
        } else {
            echo '<p class="error">✗ Database connection failed</p>';
        }
    } catch (Exception $e) {
        echo '<p class="error">✗ Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    echo '</div>';
    
    // Test 6: PHP Configuration
    echo '<div class="card">';
    echo '<h2>6. PHP Configuration</h2>';
    echo '<p>PHP Version: <strong>' . phpversion() . '</strong></p>';
    echo '<p>MySQLi Extension: <strong>' . (extension_loaded('mysqli') ? '✓ Loaded' : '✗ Not Loaded') . '</strong></p>';
    echo '<p>Upload Max Filesize: <strong>' . ini_get('upload_max_filesize') . '</strong></p>';
    echo '<p>Post Max Size: <strong>' . ini_get('post_max_size') . '</strong></p>';
    echo '</div>';
    ?>
    
    <div class="card">
        <h2>Next Steps</h2>
        <p>If all checks pass, try:</p>
        <ul>
            <li><a href="index.php" class="btn">→ Go to Homepage</a></li>
            <li><a href="test_connection.php" class="btn">→ Test JSON API</a></li>
        </ul>
    </div>
    

  <script src='../vendors/js/theme-toggle.js'></script>
</body>
</html>
