<?php
$host = 'localhost';
$dbname = 'TASTELIBMANAN';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// No need to close the connection here.  It will be closed at the end of the script that includes this file, or automatically when the script finishes.
?>