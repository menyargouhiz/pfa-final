<?php
include_once(__DIR__ . "/../config/database.php");
include_once(__DIR__ . "/../model/user.php");

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    if(defined('PHPUNIT_RUNNING')) { throw new ResponseException('exit', 200); } else { exit; }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    if(defined('PHPUNIT_RUNNING')) { throw new ResponseException('exit', 200); } else { exit; }
}

if (!isset($_POST['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'User ID required']);
    if(defined('PHPUNIT_RUNNING')) { throw new ResponseException('exit', 200); } else { exit; }
}

try {
    $id = $_POST['id'];
    
    // Delete using User model (safe prepared statement)
    $result = User::delete($id);
    
    if ($result) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Failed to delete user']);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>




