<?php
require_once 'config/database.php';
$cnx->exec('ALTER TABLE reviews ADD COLUMN IF NOT EXISTS user_id INT NULL AFTER restaurant_id');
try {
    $cnx->exec('ALTER TABLE reviews ADD CONSTRAINT fk_reviews_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
} catch (Exception $e) {
    // ignore if the foreign key already exists
}
$cnx->exec('ALTER TABLE reviews ADD COLUMN IF NOT EXISTS ambiance INT NOT NULL DEFAULT 0 AFTER rating');
$cnx->exec('ALTER TABLE reviews ADD COLUMN IF NOT EXISTS cleanliness INT NOT NULL DEFAULT 0 AFTER ambiance');
$cnx->exec('ALTER TABLE reviews ADD COLUMN IF NOT EXISTS quality INT NOT NULL DEFAULT 0 AFTER cleanliness');
$cnx->exec('ALTER TABLE reviews ADD COLUMN IF NOT EXISTS service INT NOT NULL DEFAULT 0 AFTER quality');
echo "Table altered.\n";
?>