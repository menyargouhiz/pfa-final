<?php
require_once __DIR__ . '/../config/database.php';

$reviewers = [
    ['name' => 'Sarah Ben Amor', 'id' => 1],
    ['name' => 'Mehdi Trabelsi', 'id' => 2],
    ['name' => 'Emna Gharbi', 'id' => 3],
    ['name' => 'Yassin Mansour', 'id' => 4],
    ['name' => 'Linda Karoui', 'id' => 5]
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
    // Check if we have restaurants
    $stmt = $cnx->query("SELECT id FROM restaurants");
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($restaurants)) {
        die("No restaurants found. Please run import_restaurants.php first.\n");
    }

    // Clean existing reviews if needed?
    // $cnx->exec("TRUNCATE TABLE reviews");

    $stmtInsert = $cnx->prepare("INSERT INTO reviews (restaurant_id, user_id, author, rating, text, date) VALUES (?, ?, ?, ?, ?, ?)");

    echo "Seeding reviews for " . count($restaurants) . " restaurants...\n";

    $count = 0;
    foreach ($restaurants as $r) {
        // Add 2-4 reviews per restaurant
        $numReviews = rand(2, 4);
        
        $usedReviewers = [];
        
        for ($i = 0; $i < $numReviews; $i++) {
            // Pick a random reviewer we haven't used for THIS restaurant
            do {
                $reviewer = $reviewers[array_rand($reviewers)];
            } while (in_array($reviewer['id'], $usedReviewers));
            
            $usedReviewers[] = $reviewer['id'];
            
            $rating = rand(3, 5); // Mostly good reviews for the seed
            $text = $comments[$rating][array_rand($comments[$rating])];
            $date = date('Y-m-d', strtotime('-' . rand(1, 180) . ' days'));
            
            $stmtInsert->execute([
                $r['id'],
                $reviewer['id'],
                $reviewer['name'],
                $rating,
                $text,
                $date
            ]);
            $count++;
        }
    }

    echo "Success! Added $count reviews to the database.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
