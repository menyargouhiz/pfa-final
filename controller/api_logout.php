<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/response.php';

header('Content-Type: application/json');
setCorsHeaders();
handleCorsPreFlight();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method not allowed', 405);
}

try {
    session_destroy();
    sendSuccess(null, 'Logged out successfully', 200);
} catch (Exception $e) {
    error_log("Logout error: " . $e->getMessage());
    sendError('Logout failed', 500);
}
?>



