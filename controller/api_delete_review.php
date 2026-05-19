<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/review.php';
require_once __DIR__ . '/response.php';

header('Content-Type: application/json');
setCorsHeaders();
handleCorsPreFlight();

// --- Auth check ---
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    sendError('Not logged in', 401);
}

$current_user_id = (int) $_SESSION['user_id'];

// --- Method check ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    sendError('Method not allowed', 405);
}

// --- Input ---
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['review_id'])) {
    sendError('Review ID required', 400);
}

try {
    $review_id = (int) $data['review_id'];

    // --- Existence check ---
    $review = Review::findById($review_id);
    if (!$review) {
        sendError('Review not found', 404);
    }

    // --- Ownership check ---
    // Admins (role stored in session) may delete any review.
    // Regular users may only delete their own.
    $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

    if (!$isAdmin && (int) $review->user_id !== $current_user_id) {
        sendError('You are not allowed to delete this review', 403);
    }

    // --- Delete ---
    $result = Review::delete($review_id);

    if ($result) {
        sendSuccess(null, 'Review deleted successfully');
    } else {
        sendError('Failed to delete review', 500);
    }

} catch (Exception $e) {
    error_log('Delete review error: ' . $e->getMessage());
    sendError('Server error', 500);
}
<<<<<<< HEAD




=======
>>>>>>> df34791d4b40b7fc6586c4e6c6ecd09ede24f718
