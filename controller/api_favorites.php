<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/response.php';


header('Content-Type: application/json');
setCorsHeaders();
handleCorsPreFlight();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    sendError('Not logged in', 401);
}

$user_id = $_SESSION['user_id'];

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            // Get user's favorites
            $stmt = $cnx->prepare("
                SELECT f.id, f.restaurant_id, f.created_at, 
                       r.name, r.cuisine, r.category, r.city, r.image, r.priceRange, r.address, r.description
                FROM favorites f
                JOIN restaurants r ON f.restaurant_id = r.id
                WHERE f.user_id = ?
                ORDER BY f.created_at DESC
            ");
            $stmt->execute([$user_id]);
            $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
            sendSuccess($favorites, 'Favorites retrieved');
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || !isset($data['restaurant_id'])) {
                sendError('Missing restaurant_id', 400);
            }
            $restaurant_id = intval($data['restaurant_id']);
            
            // Check if already favorited
            $check = $cnx->prepare("SELECT id FROM favorites WHERE user_id = ? AND restaurant_id = ?");
            $check->execute([$user_id, $restaurant_id]);
            
            if ($check->fetch()) {
                // Already exists → remove it (toggle behavior)
                $stmt = $cnx->prepare("DELETE FROM favorites WHERE user_id = ? AND restaurant_id = ?");
                $stmt->execute([$user_id, $restaurant_id]);
                sendSuccess(['action' => 'removed'], 'Removed from favorites');
            } else {
                // Add to favorites
                $stmt = $cnx->prepare("INSERT INTO favorites (user_id, restaurant_id) VALUES (?, ?)");
                $stmt->execute([$user_id, $restaurant_id]);
                sendSuccess(['action' => 'added'], 'Added to favorites');
            }
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || !isset($data['restaurant_id'])) {
                sendError('Missing restaurant_id', 400);
            }
            $restaurant_id = intval($data['restaurant_id']);
            $stmt = $cnx->prepare("DELETE FROM favorites WHERE user_id = ? AND restaurant_id = ?");
            $stmt->execute([$user_id, $restaurant_id]);
            sendSuccess(null, 'Removed from favorites');
            break;

        default:
            sendError('Method not allowed', 405);
    }
} catch (Exception $e) {
    error_log("Favorites error: " . $e->getMessage());
    sendError($e->getMessage(), 500);
}
?>




