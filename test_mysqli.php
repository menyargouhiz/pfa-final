<?php
$conn = new mysqli("127.0.0.1", "root", "", "database1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully with mysqli";
$conn->close();
?>
