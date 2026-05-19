<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/restaurant_images.php';

function indexExists(PDO $cnx, string $table, string $indexName): bool
{
    $stmt = $cnx->prepare("
        SELECT COUNT(*)
        FROM information_schema.statistics
        WHERE table_schema = DATABASE()
          AND table_name = ?
          AND index_name = ?
    ");
    $stmt->execute([$table, $indexName]);
    return (int) $stmt->fetchColumn() > 0;
}

function addIndexIfMissing(PDO $cnx, string $table, string $indexName, string $definition): void
{
    if (indexExists($cnx, $table, $indexName)) {
        echo "Index $indexName already exists.\n";
        return;
    }

    $cnx->exec("ALTER TABLE `$table` ADD $definition");
    echo "Added index $indexName.\n";
}

function getIndexColumns(PDO $cnx, string $table, string $indexName): array
{
    $stmt = $cnx->prepare("
        SELECT column_name
        FROM information_schema.statistics
        WHERE table_schema = DATABASE()
          AND table_name = ?
          AND index_name = ?
        ORDER BY seq_in_index
    ");
    $stmt->execute([$table, $indexName]);
    return array_map('strval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

function ensureFullTextIndex(PDO $cnx, string $table, string $indexName, array $columns): void
{
    $existingColumns = getIndexColumns($cnx, $table, $indexName);
    $definition = 'FULLTEXT INDEX `' . $indexName . '` (`' . implode('`, `', $columns) . '`)';

    if ($existingColumns === $columns) {
        echo "Index $indexName already exists.\n";
        return;
    }

    if ($existingColumns) {
        $cnx->exec("ALTER TABLE `$table` DROP INDEX `$indexName`");
        echo "Dropped outdated index $indexName.\n";
    }

    $cnx->exec("ALTER TABLE `$table` ADD $definition");
    echo "Added index $indexName.\n";
}

function columnExists(PDO $cnx, string $table, string $columnName): bool
{
    $stmt = $cnx->prepare("
        SELECT COUNT(*)
        FROM information_schema.columns
        WHERE table_schema = DATABASE()
          AND table_name = ?
          AND column_name = ?
    ");
    $stmt->execute([$table, $columnName]);
    return (int) $stmt->fetchColumn() > 0;
}

function addColumnIfMissing(PDO $cnx, string $table, string $columnName, string $definition): void
{
    if (columnExists($cnx, $table, $columnName)) {
        echo "Column $columnName already exists.\n";
        return;
    }

    $cnx->exec("ALTER TABLE `$table` ADD COLUMN $definition");
    echo "Added column $columnName.\n";
}

try {
    echo "Improving database indexation...\n";

    addIndexIfMissing($cnx, 'restaurants', 'idx_restaurants_category', 'INDEX `idx_restaurants_category` (`category`)');
    addIndexIfMissing($cnx, 'restaurants', 'idx_restaurants_city', 'INDEX `idx_restaurants_city` (`city`)');
    addIndexIfMissing($cnx, 'restaurants', 'idx_restaurants_price', 'INDEX `idx_restaurants_price` (`priceRange`)');
    addIndexIfMissing($cnx, 'restaurants', 'idx_restaurants_name', 'INDEX `idx_restaurants_name` (`name`)');
    addIndexIfMissing($cnx, 'restaurants', 'idx_restaurants_city_category', 'INDEX `idx_restaurants_city_category` (`city`, `category`)');
    ensureFullTextIndex($cnx, 'restaurants', 'ft_restaurants_search', ['name', 'cuisine', 'category', 'city', 'tags', 'description', 'address']);

    addIndexIfMissing($cnx, 'reviews', 'idx_reviews_restaurant_date', 'INDEX `idx_reviews_restaurant_date` (`restaurant_id`, `date`)');
    addIndexIfMissing($cnx, 'reviews', 'idx_reviews_user_date', 'INDEX `idx_reviews_user_date` (`user_id`, `date`)');
    addIndexIfMissing($cnx, 'reviews', 'idx_reviews_rating', 'INDEX `idx_reviews_rating` (`rating`)');
    addColumnIfMissing($cnx, 'reviews', 'facture_code', '`facture_code` VARCHAR(100) DEFAULT NULL AFTER `text`');
    addIndexIfMissing($cnx, 'reviews', 'idx_reviews_facture_code', 'INDEX `idx_reviews_facture_code` (`facture_code`)');

    addIndexIfMissing($cnx, 'comments', 'idx_comments_review_created', 'INDEX `idx_comments_review_created` (`review_id`, `created_at`)');
    addIndexIfMissing($cnx, 'favorites', 'idx_favorites_restaurant', 'INDEX `idx_favorites_restaurant` (`restaurant_id`)');
    addIndexIfMissing($cnx, 'wishlist', 'idx_wishlist_restaurant', 'INDEX `idx_wishlist_restaurant` (`restaurant_id`)');

    echo "Adding extra restaurants...\n";
    $restaurants = require __DIR__ . '/extra_restaurants.php';
    $restaurants = appetitus_assign_unique_restaurant_images($restaurants);

    $exists = $cnx->prepare("SELECT id FROM restaurants WHERE name = ? AND city = ? LIMIT 1");
    $insert = $cnx->prepare("
        INSERT INTO restaurants
            (name, cuisine, category, address, city, phone, priceRange, lat, lng, tags, image, description, openHours)
        VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $added = 0;
    $skipped = 0;
    $restaurantIdsForReviews = [];

    foreach ($restaurants as $restaurant) {
        $exists->execute([$restaurant['name'], $restaurant['city']]);
        $existingId = $exists->fetchColumn();
        if ($existingId) {
            $restaurantIdsForReviews[] = (int) $existingId;
            $skipped++;
            continue;
        }

        $insert->execute([
            $restaurant['name'],
            $restaurant['cuisine'],
            $restaurant['category'],
            $restaurant['address'],
            $restaurant['city'],
            $restaurant['phone'],
            $restaurant['priceRange'],
            $restaurant['lat'],
            $restaurant['lng'],
            $restaurant['tags'],
            $restaurant['image'],
            $restaurant['description'],
            $restaurant['openHours'],
        ]);
        $restaurantIdsForReviews[] = (int) $cnx->lastInsertId();
        $added++;
    }

    echo "Done. Added $added restaurants, skipped $skipped existing restaurants.\n";

    echo "Adding starter reviews where needed...\n";
    $users = $cnx->query("SELECT id, nom FROM users ORDER BY id ASC LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);
    $reviewTexts = [
        5 => [
            'Excellent meal and warm service. This place deserves more attention.',
            'Fresh ingredients, generous portions, and a really pleasant atmosphere.',
            'One of the better recent discoveries. I would happily return.',
        ],
        4 => [
            'Very good overall. The food was flavorful and the staff were helpful.',
            'Solid spot with a comfortable setting and reliable dishes.',
            'Good value and nice presentation. A strong choice for the area.',
        ],
    ];

    $reviewCount = 0;
    if ($users) {
        $reviewExists = $cnx->prepare("SELECT COUNT(*) FROM reviews WHERE restaurant_id = ?");
        $insertReview = $cnx->prepare("
            INSERT INTO reviews (restaurant_id, user_id, author, rating, text, facture_code, date)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        foreach ($restaurantIdsForReviews as $restaurantId) {
            $reviewExists->execute([$restaurantId]);
            if ((int) $reviewExists->fetchColumn() > 0) {
                continue;
            }

            $reviewers = array_slice($users, 0, min(3, count($users)));
            foreach ($reviewers as $offset => $user) {
                $rating = $offset === 0 ? 5 : 4;
                $textOptions = $reviewTexts[$rating];
                $date = date('Y-m-d', strtotime('-' . (14 + ($offset * 11)) . ' days'));
                $insertReview->execute([
                    $restaurantId,
                    $user['id'],
                    $user['nom'],
                    $rating,
                    $textOptions[$offset % count($textOptions)],
                    'SEED-' . $restaurantId . '-' . ($offset + 1),
                    $date,
                ]);
                $reviewCount++;
            }
        }
    }

    echo "Added $reviewCount starter reviews.\n";
    echo "Total restaurants: " . $cnx->query("SELECT COUNT(*) FROM restaurants")->fetchColumn() . "\n";
} catch (Exception $e) {
    http_response_code(500);
    echo "ERROR: " . $e->getMessage() . "\n";
}
