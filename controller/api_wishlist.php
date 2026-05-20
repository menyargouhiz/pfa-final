<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/response.php';
require_once __DIR__ . '/../database/restaurant_images.php';

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
            // Get user's wishlist
            $stmt = $cnx->prepare("
                SELECT w.id, w.restaurant_id, w.created_at,
                       r.name, r.cuisine, r.category, r.city, r.image, r.priceRange, r.address, r.description
                FROM wishlist w
                JOIN restaurants r ON w.restaurant_id = r.id
                WHERE w.user_id = ?
                ORDER BY w.created_at DESC
            ");
            $stmt->execute([$user_id]);
            $wishlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $wishlist = appetitus_assign_unique_restaurant_images($wishlist);
            sendSuccess($wishlist, 'Wishlist retrieved');
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || !isset($data['restaurant_id'])) {
                sendError('Missing restaurant_id', 400);
            }
            $restaurant_id = intval($data['restaurant_id']);
            
            // Check if already in wishlist
            $check = $cnx->prepare("SELECT id FROM wishlist WHERE user_id = ? AND restaurant_id = ?");
            $check->execute([$user_id, $restaurant_id]);
            
            if ($check->fetch()) {
                // Already exists → remove it (toggle behavior)
                $stmt = $cnx->prepare("DELETE FROM wishlist WHERE user_id = ? AND restaurant_id = ?");
                $stmt->execute([$user_id, $restaurant_id]);
                sendSuccess(['action' => 'removed'], 'Removed from wishlist');
            } else {
                // Add to wishlist
                $stmt = $cnx->prepare("INSERT INTO wishlist (user_id, restaurant_id) VALUES (?, ?)");
                $stmt->execute([$user_id, $restaurant_id]);
                sendSuccess(['action' => 'added'], 'Added to wishlist');
            }
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || !isset($data['restaurant_id'])) {
                sendError('Missing restaurant_id', 400);
            }
            $restaurant_id = intval($data['restaurant_id']);
            $stmt = $cnx->prepare("DELETE FROM wishlist WHERE user_id = ? AND restaurant_id = ?");
            $stmt->execute([$user_id, $restaurant_id]);
            sendSuccess(null, 'Removed from wishlist');
            break;

        default:
            sendError('Method not allowed', 405);
    }
} catch (Exception $e) {
    error_log("Wishlist error: " . $e->getMessage());
    sendError($e->getMessage(), 500);
}
?>




