<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Review.php';
require_once __DIR__ . '/validators.php';
require_once __DIR__ . '/response.php';

header('Content-Type: application/json');
setCorsHeaders();
handleCorsPreFlight();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method not allowed', 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['restaurant_id'], $data['text'], $data['author'])) {
    sendError('Missing required fields: restaurant_id, author, text', 400);
}

try {
    // Validate inputs
    $authorValidation = validateName($data['author']);
    if (!$authorValidation['valid']) {
        sendError($authorValidation['error'], 400);
    }

    $textValidation = validateReviewText($data['text']);
    if (!$textValidation['valid']) {
        sendError($textValidation['error'], 400);
    }

    $hasDimensions = isset($data['ambiance'], $data['cleanliness'], $data['quality'], $data['service']);
    if ($hasDimensions) {
        $ambianceValidation = validateDimensionRating($data['ambiance'], 'Ambiance');
        $cleanlinessValidation = validateDimensionRating($data['cleanliness'], 'Cleanliness');
        $qualityValidation = validateDimensionRating($data['quality'], 'Quality');
        $serviceValidation = validateDimensionRating($data['service'], 'Service');

        foreach ([$ambianceValidation, $cleanlinessValidation, $qualityValidation, $serviceValidation] as $validation) {
            if (!$validation['valid']) {
                sendError($validation['error'], 400);
            }
        }

        $ambiance = intval($data['ambiance']);
        $cleanliness = intval($data['cleanliness']);
        $quality = intval($data['quality']);
        $service = intval($data['service']);
        $rating = intval(round(($ambiance + $cleanliness + $quality + $service) / 4));
    } else {
        if (!isset($data['rating'])) {
            sendError('Missing rating fields', 400);
        }

        $ratingValidation = validateRating($data['rating']);
        if (!$ratingValidation['valid']) {
            sendError($ratingValidation['error'], 400);
        }

        $rating = intval($data['rating']);
        $ambiance = $cleanliness = $quality = $service = $rating;
    }

    $restaurant_id = intval($data['restaurant_id']);
    $text = $data['text'];
    $author = $data['author'];
    
    // Get user_id from session if logged in
    $user_id = (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) ? $_SESSION['user_id'] : null;

    // Create review
    $result = Review::create($restaurant_id, $author, $rating, $text, $ambiance, $cleanliness, $quality, $service, $user_id);

    if ($result) {
        sendSuccess(['message' => 'Review created successfully'], 'Review created successfully', 201);
    } else {
        sendError('Failed to create review', 400);
    }

} catch (Exception $e) {
    error_log("Create review error: " . $e->getMessage());
    sendError($e->getMessage(), 500);
}
?>