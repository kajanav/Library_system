<?php

use PHPUnit\Framework\TestCase;

class EditBookIntegrationTest extends TestCase
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

    public function testAdminCanEditBookAndViewIt(): void
    {
        // Step 1: Log in as an admin user
        session_start();
        $_SESSION['username'] = 'adminUser'; // Simulating an admin login
        $_SESSION['role'] = 'admin'; // Setting user role to admin

        // Step 2: Prepare the initial book details for testing
        $originalTitle = 'Original Test Book';
        $originalAuthor = 'Original Author';
        $originalYear = 2020;
        $originalPdf = 'path/to/originaltestbook.pdf';

        // Step 3: Insert the original book into the database
        $insertSql = "INSERT INTO books (title, author, year, pdf) VALUES (:title, :author, :year, :pdf)";
        $stmt = $this->conn->prepare($insertSql);
        $stmt->bindParam(':title', $originalTitle);
        $stmt->bindParam(':author', $originalAuthor);
        $stmt->bindParam(':year', $originalYear);
        $stmt->bindParam(':pdf', $originalPdf);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            $this->fail('Failed to add original book: ' . $e->getMessage());
        }

        // Step 4: Edit the book details
        $updatedTitle = 'Updated Test Book';
        $updatedAuthor = 'Updated Author';
        $updatedYear = 2024;
        $updatedPdf = 'path/to/updatedtestbook.pdf';

        $editSql = "UPDATE books SET title = :title, author = :author, year = :year, pdf = :pdf WHERE title = :originalTitle";
        $stmt = $this->conn->prepare($editSql);
        $stmt->bindParam(':title', $updatedTitle);
        $stmt->bindParam(':author', $updatedAuthor);
        $stmt->bindParam(':year', $updatedYear);
        $stmt->bindParam(':pdf', $updatedPdf);
        $stmt->bindParam(':originalTitle', $originalTitle);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            $this->fail('Failed to edit book: ' . $e->getMessage());
        }

        // Step 5: Verify the changes in view_book.php
        $query = "SELECT * FROM books WHERE title = :title";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $updatedTitle);
        $stmt->execute();
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        // Step 6: Check that the updated book details are correct
        $this->assertNotEmpty($book, 'Updated book should exist in the database');
        $this->assertEquals($updatedTitle, $book['title'], 'Updated book title should match');
        $this->assertEquals($updatedAuthor, $book['author'], 'Updated book author should match');
        $this->assertEquals($updatedYear, $book['year'], 'Updated book year should match');
        $this->assertEquals($updatedPdf, $book['pdf'], 'Updated book PDF path should match');

        // Cleanup: Optionally delete the test book to maintain a clean database
        $deleteSql = "DELETE FROM books WHERE id = :id";
        $stmt = $this->conn->prepare($deleteSql);
        $stmt->bindParam(':id', $book['id']);
        $stmt->execute();
    }
}
