<?php
require_once __DIR__ . '/../config/database.php';

$reviewers = [
    ['name' => 'Sarah Ben Amor'],
    ['name' => 'Mehdi Trabelsi'],
    ['name' => 'Emna Gharbi'],
    ['name' => 'Yassin Mansour'],
    ['name' => 'Linda Karoui']
];

$comments = [
    5 => [
        "Absolutely incredible experience! The flavors were perfectly balanced.",
        "Best meal I've had in a long time. Service was impeccable.",
        "A true gem. The atmosphere and the food are both top-notch.",
        "Everything was perfect from start to finish. Highly recommended!",
        "Stunning presentation and divine tastes. Will definitely return."
    ],
    4 => [
        "Very good food and lovely atmosphere. Slightly long wait for the main course.",
        "Great quality ingredients. The staff was very attentive.",
        "Delicious meal. The spices were spot on, though the place was a bit noisy.",
        "A wonderful dining experience. Good value for money.",
        "Solid choice for authentic cuisine. The desserts were the highlight!"
    ],
    3 => [
        "Decent food but expected more for the price.",
        "Average experience. The food was okay but nothing memorable.",
        "Good location, but the service was a bit slow today.",
        "Standard quality. Fine for a quick lunch.",
        "The meal was alright, but the seating could be more comfortable."
    ]
];

try {
    // 1. Get all restaurants
    $stmt = $cnx->query("SELECT id FROM restaurants");
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($restaurants)) {
        die("No restaurants found. Please run import_restaurants.php first.\n");
    }

    // 2. WIPE ALL OLD REVIEWS (to start clean and fix the badge counts)
    echo "Wiping old reviews to fix current data integrity...\n";
    $cnx->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE reviews; SET FOREIGN_KEY_CHECKS = 1;");

    // 3. SEED COMMUNITY REVIEWS (user_id = NULL)
    $stmtInsert = $cnx->prepare("INSERT INTO reviews (restaurant_id, user_id, author, rating, text, facture_code, date) VALUES (?, NULL, ?, ?, ?, ?, ?)");

    echo "Seeding community reviews for " . count($restaurants) . " restaurants...\n";

    $count = 0;
    foreach ($restaurants as $r) {
        // Add 2-4 reviews per restaurant
        $numReviews = rand(2, 4);
        foreach (array_slice($reviewers, 0, $numReviews) as $reviewer) {
            $rating = rand(3, 5); 
            $text = $comments[$rating][array_rand($comments[$rating])];
            $date = date('Y-m-d', strtotime('-' . rand(1, 180) . ' days'));
            
            $stmtInsert->execute([
                $r['id'],
                $reviewer['name'],
                $rating,
                $text,
                'SEED-' . $r['id'] . '-' . $count,
                $date
            ]);
            $count++;
        }
    }

    echo "Success! Database cleaned and $count community reviews added.\n";
    echo "NEW REVIEWS you write will now be correctly linked to your user account.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
