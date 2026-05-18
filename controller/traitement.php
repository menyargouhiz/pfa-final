<?php
include("../config/database.php");
include("../model/User.php");

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Add user
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    if (!isset($_POST['user_name'], $_POST['email'], $_POST['password'])) {
        http_response_code(400);
        die(json_encode(['success' => false, 'error' => 'Missing required fields']));
    }

    try {
        $result = User::create($_POST['user_name'], $_POST['email'], $_POST['password']);
        if ($result) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'User added successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Failed to add user']);
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// Get all users
if (isset($_POST['action']) && $_POST['action'] === 'getAll') {
    try {
        $users = User::readAll();
        http_response_code(200);
        echo json_encode(['success' => true, 'data' => $users]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// Search users by name
if (isset($_POST['action']) && $_POST['action'] === 'search') {
    if (!isset($_POST['name'])) {
        http_response_code(400);
        die(json_encode(['success' => false, 'error' => 'Name parameter required']));
    }

    try {
        $stmt = $cnx->prepare("SELECT * FROM users WHERE nom LIKE ?");
        $stmt->execute(['%' . $_POST['name'] . '%']);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $users = $stmt->fetchAll();
        
        http_response_code(200);
        echo json_encode(['success' => true, 'data' => $users]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'error' => 'No action specified']);
?>