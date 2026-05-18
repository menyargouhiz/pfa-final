<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/review.php';
require_once __DIR__ . '/validators.php';
require_once __DIR__ . '/response.php';

header('Content-Type: application/json');
setCorsHeaders();
handleCorsPreFlight();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method not allowed', 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['restaurant_id'], $data['text'], $data['author'], $data['facture_code'])) {
    sendError('Missing required fields: restaurant_id, author, text, facture_code', 400);
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

    $factureValidation = validateFactureCode($data['facture_code']);
    if (!$factureValidation['valid']) {
        sendError($factureValidation['error'], 400);
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
    $facture_code = strtoupper(trim($data['facture_code']));

    $duplicateStmt = $cnx->prepare("SELECT id FROM reviews WHERE restaurant_id = ? AND facture_code = ? LIMIT 1");
    $duplicateStmt->execute([$restaurant_id, $facture_code]);
    if ($duplicateStmt->fetch()) {
        sendError('This facture code has already been used for this restaurant', 409);
    }
    
    // Get user_id from session if logged in
    $user_id = (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) ? $_SESSION['user_id'] : null;

    // Create review
    $result = Review::create($restaurant_id, $author, $rating, $text, $facture_code, $ambiance, $cleanliness, $quality, $service, $user_id);

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
