<?php

$servername = "localhost";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $databaseName = "elibrary_system";  // Corrected typo from 'elibirary_system' to 'elibrary_system'
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $databaseName");
    $pdo->exec("USE $databaseName");
    
    echo "Database created and selected successfully";
} catch (PDOException $e) {
    echo "Error creating database: " . $e->getMessage();
}

// Close connection
$pdo = null;

// Replace these with your database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elibrary_system";  // Corrected typo from 'elibirary_system'

try {
    // Create connection to the database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the 'users' table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(255) UNIQUE NOT NULL,
        email VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);

    // Create the 'books' table
    $sql = "CREATE TABLE IF NOT EXISTS books (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        year INT NOT NULL,
        available BOOLEAN DEFAULT TRUE,
        pdf VARCHAR(255) NULL 
    )";
    $conn->exec($sql);

    // Create the 'borrowers' table with foreign key reference to 'books'
    $sql = "CREATE TABLE IF NOT EXISTS borrowers (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        book_id INT,
        borrow_date DATE,
        return_date DATE,
        FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE SET NULL
    )";
    $conn->exec($sql);

    echo "Tables created successfully";
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}

// Close connection
$conn = null;
?>

