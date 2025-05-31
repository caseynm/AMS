<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'ams_user');
define('DB_PASS', 'password'); // Please change this password
define('DB_NAME', 'ams_db');

// Create a PDO database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>
