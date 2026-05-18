<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/response.php';

header('Content-Type: application/json');
setCorsHeaders();
handleCorsPreFlight();

// Debug: print all session variables
$sessionInfo = [
    'session_id' => session_id(),
    'session_status' => session_status(),
    'session_vars' => $_SESSION,
    'cookies_sent' => isset($_COOKIE) ? array_keys($_COOKIE) : [],
    'headers_sent' => headers_list()
];

sendSuccess($sessionInfo, 'Session debug info');
?>
