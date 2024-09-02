<?php
$host = "localhost"; // or the hostname of your MySQL server
$dbname = "user_roles"; // replace with your actual database name
$username = "root"; // replace with your MySQL username
$password = ""; // replace with your MySQL password

try {
    // Create a PDO instance (connect to the database)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Report any error
    echo "Connection failed: " . $e->getMessage();
}
