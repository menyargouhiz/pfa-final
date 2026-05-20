<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/response.php';

header('Content-Type: application/json');
setCorsHeaders();
handleCorsPreFlight();

try {
    // Get all users with their password info (for debugging)
    $stmt = $cnx->prepare("SELECT id, nom, email, password, LENGTH(password) as pwd_length FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendSuccess($users, 'All users with password info');
} catch (Exception $e) {
    error_log("Check users error: " . $e->getMessage());
    sendError($e->getMessage(), 500);
}
?>




