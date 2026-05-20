<?php
$cnx = new PDO('mysql:host=127.0.0.1;port=3306;dbname=database1;charset=utf8mb4', 'root', '');
$stmt = $cnx->query("SELECT id,name,city,image FROM restaurants WHERE city LIKE '%Sousse%' OR name LIKE '%Blue%' OR name LIKE '%Net%' LIMIT 20");
foreach ($stmt as $row) {
    echo $row['id'] . '|' . $row['name'] . '|' . $row['city'] . '|' . $row['image'] . "\n";
}
