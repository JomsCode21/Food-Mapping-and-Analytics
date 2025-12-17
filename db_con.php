<?php
$host = 'localhost'; // Sometimes different, e.g., '127.0.0.1' or a specific host from your cPanel
$dbname = 'u366677621_tastelibmanan'; 
$username = 'root';
$password = ''; // the one set in your hosting control panel

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
