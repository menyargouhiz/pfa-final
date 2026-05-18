<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/response.php';

header('Content-Type: application/json');
setCorsHeaders();
handleCorsPreFlight();

try {
    // Alter the password column to be larger
    $sql = "ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NOT NULL";
    $cnx->exec($sql);
    
    sendSuccess(['message' => 'Password column expanded to VARCHAR(255)'], 'Database schema updated');
} catch (Exception $e) {
    error_log("Alter table error: " . $e->getMessage());
    sendError($e->getMessage(), 500);
}
?>
