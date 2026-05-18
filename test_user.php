<?php
$users = ['root', 'admin', 'appetitus', 'hiba', 'smarty'];
foreach ($users as $u) {
    try {
        echo "Testing user $u...\n";
        $p = new PDO("mysql:host=127.0.0.1", $u, "");
        echo "SUCCESS with user $u\n";
        exit(0);
    } catch (Exception $e) {
        echo "FAILED with user $u: " . $e->getMessage() . "\n";
    }
}
exit(1);
?>
