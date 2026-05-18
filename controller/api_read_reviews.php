<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Review.php';
require_once __DIR__ . '/response.php';

header('Content-Type: application/json');
setCorsHeaders();
handleCorsPreFlight();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method not allowed', 405);
}

try {
    // Get reviews by restaurant
    if (isset($_GET['restaurant_id'])) {
        $restaurant_id = intval($_GET['restaurant_id']);
        $reviews = Review::findByRestaurant($restaurant_id);
        sendSuccess($reviews, 'Reviews retrieved', 200);
        exit;
    }

    // Get reviews by user_id
    if (isset($_GET['user_id'])) {
        $user_id = intval($_GET['user_id']);
        $stmt = $cnx->prepare("SELECT * FROM reviews WHERE user_id = ? ORDER BY date DESC");
        $stmt->execute([$user_id]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        sendSuccess($reviews, 'User reviews retrieved', 200);
        exit;
    }

    // Get reviews for current logged-in user
    if (isset($_GET['mine'])) {
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            sendError('Unauthorized - session lost or not logged in', 401);
            exit;
        }
        $user_id = $_SESSION['user_id'];
        $stmt = $cnx->prepare("SELECT * FROM reviews WHERE user_id = ? ORDER BY date DESC");
        $stmt->execute([$user_id]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        sendSuccess($reviews, 'My reviews retrieved', 200);
        exit;
    }

    // Get all reviews
    $stmt = $cnx->query("SELECT * FROM reviews ORDER BY date DESC");
    $reviews = $stmt->fetchAll(PDO::FETCH_CLASS, 'Review');
    
    sendSuccess($reviews, 'All reviews retrieved', 200);

} catch (Exception $e) {
    error_log("Read reviews error: " . $e->getMessage());
    sendError($e->getMessage(), 500);
}
?>

