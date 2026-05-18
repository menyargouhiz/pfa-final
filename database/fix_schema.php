<?php
require_once __DIR__ . '/../config/database.php';

try {
    echo "Dropping old reviews table...\n";
    $cnx->exec("DROP TABLE IF EXISTS reviews");
    echo "Done.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
