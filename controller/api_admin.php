<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/response.php';

header('Content-Type: application/json');
setCorsHeaders();
handleCorsPreFlight();

if (!function_exists('adminReadJsonInput')) {
    function adminReadJsonInput()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        return is_array($input) ? $input : [];
    }
}

if (!function_exists('adminNormalizeTags')) {
    function adminNormalizeTags($tags)
    {
        if (is_array($tags)) {
            return implode(',', array_filter(array_map('trim', $tags)));
        }
        return trim((string) $tags);
    }
}

if (!function_exists('adminFetchDashboard')) {
    function adminFetchDashboard(PDO $cnx)
    {
    $restaurants = $cnx->query("
        SELECT
            r.*,
            COALESCE(review_stats.avg_rating, 0) AS avg,
            COALESCE(review_stats.review_count, 0) AS review_count
        FROM restaurants r
        LEFT JOIN (
            SELECT restaurant_id, AVG(rating) AS avg_rating, COUNT(*) AS review_count
            FROM reviews
            GROUP BY restaurant_id
        ) review_stats ON review_stats.restaurant_id = r.id
        ORDER BY r.name ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($restaurants as &$restaurant) {
        $restaurant['id'] = (int) $restaurant['id'];
        $restaurant['avg'] = round((float) $restaurant['avg'], 1);
        $restaurant['review_count'] = (int) $restaurant['review_count'];
        $restaurant['reviews'] = [];
        $restaurant['tags'] = !empty($restaurant['tags'])
            ? array_values(array_filter(array_map('trim', explode(',', $restaurant['tags']))))
            : [];
    }
    unset($restaurant);

    $reviews = $cnx->query("
        SELECT
            rv.id,
            rv.restaurant_id,
            rv.user_id,
            rv.author,
            rv.rating,
            rv.ambiance,
            rv.cleanliness,
            rv.quality,
            rv.service,
            rv.text,
            rv.date,
            r.name AS restaurant_name,
            COALESCE(u.nom, rv.author) AS user_name,
            u.email AS user_email
        FROM reviews rv
        LEFT JOIN restaurants r ON r.id = rv.restaurant_id
        LEFT JOIN users u ON u.id = rv.user_id
        ORDER BY rv.date DESC, rv.id DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($reviews as &$review) {
        $review['id'] = (int) $review['id'];
        $review['restaurant_id'] = (int) $review['restaurant_id'];
        $review['restaurantId'] = (int) $review['restaurant_id'];
        $review['user_id'] = $review['user_id'] === null ? null : (int) $review['user_id'];
        $review['userId'] = $review['user_id'];
        $review['rating'] = (int) $review['rating'];
        $review['ambiance'] = (int) $review['ambiance'];
        $review['cleanliness'] = (int) $review['cleanliness'];
        $review['quality'] = (int) $review['quality'];
        $review['service'] = (int) $review['service'];
    }
    unset($review);

    $users = $cnx->query("
        SELECT u.id, u.nom, u.email, COUNT(rv.id) AS review_count
        FROM users u
        LEFT JOIN reviews rv ON rv.user_id = u.id
        GROUP BY u.id, u.nom, u.email
        ORDER BY u.nom ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as &$user) {
        $user['id'] = (int) $user['id'];
        $user['review_count'] = (int) $user['review_count'];
    }
    unset($user);

    $favorites = $cnx->query("
        SELECT
            u.id AS user_id,
            u.nom AS user_name,
            COUNT(DISTINCT f.restaurant_id) AS favorites_count,
            COUNT(DISTINCT w.restaurant_id) AS wishlist_count,
            GROUP_CONCAT(DISTINCT fr.name ORDER BY fr.name SEPARATOR ', ') AS favorites_list,
            GROUP_CONCAT(DISTINCT wr.name ORDER BY wr.name SEPARATOR ', ') AS wishlist_list
        FROM users u
        LEFT JOIN favorites f ON f.user_id = u.id
        LEFT JOIN restaurants fr ON fr.id = f.restaurant_id
        LEFT JOIN wishlist w ON w.user_id = u.id
        LEFT JOIN restaurants wr ON wr.id = w.restaurant_id
        GROUP BY u.id, u.nom
        ORDER BY u.nom ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($favorites as &$row) {
        $row['user_id'] = (int) $row['user_id'];
        $row['favorites_count'] = (int) $row['favorites_count'];
        $row['wishlist_count'] = (int) $row['wishlist_count'];
        $row['favorites_list'] = $row['favorites_list'] ?: 'None';
        $row['wishlist_list'] = $row['wishlist_list'] ?: 'None';
    }
    unset($row);

    $stats = [
        'total_restaurants' => count($restaurants),
        'total_reviews' => count($reviews),
        'total_users' => count($users),
        'avg_rating' => count($reviews)
            ? round(array_sum(array_map(fn($review) => (int) $review['rating'], $reviews)) / count($reviews), 1)
            : 0,
        'total_favorites' => array_sum(array_map(fn($row) => $row['favorites_count'], $favorites)),
        'total_wishlist' => array_sum(array_map(fn($row) => $row['wishlist_count'], $favorites)),
    ];

    return [
        'stats' => $stats,
        'restaurants' => $restaurants,
        'reviews' => $reviews,
        'users' => $users,
        'favorites' => $favorites,
    ];
    }
}

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    $data = adminReadJsonInput();

    if ($action === 'dashboard' || $action === '') {
        sendSuccess(adminFetchDashboard($cnx), 'Admin dashboard loaded');
    }

    if ($action === 'delete_restaurant') {
        $id = (int) ($data['id'] ?? $_POST['id'] ?? 0);
        if ($id <= 0) sendError('Restaurant ID required', 400);

        $cnx->beginTransaction();
        $commentStmt = $cnx->prepare("
            DELETE c
            FROM comments c
            INNER JOIN reviews rv ON rv.id = c.review_id
            WHERE rv.restaurant_id = ?
        ");
        $commentStmt->execute([$id]);

        $cnx->prepare("DELETE FROM favorites WHERE restaurant_id = ?")->execute([$id]);
        $cnx->prepare("DELETE FROM wishlist WHERE restaurant_id = ?")->execute([$id]);
        $cnx->prepare("DELETE FROM reviews WHERE restaurant_id = ?")->execute([$id]);
        $stmt = $cnx->prepare("DELETE FROM restaurants WHERE id = ?");
        $stmt->execute([$id]);
        $cnx->commit();
        sendSuccess(['deleted' => $stmt->rowCount()], 'Restaurant deleted');
    }

    if ($action === 'delete_review') {
        $id = (int) ($data['id'] ?? $_POST['id'] ?? 0);
        if ($id <= 0) sendError('Review ID required', 400);

        $stmt = $cnx->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
        sendSuccess(['deleted' => $stmt->rowCount()], 'Review deleted');
    }

    if ($action === 'delete_user') {
        $id = (int) ($data['id'] ?? $_POST['id'] ?? 0);
        if ($id <= 0) sendError('User ID required', 400);

        $stmt = $cnx->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        sendSuccess(['deleted' => $stmt->rowCount()], 'User deleted');
    }

    if ($action === 'save_restaurant') {
        $name = trim((string) ($data['name'] ?? ''));
        if ($name === '') sendError('Restaurant name required', 400);

        $payload = [
            'name' => $name,
            'cuisine' => trim((string) ($data['cuisine'] ?? '')),
            'category' => trim((string) ($data['category'] ?? '')),
            'city' => trim((string) ($data['city'] ?? '')),
            'priceRange' => trim((string) ($data['priceRange'] ?? '')),
            'image' => trim((string) ($data['image'] ?? '')),
            'tags' => adminNormalizeTags($data['tags'] ?? ''),
        ];

        $id = (int) ($data['id'] ?? 0);
        if ($id > 0) {
            $stmt = $cnx->prepare("
                UPDATE restaurants
                SET name = ?, cuisine = ?, category = ?, city = ?, priceRange = ?, image = ?, tags = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $payload['name'],
                $payload['cuisine'],
                $payload['category'],
                $payload['city'],
                $payload['priceRange'],
                $payload['image'],
                $payload['tags'],
                $id,
            ]);
            sendSuccess(['id' => $id], 'Restaurant updated');
        }

        $stmt = $cnx->prepare("
            INSERT INTO restaurants (name, cuisine, category, city, priceRange, image, tags)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $payload['name'],
            $payload['cuisine'],
            $payload['category'],
            $payload['city'],
            $payload['priceRange'],
            $payload['image'],
            $payload['tags'],
        ]);
        sendSuccess(['id' => (int) $cnx->lastInsertId()], 'Restaurant created', 201);
    }

    sendError('Unknown admin action', 400);
} catch (Exception $e) {
    if (isset($cnx) && $cnx instanceof PDO && $cnx->inTransaction()) {
        $cnx->rollBack();
    }
    error_log('Admin API error: ' . $e->getMessage());
    sendError($e->getMessage(), 500);
}
