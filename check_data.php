<?php
require_once 'config/database.php';
$stmt = $cnx->query('SELECT COUNT(*) as count FROM restaurants');
$result = $stmt->fetch();
echo 'Restaurants: ' . $result['count'] . "\n";
$stmt = $cnx->query('SELECT COUNT(*) as count FROM reviews');
$result = $stmt->fetch();
echo 'Reviews: ' . $result['count'] . "\n";
?>