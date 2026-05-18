<?php
require_once '../config/database.php';
header('Content-Type: application/json');

try {
    global $cnx;
    
    // Fetch all restaurants
    $stmt = $cnx->query("SELECT * FROM restaurants");
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all reviews
    $stmt2 = $cnx->query("SELECT * FROM reviews ORDER BY date DESC");
    $reviews = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all comments
    $stmt3 = $cnx->query("SELECT * FROM comments ORDER BY created_at ASC");
    $comments = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    // Group comments by review_id
    $groupedComments = [];
    foreach($comments as $c) {
        $groupedComments[$c['review_id']][] = $c;
    }

    // Group reviews by restaurant_id and attach comments
    $groupedReviews = [];
    foreach($reviews as $rev) {
        $rev['id'] = (int)$rev['id'];
        $rev['rating'] = isset($rev['rating']) ? (int)$rev['rating'] : 0;
        $rev['ambiance'] = isset($rev['ambiance']) ? (int)$rev['ambiance'] : 0;
        $rev['cleanliness'] = isset($rev['cleanliness']) ? (int)$rev['cleanliness'] : 0;
        $rev['quality'] = isset($rev['quality']) ? (int)$rev['quality'] : 0;
        $rev['service'] = isset($rev['service']) ? (int)$rev['service'] : 0;
        if ($rev['ambiance'] && $rev['cleanliness'] && $rev['quality'] && $rev['service']) {
            $rev['rating'] = (int) round(($rev['ambiance'] + $rev['cleanliness'] + $rev['quality'] + $rev['service']) / 4);
        }
        $rev['comments'] = isset($groupedComments[$rev['id']]) ? $groupedComments[$rev['id']] : [];
        $groupedReviews[$rev['restaurant_id']][] = $rev;
    }

    foreach($restaurants as &$r) {
        $r['id'] = (int)$r['id'];
        $r['lat'] = (float)$r['lat'];
        $r['lng'] = (float)$r['lng'];
        $r['reviews'] = isset($groupedReviews[$r['id']]) ? $groupedReviews[$r['id']] : [];
        $r['tags'] = !empty($r['tags']) ? explode(',', $r['tags']) : [];
        
        // Deduced region for filters
        $city = $r['city'];
        $regionMap = [
            'Tunis' => 'Tunis', 'La Marsa' => 'Tunis', 'Sidi Bou Said' => 'Tunis', 'La Goulette' => 'Tunis', 'Carthage' => 'Tunis',
            'Ariana' => 'Ariana', 'Bizerte' => 'Bizerte', 'Nabeul' => 'Nabeul', 'Hammamet' => 'Nabeul',
            'Zaghouan' => 'Zaghouan', 'Mahdia' => 'Mahdia', 'Sfax' => 'Sfax', 'Tozeur' => 'Tozeur',
            'Medenine' => 'Medenine', 'Kairouan' => 'Kairouan', 'Le Kef' => 'Le Kef', 'Tataouine' => 'Tataouine',
            'Sousse' => 'Sousse', 'Béja' => 'Béja', 'Jendouba' => 'Jendouba', 'Monastir' => 'Monastir',
            'Siliana' => 'Siliana', 'Gabès' => 'Gabès', 'Gafsa' => 'Gafsa', 'Kasserine' => 'Kasserine', 'Kebili' => 'Kebili'
        ];
        $r['region'] = isset($regionMap[$city]) ? $regionMap[$city] : $city;
    }

    echo json_encode($restaurants);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
