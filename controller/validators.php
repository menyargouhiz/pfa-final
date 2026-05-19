<?php

/**
 * Input Validation Functions
 */

/**
 * Validate email format
 */
function validateEmail($email) {
    $email = trim($email);
    
    if (empty($email)) {
        return ['valid' => false, 'error' => 'Email is required'];
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['valid' => false, 'error' => 'Invalid email format'];
    }
    
    if (strlen($email) > 255) {
        return ['valid' => false, 'error' => 'Email is too long (max 255 characters)'];
    }
    
    return ['valid' => true];
}

/**
 * Validate password strength
 */
function validatePassword($password) {
    if (empty($password)) {
        return ['valid' => false, 'error' => 'Password is required'];
    }
    
    if (strlen($password) < 6) {
        return ['valid' => false, 'error' => 'Password must be at least 6 characters'];
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        return ['valid' => false, 'error' => 'Password must contain at least one number'];
    }
    
    if (strlen($password) > 255) {
        return ['valid' => false, 'error' => 'Password is too long'];
    }
    
    return ['valid' => true];
}

/**
 * Validate name/username
 */
function validateName($name) {
    $name = trim($name);
    
    if (empty($name)) {
        return ['valid' => false, 'error' => 'Name is required'];
    }
    
    if (strlen($name) < 2) {
        return ['valid' => false, 'error' => 'Name must be at least 2 characters'];
    }
    
    if (strlen($name) > 255) {
        return ['valid' => false, 'error' => 'Name is too long (max 255 characters)'];
    }
    
    // Check for SQL/HTML injection attempts
    if (preg_match('/[<>\"\'`;\\\\]/', $name)) {
        return ['valid' => false, 'error' => 'Name contains invalid characters'];
    }
    
    return ['valid' => true];
}

/**
 * Validate rating (1-5)
 */
function validateRating($rating) {
    $rating = intval($rating);
    
    if ($rating < 1 || $rating > 5) {
        return ['valid' => false, 'error' => 'Rating must be between 1 and 5'];
    }
    
    return ['valid' => true];
}

/**
 * Validate dimension rating (ambiance, cleanliness, quality, service)
 */
function validateDimensionRating($rating, $label) {
    $rating = intval($rating);
    
    if ($rating < 1 || $rating > 5) {
        return ['valid' => false, 'error' => "$label rating must be between 1 and 5"];
    }
    
    return ['valid' => true];
}

/**
 * Validate review text
 */
function validateReviewText($text) {
    $text = trim($text);
    
    if (empty($text)) {
        return ['valid' => false, 'error' => 'Review text is required'];
    }
    
    if (strlen($text) < 5) {
        return ['valid' => false, 'error' => 'Review must be at least 5 characters'];
    }
    
    if (strlen($text) > 5000) {
        return ['valid' => false, 'error' => 'Review is too long (max 5000 characters)'];
    }
    
    return ['valid' => true];
}

/**
 * Validate facture/receipt code used to verify a real visit.
 */
function validateFactureCode($code) {
    $code = trim($code);

    if (empty($code)) {
        return ['valid' => false, 'error' => 'Facture code is required'];
    }

    if (strlen($code) < 4) {
        return ['valid' => false, 'error' => 'Facture code must be at least 4 characters'];
    }

    if (strlen($code) > 100) {
        return ['valid' => false, 'error' => 'Facture code is too long (max 100 characters)'];
    }

    if (!preg_match('/^[A-Za-z0-9][A-Za-z0-9 .:_\/-]*$/', $code)) {
        return ['valid' => false, 'error' => 'Facture code contains invalid characters'];
    }

    return ['valid' => true];
}

/**
 * Sanitize string input
 */
function sanitizeString($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

<<<<<<< HEAD

=======
?>
>>>>>>> df34791d4b40b7fc6586c4e6c6ecd09ede24f718
