<?php

$servername = "localhost";
$username = "root";
$password = "";

try {
  $pdo =new PDO("mysql:host=$servername",$username,$password);
  $pdo->setAttribute(PDO:: ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  $databaseName ="elibirary_system";
  $pdo->exec("CREATE DATABASE IF NOT EXISTs $databaseName");
  $pdo->exec("USE $databaseName");
    echo "Database created successfully";
} catch (PDOException $e) {
    echo "Error creating database: " . $e->getMessage();
}

$conn = null;
?>
<?php
// Replace these with your database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elibirary_system";

try {
    // Create connection to the database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the 'users' table
    $sql = "CREATE TABLE users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(255) UNIQUE NOT NULL,
        email VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )";

    $conn->exec($sql);
    $sql = "CREATE TABLE books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        year INT NOT NULL,
        available BOOLEAN DEFAULT TRUE
    );"
    
    $sql ="CREATE TABLE borrowers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        book_id INT,
        borrow_date DATE,
        return_date DATE,
        FOREIGN KEY (book_id) REFERENCES books(id)
    );"
    

    echo "Tables created successfully";
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}

$conn = null;
?>
