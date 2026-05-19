<?php
// Load .env if it exists
$envFile = __DIR__ . '/../config/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

$host = $_ENV['DB_HOST'] ?? "127.0.0.1";
$port = $_ENV['DB_PORT'] ?? 3306;
$user = $_ENV['DB_USER'] ?? "root";
$pass = $_ENV['DB_PASS'] ?? "";
$dbname = $_ENV['DB_NAME'] ?? "database1";

try {
    echo "Connecting to MySQL at $host:$port...\n";
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Creating database $dbname if not exists...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");
    
    // Users table
    echo "Creating users table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )");
    
    // Restaurants table
    echo "Creating restaurants table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS restaurants (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        cuisine VARCHAR(100),
        category VARCHAR(100),
        address TEXT,
        city VARCHAR(100),
        phone VARCHAR(50),
        priceRange VARCHAR(10),
        lat FLOAT,
        lng FLOAT,
        tags VARCHAR(255),
        image VARCHAR(500),
        description TEXT,
        openHours VARCHAR(200),
        INDEX idx_restaurants_category (category),
        INDEX idx_restaurants_city (city),
        INDEX idx_restaurants_price (priceRange),
        INDEX idx_restaurants_name (name),
        INDEX idx_restaurants_city_category (city, category),
        FULLTEXT INDEX ft_restaurants_search (name, cuisine, category, city, tags, description, address)
    )");
    
    // Reviews table
    echo "Creating reviews table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        restaurant_id INT NOT NULL,
        user_id INT,
        author VARCHAR(255) NOT NULL,
        rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
        ambiance INT NOT NULL DEFAULT 0,
        cleanliness INT NOT NULL DEFAULT 0,
        quality INT NOT NULL DEFAULT 0,
        service INT NOT NULL DEFAULT 0,
        text TEXT,
        facture_code VARCHAR(100) DEFAULT NULL,
        date DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_reviews_restaurant_date (restaurant_id, date),
        INDEX idx_reviews_user_date (user_id, date),
        INDEX idx_reviews_rating (rating),
        INDEX idx_reviews_facture_code (facture_code),
        FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )");
    
    // Comments table
    echo "Creating comments table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS comments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        review_id INT NOT NULL,
        user_id INT,
        author VARCHAR(255) NOT NULL,
        text TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_comments_review_created (review_id, created_at),
        FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )");
    
    // Favorites table
    echo "Creating favorites table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS favorites (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        restaurant_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_fav (user_id, restaurant_id),
        INDEX idx_favorites_restaurant (restaurant_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
    )");
    
    // Wishlist table
    echo "Creating wishlist table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS wishlist (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        restaurant_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_wish (user_id, restaurant_id),
        INDEX idx_wishlist_restaurant (restaurant_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
    )");
    
    echo "Database and tables ready.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
