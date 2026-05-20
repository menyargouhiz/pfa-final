<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/user.php';
require_once __DIR__ . '/validators.php';
require_once __DIR__ . '/response.php';

header('Content-Type: application/json');
setCorsHeaders();
handleCorsPreFlight();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method not allowed', 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['email'], $data['password'])) {
    sendError('Email and password required', 400);
}

// Validate email format
$emailValidation = validateEmail($data['email']);
if (!$emailValidation['valid']) {
    sendError($emailValidation['error'], 400);
}

try {
    // Find user by email
    $user = User::findByEmail($data['email']);
    
    if (!$user) {
        sendError('Invalid email or password', 401);
    }
    
    // Verify password
    if (!password_verify($data['password'], $user->password)) {
        sendError('Invalid email or password', 401);
    }
    
    // Create session
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->nom;
    $_SESSION['logged_in'] = true;
    
    sendSuccess([
        'id' => $user->id,
        'name' => $user->nom,
        'email' => $user->email
    ], 'Login successful', 200);
    
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    sendError('Server error: ' . $e->getMessage(), 500);
}
?>




