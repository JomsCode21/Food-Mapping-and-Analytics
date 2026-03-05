<?php
/**
 * Test Database Connection
 * Visit this file in your browser to verify the database connection works
 */

header('Content-Type: application/json');

// Disable error display, enable logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

try {
    require_once __DIR__ . '/../db_con.php';
    
    // Test a simple query
    $result = $conn->query("SELECT DATABASE() as db_name");
    
    if ($result) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'message' => 'Database connection successful!',
            'database' => $row['db_name'],
            'server_info' => $conn->server_info
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Query failed: ' . $conn->error
        ]);
    }
    
    $conn->close();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Connection error: ' . $e->getMessage()
    ]);
}
