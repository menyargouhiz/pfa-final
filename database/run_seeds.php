<?php
echo "<pre>";
echo "Running setup_db.php...\n";
include 'setup_db.php';
echo "\nRunning seed_all.php...\n";
include 'seed_all.php';
echo "</pre>";
?>
