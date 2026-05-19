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

if (!$data || !isset($data['name'], $data['email'], $data['password'])) {
    sendError('Missing required fields: name, email, password', 400);
}

try {
    // Validate inputs
    $nameValidation = validateName($data['name']);
    if (!$nameValidation['valid']) {
        sendError($nameValidation['error'], 400);
    }

    $emailValidation = validateEmail($data['email']);
    if (!$emailValidation['valid']) {
        sendError($emailValidation['error'], 400);
    }

    $passwordValidation = validatePassword($data['password']);
    if (!$passwordValidation['valid']) {
        sendError($passwordValidation['error'], 400);
    }

    // Check if email already exists
    $existingUser = User::findByEmail($data['email']);
    if ($existingUser) {
        sendError('Email already registered', 400);
    }

    // Create user
    $mdpHash = password_hash($data['password'], PASSWORD_DEFAULT);
    $stmt = $cnx->prepare("INSERT INTO users(nom, email, password) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$data['name'], $data['email'], $mdpHash])) {
        sendSuccess(['message' => 'User registered successfully'], 'User registered successfully', 201);
    } else {
        sendError('Registration failed', 400);
    }

} catch (Exception $e) {
    error_log("Signup error: " . $e->getMessage());
    sendError('Registration failed: ' . $e->getMessage(), 500);
}
?>
<<<<<<< HEAD




=======
>>>>>>> df34791d4b40b7fc6586c4e6c6ecd09ede24f718
