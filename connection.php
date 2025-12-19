<?php
// connection.php - Improved version
$host = 'localhost';
$dbname = 'car_preferences';
$username = 'root';
$password = '';  // Change only if you set a password

try {
    // First connect without database name
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $pdo->exec("USE `$dbname`");

    // Create banners table
    $pdo->exec("CREATE TABLE IF NOT EXISTS banners (
        id INT AUTO_INCREMENT PRIMARY KEY,
        image_path VARCHAR(255) NOT NULL,
        title VARCHAR(255),
        subtitle VARCHAR(255),
        link VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create cars table
    $pdo->exec("CREATE TABLE IF NOT EXISTS cars (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        price VARCHAR(100),
        description TEXT,
        section ENUM('most_searched', 'latest') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

} catch (PDOException $e) {
    // Show friendly error during setup
    die("Database Error: " . $e->getMessage() . "<br><br>Check if MySQL is running and credentials are correct.");
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>