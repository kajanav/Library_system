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
        pdf VARCHAR(255) NOT NULL 
    )";
    $conn->exec($sql);

    $sql = "ALTER TABLE books 
        MODIFY title VARCHAR(255) NOT NULL,
        MODIFY author VARCHAR(255) NOT NULL,
        MODIFY year INT NOT NULL,
        MODIFY pdf VARCHAR(255) NOT NULL";
    $conn->exec($sql);


    echo "Tables created successfully";
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}

// Close connection
$conn = null;

/**
 * Function to update book details
 *
 * @param PDO $conn Database connection
 * @param int $bookId The ID of the book to update
 * @param string $title The new title of the book
 * @param string $author The new author of the book
 * @param int $year The new year of publication
 * @param string $pdf The new PDF file path
 * @throws InvalidArgumentException if title is empty
 */
function updateBook($conn, $bookId, $title, $author, $year, $pdf) {
    if (empty($title)) {
        throw new InvalidArgumentException('Title cannot be empty.');
    }

    // Prepare update query
    $sql = "UPDATE books SET title = :title, author = :author, year = :year, pdf = :pdf WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':pdf', $pdf);
    $stmt->bindParam(':id', $bookId);
    
    // Execute the update
    $stmt->execute();
}

// Example of using the updateBook function
try {
    // Reconnect to the database to perform updates
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Update book details
    updateBook($conn, 1, 'Updated Book Title', 'Updated Author', 2024, 'UpdatedActivity.pdf');
    echo "Book updated successfully<br>";

} catch (PDOException $e) {
    echo "Error updating book: " . $e->getMessage();
} catch (InvalidArgumentException $e) {
    echo "Validation Error: " . $e->getMessage();
}

// Close connection
$conn = null;

?>