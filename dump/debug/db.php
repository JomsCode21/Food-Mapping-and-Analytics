<?php
require_once __DIR__ . '/../../env.php';

$host = env_value('DB_HOST', 'localhost');
$dbname = env_value('DB_NAME', 'TASTELIBMANAN');
$username = env_value('DB_USER', 'root');
$password = env_value('DB_PASS', '');

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// No need to close the connection here.  It will be closed at the end of the script that includes this file, or automatically when the script finishes.
?>