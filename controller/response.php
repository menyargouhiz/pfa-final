<?php

/**
 * Standardized Response Helpers
 */

/**
 * Send a success response
 */
function sendSuccess($data = null, $message = null, $statusCode = 200) {
    http_response_code($statusCode);
    
    $response = ['success' => true];
    
    if ($message) {
        $response['message'] = $message;
    }
    
    if ($data) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}

/**
 * Send an error response
 */
function sendError($error, $statusCode = 400, $details = null) {
    http_response_code($statusCode);
    
    $response = [
        'success' => false,
        'error' => $error
    ];
    
    if ($details) {
        $response['details'] = $details;
    }
    
    echo json_encode($response);
    exit;
}

/**
 * Set CORS headers using an explicit origin whitelist.
 *
 * Add any legitimate front-end origins to $allowedOrigins.
 * The header is only set when the request origin matches — unknown
 * origins get no CORS header, so the browser blocks them.
 */
function setCorsHeaders() {
    $allowedOrigins = [
        'http://localhost',
        'http://localhost:3000',
        'http://127.0.0.1',
        // 'https://yourdomain.com',  // ← add your production URL here
    ];

    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    if (in_array($origin, $allowedOrigins, true)) {
        header("Access-Control-Allow-Origin: $origin");
        header('Access-Control-Allow-Credentials: true');
    }
    // No wildcard fallback — unlisted origins are silently denied.

    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Max-Age: 3600');
}

/**
 * Handle preflight requests
 */
function handleCorsPreFlight() {
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}