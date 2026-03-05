<?php
/**
 * Execute SQL file to create missing tables
 * Run this once to setup the reviews table
 */

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once __DIR__ . '/../db_con.php';

try {
    // Prefer organized SQL location with fallback for legacy root path.
    $sqlFile = __DIR__ . '/../database/create_reviews_table.sql';
    if (!file_exists($sqlFile)) {
        $sqlFile = __DIR__ . '/../create_reviews_table.sql';
    }

    // Read the SQL file
    $sql = file_get_contents($sqlFile);
    
    if ($sql === false) {
        throw new Exception('Could not read SQL file');
    }
    
    // Execute the SQL
    if ($conn->multi_query($sql)) {
        do {
            // Store first result set
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        
        echo json_encode([
            'success' => true,
            'message' => 'Reviews table created successfully!'
        ]);
    } else {
        throw new Exception('SQL execution failed: ' . $conn->error);
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
