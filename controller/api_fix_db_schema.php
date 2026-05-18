<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/response.php';

header('Content-Type: application/json');
setCorsHeaders();
handleCorsPreFlight();

try {
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
} catch (Exception $e) {
    error_log("Alter table error: " . $e->getMessage());
    sendError($e->getMessage(), 500);
}
?>
