<?php

use PHPUnit\Framework\TestCase;

class AddBookIntegrationTest extends TestCase
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

    public function testAdminCanAddBookAndViewIt(): void
    {
        
        session_start();
        $_SESSION['username'] = 'adminUser'; 
        $_SESSION['role'] = 'admin'; 

        
        $bookTitle = 'New Test Book';
        $bookAuthor = 'Test Author';
        $bookYear = 2024;
        $bookPdf = 'path/to/newtestbook.pdf';

        
        $insertSql = "INSERT INTO books (title, author, year, pdf) VALUES (:title, :author, :year, :pdf)";
        $stmt = $this->conn->prepare($insertSql);
        $stmt->bindParam(':title', $bookTitle);
        $stmt->bindParam(':author', $bookAuthor);
        $stmt->bindParam(':year', $bookYear);
        $stmt->bindParam(':pdf', $bookPdf);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            $this->fail('Failed to add book: ' . $e->getMessage());
        }

        
        $query = "SELECT * FROM books WHERE title = :title";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $bookTitle);
        $stmt->execute();
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

       
        $this->assertNotEmpty($book, 'Book should be saved in the database');
        $this->assertEquals($bookTitle, $book['title'], 'Book title should match');
        $this->assertEquals($bookAuthor, $book['author'], 'Book author should match');
        $this->assertEquals($bookYear, $book['year'], 'Book year should match');
        $this->assertEquals($bookPdf, $book['pdf'], 'Book PDF path should match');

        // Step 6: Navigate to view_book.php (simulated)
        $viewQuery = "SELECT * FROM books WHERE id = :id";
        $stmt = $this->conn->prepare($viewQuery);
        $stmt->bindParam(':id', $book['id']);
        $stmt->execute();
        $viewedBook = $stmt->fetch(PDO::FETCH_ASSOC);

        // Step 7: Verify that the book details can be viewed in view_book.php
        $this->assertNotEmpty($viewedBook, 'Viewed book should exist');
        $this->assertEquals($bookTitle, $viewedBook['title'], 'Viewed book title should match');
        $this->assertEquals($bookAuthor, $viewedBook['author'], 'Viewed book author should match');
        $this->assertEquals($bookYear, $viewedBook['year'], 'Viewed book year should match');
        $this->assertEquals($bookPdf, $viewedBook['pdf'], 'Viewed book PDF path should match');

        // Cleanup: Optionally delete the test book to maintain a clean database
        $deleteSql = "DELETE FROM books WHERE id = :id";
        $stmt = $this->conn->prepare($deleteSql);
        $stmt->bindParam(':id', $book['id']);
        $stmt->execute();
    }
}
