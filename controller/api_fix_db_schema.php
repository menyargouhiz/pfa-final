<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/response.php';

header('Content-Type: application/json');
setCorsHeaders();
handleCorsPreFlight();

try {
<<<<<<< HEAD
    $tableExists = function ($table) use ($cnx) {
        $stmt = $cnx->prepare("
            SELECT COUNT(*)
            FROM information_schema.tables
            WHERE table_schema = DATABASE()
              AND table_name = ?
        ");
        $stmt->execute([$table]);
        return (int) $stmt->fetchColumn() > 0;
    };

    $columnExists = function ($table, $column) use ($cnx) {
        $stmt = $cnx->prepare("
            SELECT COUNT(*)
            FROM information_schema.columns
            WHERE table_schema = DATABASE()
              AND table_name = ?
              AND column_name = ?
        ");
        $stmt->execute([$table, $column]);
        return (int) $stmt->fetchColumn() > 0;
    };

    $indexExists = function ($table, $index) use ($cnx) {
        $stmt = $cnx->prepare("
            SELECT COUNT(*)
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = ?
              AND index_name = ?
        ");
        $stmt->execute([$table, $index]);
        return (int) $stmt->fetchColumn() > 0;
    };

    $applied = [];

    if (!$tableExists('users')) {
        $cnx->exec("
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $applied[] = 'created users table';
    } else {
        if (!$columnExists('users', 'password')) {
            $cnx->exec("ALTER TABLE users ADD COLUMN password VARCHAR(255) NOT NULL DEFAULT '' AFTER email");
            $applied[] = 'added users.password';
        } else {
            $cnx->exec("ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NOT NULL");
        }
    }

    if (!$tableExists('restaurants')) {
        $cnx->exec("
            CREATE TABLE restaurants (
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
                openHours VARCHAR(200)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $applied[] = 'created restaurants table';
    }

    if (!$tableExists('reviews')) {
        $cnx->exec("
            CREATE TABLE reviews (
                id INT AUTO_INCREMENT PRIMARY KEY,
                restaurant_id INT NOT NULL,
                user_id INT NULL,
                author VARCHAR(255) NOT NULL,
                rating INT NOT NULL DEFAULT 1,
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
                INDEX idx_reviews_facture_code (facture_code)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $applied[] = 'created reviews table';
    } else {
        $reviewColumns = [
            'restaurant_id' => "ADD COLUMN restaurant_id INT NOT NULL DEFAULT 0 AFTER id",
            'user_id' => "ADD COLUMN user_id INT NULL AFTER restaurant_id",
            'author' => "ADD COLUMN author VARCHAR(255) NOT NULL DEFAULT 'Anonymous' AFTER user_id",
            'rating' => "ADD COLUMN rating INT NOT NULL DEFAULT 1 AFTER author",
            'ambiance' => "ADD COLUMN ambiance INT NOT NULL DEFAULT 0 AFTER rating",
            'cleanliness' => "ADD COLUMN cleanliness INT NOT NULL DEFAULT 0 AFTER ambiance",
            'quality' => "ADD COLUMN quality INT NOT NULL DEFAULT 0 AFTER cleanliness",
            'service' => "ADD COLUMN service INT NOT NULL DEFAULT 0 AFTER quality",
            'text' => "ADD COLUMN text TEXT AFTER service",
            'facture_code' => "ADD COLUMN facture_code VARCHAR(100) DEFAULT NULL AFTER text",
            'date' => "ADD COLUMN date DATE NULL AFTER facture_code",
            'created_at' => "ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER date",
        ];

        foreach ($reviewColumns as $column => $alter) {
            if (!$columnExists('reviews', $column)) {
                $cnx->exec("ALTER TABLE reviews $alter");
                $applied[] = "added reviews.$column";
                if ($column === 'date') {
                    $cnx->exec("UPDATE reviews SET date = CURRENT_DATE WHERE date IS NULL");
                    $cnx->exec("ALTER TABLE reviews MODIFY COLUMN date DATE NOT NULL");
                }
            }
        }
    }

    $reviewIndexes = [
        'idx_reviews_restaurant_date' => '(restaurant_id, date)',
        'idx_reviews_user_date' => '(user_id, date)',
        'idx_reviews_rating' => '(rating)',
        'idx_reviews_facture_code' => '(facture_code)',
    ];
    foreach ($reviewIndexes as $index => $columns) {
        if (!$indexExists('reviews', $index)) {
            $cnx->exec("ALTER TABLE reviews ADD INDEX $index $columns");
            $applied[] = "added $index";
        }
    }
    if (!$indexExists('reviews', 'ft_reviews_text')) {
        $cnx->exec("ALTER TABLE reviews ADD FULLTEXT INDEX ft_reviews_text (author, text)");
        $applied[] = 'added ft_reviews_text';
    }

    if (!$tableExists('comments')) {
        $cnx->exec("
            CREATE TABLE comments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                review_id INT NOT NULL,
                user_id INT NULL,
                author VARCHAR(255) NOT NULL,
                text TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_comments_review_created (review_id, created_at),
                FULLTEXT INDEX ft_comments_text (author, text)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $applied[] = 'created comments table';
    } else if (!$indexExists('comments', 'ft_comments_text')) {
        $cnx->exec("ALTER TABLE comments ADD FULLTEXT INDEX ft_comments_text (author, text)");
        $applied[] = 'added ft_comments_text';
    }

    if (!$tableExists('favorites')) {
        $cnx->exec("
            CREATE TABLE favorites (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                restaurant_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_fav (user_id, restaurant_id),
                INDEX idx_favorites_restaurant (restaurant_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $applied[] = 'created favorites table';
    }

    if (!$tableExists('wishlist')) {
        $cnx->exec("
            CREATE TABLE wishlist (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                restaurant_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_wish (user_id, restaurant_id),
                INDEX idx_wishlist_restaurant (restaurant_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $applied[] = 'created wishlist table';
    }

    sendSuccess(['applied' => $applied], 'Database schema updated');
=======
    $cnx->exec("ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NOT NULL");

    $stmt = $cnx->prepare("
        SELECT COUNT(*)
        FROM information_schema.columns
        WHERE table_schema = DATABASE()
          AND table_name = 'reviews'
          AND column_name = 'facture_code'
    ");
    $stmt->execute();
    if ((int) $stmt->fetchColumn() === 0) {
        $cnx->exec("ALTER TABLE reviews ADD COLUMN facture_code VARCHAR(100) DEFAULT NULL AFTER text");
    }

    $stmt = $cnx->prepare("
        SELECT COUNT(*)
        FROM information_schema.statistics
        WHERE table_schema = DATABASE()
          AND table_name = 'reviews'
          AND index_name = 'idx_reviews_facture_code'
    ");
    $stmt->execute();
    if ((int) $stmt->fetchColumn() === 0) {
        $cnx->exec("ALTER TABLE reviews ADD INDEX idx_reviews_facture_code (facture_code)");
    }
    
    sendSuccess(['message' => 'Schema fixes applied'], 'Database schema updated');
>>>>>>> 9f33d9882a7e691571e96025575b7eef87d6352b
} catch (Exception $e) {
    error_log("Alter table error: " . $e->getMessage());
    sendError($e->getMessage(), 500);
}
?>




