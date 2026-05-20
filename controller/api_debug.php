<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');
session_start();
echo json_encode([
    'session' => $_SESSION,
    'id' => session_id(),
    'db_user' => isset($_SESSION['user_id']) ? $cnx->query("SELECT * FROM users WHERE id = " . intval($_SESSION['user_id']))->fetch(PDO::FETCH_ASSOC) : null,
    'user_reviews_in_db' => isset($_SESSION['user_id']) ? $cnx->query("SELECT id FROM reviews WHERE user_id = " . intval($_SESSION['user_id']))->fetchAll(PDO::FETCH_ASSOC) : []
]);
?>




