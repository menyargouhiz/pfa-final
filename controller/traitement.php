<?php
<<<<<<< HEAD
include_once(__DIR__ . "/../config/database.php");
include_once(__DIR__ . "/../model/user.php");
=======
include("../config/database.php");
include("../model/user.php");
>>>>>>> df34791d4b40b7fc6586c4e6c6ecd09ede24f718

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    if(defined('PHPUNIT_RUNNING')) { throw new ResponseException('exit', 200); } else { exit; }
}

// Add user
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    if (!isset($_POST['user_name'], $_POST['email'], $_POST['password'])) {
        http_response_code(400);
        if(defined('PHPUNIT_RUNNING')) { throw new ResponseException('Missing required fields', 400); } else { die(json_encode(['success' => false, 'error' => 'Missing required fields'])); }
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
    if(defined('PHPUNIT_RUNNING')) { throw new ResponseException('exit', 200); } else { exit; }
}

// Get all users
if (isset($_POST['action']) && $_POST['action'] === 'getAll') {
    try {
        $stmt = $cnx->query("
            SELECT u.id, u.nom, u.email, COUNT(r.id) AS review_count
            FROM users u
            LEFT JOIN reviews r ON r.user_id = u.id
            GROUP BY u.id, u.nom, u.email
            ORDER BY u.nom ASC
        ");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode(['success' => true, 'data' => $users]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    if(defined('PHPUNIT_RUNNING')) { throw new ResponseException('exit', 200); } else { exit; }
}

// Search users by name or email
if (isset($_POST['action']) && $_POST['action'] === 'search') {
    if (!isset($_POST['name'])) {
        http_response_code(400);
<<<<<<< HEAD
        if(defined('PHPUNIT_RUNNING')) { throw new ResponseException('Search parameter required', 400); } else { die(json_encode(['success' => false, 'error' => 'Search parameter required'])); }
=======
        die(json_encode(['success' => false, 'error' => 'Search parameter required']));
>>>>>>> df34791d4b40b7fc6586c4e6c6ecd09ede24f718
    }

    try {
        $search = '%' . trim($_POST['name']) . '%';
        $stmt = $cnx->prepare("
            SELECT u.id, u.nom, u.email, COUNT(r.id) AS review_count
            FROM users u
            LEFT JOIN reviews r ON r.user_id = u.id
            WHERE u.nom LIKE ? OR u.email LIKE ?
            GROUP BY u.id, u.nom, u.email
            ORDER BY u.nom ASC
        ");
        $stmt->execute([$search, $search]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        http_response_code(200);
        echo json_encode(['success' => true, 'data' => $users]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    if(defined('PHPUNIT_RUNNING')) { throw new ResponseException('exit', 200); } else { exit; }
}

http_response_code(400);
echo json_encode(['success' => false, 'error' => 'No action specified']);
?>
<<<<<<< HEAD




=======
>>>>>>> df34791d4b40b7fc6586c4e6c6ecd09ede24f718
