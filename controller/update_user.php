<?php
include("../config/database.php");
include("../model/user.php");

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

if (!isset($_POST['idu'], $_POST['user_name'], $_POST['email'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    $id = $_POST['idu'];
    $nom = $_POST['user_name'];
    $email = $_POST['email'];
    $password = isset($_POST['password']) && !empty($_POST['password']) ? $_POST['password'] : null;
    
    // Update using User model (safe prepared statement)
    $result = User::update($id, $nom, $email, $password);
    
    if ($result) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Failed to update user']);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
