<?php
$host = 'localhost'; // Database host
$dbname = 'dcms'; // Database name
$username = 'root'; // Database username (change as needed)
$password = ''; // Database password (change as needed)

try {
    // Create a new PDO instance
    $dbconn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set the PDO error mode to exception
    $dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>