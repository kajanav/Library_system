<?php

use PHPUnit\Framework\TestCase;

class DeleteBookIntegrationTest extends TestCase
{
    protected $conn;

    protected function setUp(): void
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "elibrary_system";

        $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    protected function tearDown(): void
    {
        $this->conn = null;
    }

    public function testAdminCanDeleteBook(): void
    {
        // Step 1: Log in as an admin user
        session_start();
        $_SESSION['username'] = 'adminUser'; // Simulating an admin login
        $_SESSION['role'] = 'admin'; // Setting user role to admin

        // Step 2: Prepare the book details for testing
        $title = 'Test Book to Delete';
        $author = 'Test Author';
        $year = 2022;
        $pdf = 'path/to/testbook.pdf';

        // Step 3: Insert the book into the database
        $insertSql = "INSERT INTO books (title, author, year, pdf) VALUES (:title, :author, :year, :pdf)";
        $stmt = $this->conn->prepare($insertSql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':pdf', $pdf);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            $this->fail('Failed to add book for deletion test: ' . $e->getMessage());
        }

        // Step 4: Delete the book
        $deleteSql = "DELETE FROM books WHERE title = :title";
        $stmt = $this->conn->prepare($deleteSql);
        $stmt->bindParam(':title', $title);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            $this->fail('Failed to delete book: ' . $e->getMessage());
        }

        // Step 5: Verify the book is no longer listed
        $query = "SELECT * FROM books WHERE title = :title";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->execute();
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        // Step 6: Assert that the book does not exist in the database
        $this->assertEmpty($book, 'Book should no longer exist in the database after deletion');

        // Optional: Cleanup - Not needed here since the book has already been deleted
    }
}
