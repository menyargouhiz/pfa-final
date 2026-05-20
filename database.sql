-- Create Database
CREATE DATABASE IF NOT EXISTS `database1`;
USE `database1`;

-- Create Users Table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Restaurants Table
CREATE TABLE IF NOT EXISTS `restaurants` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `cuisine` VARCHAR(100),
    `category` VARCHAR(100),
    `address` TEXT,
    `city` VARCHAR(100),
    `phone` VARCHAR(50),
    `priceRange` VARCHAR(10),
    `lat` FLOAT,
    `lng` FLOAT,
    `tags` VARCHAR(255),
    `image` VARCHAR(500),
    `description` TEXT,
    `openHours` VARCHAR(100),
    INDEX `idx_restaurants_category` (`category`),
    INDEX `idx_restaurants_city` (`city`),
    INDEX `idx_restaurants_price` (`priceRange`),
    INDEX `idx_restaurants_name` (`name`),
    INDEX `idx_restaurants_city_category` (`city`, `category`),
<<<<<<< HEAD
    FULLTEXT INDEX `ft_restaurants_search` (`name`, `cuisine`, `category`, `city`, `tags`, `description`, `address`)
=======
<<<<<<< HEAD
    FULLTEXT INDEX `ft_restaurants_search` (`name`, `cuisine`, `category`, `city`, `tags`, `description`, `address`)
=======
    FULLTEXT INDEX `ft_restaurants_search` (`name`, `cuisine`, `tags`, `description`, `address`)
>>>>>>> df34791d4b40b7fc6586c4e6c6ecd09ede24f718
>>>>>>> 9f33d9882a7e691571e96025575b7eef87d6352b
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Reviews Table
CREATE TABLE IF NOT EXISTS `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `restaurant_id` INT NOT NULL,
    `user_id` INT,
    `author` VARCHAR(255) NOT NULL,
    `rating` INT NOT NULL,
    `ambiance` INT NOT NULL DEFAULT 0,
    `cleanliness` INT NOT NULL DEFAULT 0,
    `quality` INT NOT NULL DEFAULT 0,
    `service` INT NOT NULL DEFAULT 0,
    `date` DATE NOT NULL,
    `text` TEXT,
    `facture_code` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_reviews_restaurant_date` (`restaurant_id`, `date`),
    INDEX `idx_reviews_user_date` (`user_id`, `date`),
    INDEX `idx_reviews_rating` (`rating`),
    INDEX `idx_reviews_facture_code` (`facture_code`),
<<<<<<< HEAD
    FULLTEXT INDEX `ft_reviews_text` (`author`, `text`),
=======
>>>>>>> 9f33d9882a7e691571e96025575b7eef87d6352b
    FOREIGN KEY(`restaurant_id`) REFERENCES `restaurants`(`id`) ON DELETE CASCADE,
    FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Comments Table
CREATE TABLE IF NOT EXISTS `comments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `review_id` INT NOT NULL,
    `user_id` INT,
    `author` VARCHAR(255) NOT NULL,
    `text` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_comments_review_created` (`review_id`, `created_at`),
<<<<<<< HEAD
    FULLTEXT INDEX `ft_comments_text` (`author`, `text`),
=======
>>>>>>> 9f33d9882a7e691571e96025575b7eef87d6352b
    FOREIGN KEY(`review_id`) REFERENCES `reviews`(`id`) ON DELETE CASCADE,
    FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Favorites Table
CREATE TABLE IF NOT EXISTS `favorites` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `restaurant_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_fav` (`user_id`, `restaurant_id`),
    INDEX `idx_favorites_restaurant` (`restaurant_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Wishlist Table
CREATE TABLE IF NOT EXISTS `wishlist` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `restaurant_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_wish` (`user_id`, `restaurant_id`),
    INDEX `idx_wishlist_restaurant` (`restaurant_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
