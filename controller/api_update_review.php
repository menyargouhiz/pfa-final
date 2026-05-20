<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/review.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    if(defined('PHPUNIT_RUNNING')) { throw new ResponseException('exit', 200); } else { exit; }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    if(defined('PHPUNIT_RUNNING')) { throw new ResponseException('exit', 200); } else { exit; }
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['review_id'], $data['rating'], $data['text'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    if(defined('PHPUNIT_RUNNING')) { throw new ResponseException('exit', 200); } else { exit; }
}

try {
    $review_id = intval($data['review_id']);
    $rating = intval($data['rating']);
    $text = $data['text'];

    // Validate rating
    if ($rating < 1 || $rating > 5) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Rating must be between 1 and 5']);
        if(defined('PHPUNIT_RUNNING')) { throw new ResponseException('exit', 200); } else { exit; }
    }

    // Verify review exists
    $review = Review::findById($review_id);
    if (!$review) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Review not found']);
        if(defined('PHPUNIT_RUNNING')) { throw new ResponseException('exit', 200); } else { exit; }
    }

    // Update review
    $result = Review::update($review_id, $rating, $text);

    if ($result) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Review updated successfully'
        ]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Failed to update review']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 9f33d9882a7e691571e96025575b7eef87d6352b




<<<<<<< HEAD
=======
=======
>>>>>>> df34791d4b40b7fc6586c4e6c6ecd09ede24f718
>>>>>>> 9f33d9882a7e691571e96025575b7eef87d6352b
