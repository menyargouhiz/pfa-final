<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/response.php';

header('Content-Type: application/json');
setCorsHeaders();
handleCorsPreFlight();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method not allowed', 405);
}

try {
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        sendError('Not logged in', 401);
    }
    
    sendSuccess([
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'email' => $_SESSION['user_email']
    ], 'User session retrieved', 200);
    
} catch (Exception $e) {
    error_log("Session error: " . $e->getMessage());
    sendError('Server error', 500);
}
?>