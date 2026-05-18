<?php
$hosts = ['127.0.0.1', 'localhost'];
foreach ($hosts as $h) {
    try {
        echo "Testing $h...\n";
        $p = new PDO("mysql:host=$h", "root", "");
        echo "SUCCESS with $h\n";
        exit(0);
    } catch (Exception $e) {
        echo "FAILED with $h: " . $e->getMessage() . "\n";
    }
}
exit(1);
?>
