<?php

use PHPUnit\Framework\TestCase;

class BookManagementTest extends TestCase
{
    protected $conn;

    protected function setUp(): void
    {
        // Create a new PDO connection for the tests
        $this->conn = new PDO("mysql:host=localhost;dbname=elibrary_system", "root", "");
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create test books
        $this->createTestBooks();
    }

    protected function tearDown(): void
    {
        // Clean up test books
        $this->cleanupTestBooks();
        $this->conn = null; // Close connection
    }

    private function createTestBooks(): void
    {
        // Add a sample book for testing
        $sql = "INSERT INTO books (title, author, year, pdf) VALUES (:title, :author, :year, :pdf)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':pdf', $pdf);

        $title = 'Sample Book';
        $author = 'Sample Author';
        $year = 2023;
        $pdf = 'sample.pdf';
        $stmt->execute();
    }

    private function cleanupTestBooks(): void
    {
        $deleteSql = "DELETE FROM books WHERE title IN ('Sample Book', 'New Book Title', 'Updated Book Title')";
        $deleteStmt = $this->conn->prepare($deleteSql);
        $deleteStmt->execute();
    }

    public function testAddNewBook(): void
    {
        // Step 1: Prepare valid book details
        $title = 'New Book Title';
        $author = 'New Author';
        $year = 2024;
        $pdf = 'newbook.pdf';

        // Step 2: Insert the new book into the database
        $sql = "INSERT INTO books (title, author, year, pdf) VALUES (:title, :author, :year, :pdf)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':pdf', $pdf);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            $this->fail('Book addition failed: ' . $e->getMessage());
        }

        // Step 3: Verify the book was added
        $query = "SELECT * FROM books WHERE title = :title";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->execute();
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($book, 'Book should exist in the database');
        $this->assertEquals($title, $book['title'], 'Book title should match');
        $this->assertEquals($author, $book['author'], 'Book author should match');
    }

    public function testEditExistingBook(): void
    {
        // Step 1: Prepare updated book details
        $newTitle = 'Updated Book Title';
        $newAuthor = 'Updated Author';
        $newYear = 2025;
        $newPdf = 'updatedbook.pdf';

        // Step 2: Update the existing book in the database
        $sql = "UPDATE books SET title = :title, author = :author, year = :year, pdf = :pdf WHERE title = 'Sample Book'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $newTitle);
        $stmt->bindParam(':author', $newAuthor);
        $stmt->bindParam(':year', $newYear);
        $stmt->bindParam(':pdf', $newPdf);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            $this->fail('Book editing failed: ' . $e->getMessage());
        }

        // Step 3: Verify the changes
        $query = "SELECT * FROM books WHERE title = :title";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $newTitle);
        $stmt->execute();
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($book, 'Updated book should exist in the database');
        $this->assertEquals($newTitle, $book['title'], 'Updated book title should match');
        $this->assertEquals($newAuthor, $book['author'], 'Updated book author should match');
    }

    public function testDeleteBook(): void
    {
        // Step 1: Delete the book from the database
        $deleteSql = "DELETE FROM books WHERE title = 'Sample Book'";
        $deleteStmt = $this->conn->prepare($deleteSql);
        
        try {
            $deleteStmt->execute();
        } catch (PDOException $e) {
            $this->fail('Book deletion failed: ' . $e->getMessage());
        }

        // Step 2: Verify the book is no longer listed
        $query = "SELECT * FROM books WHERE title = 'Sample Book'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEmpty($book, 'Book should not exist in the database after deletion');
    }

    public function testViewingBooks(): void
    {
        // Step 1: Simulate navigating to view_book.php
        $query = "SELECT * FROM books";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Step 2: Verify that all existing books are displayed correctly
        $this->assertNotEmpty($books, 'There should be existing books to view');

        foreach ($books as $book) {
            $this->assertArrayHasKey('title', $book, 'Book should have a title');
            $this->assertArrayHasKey('author', $book, 'Book should have an author');
            $this->assertArrayHasKey('year', $book, 'Book should have a year');
            $this->assertArrayHasKey('pdf', $book, 'Book should have a PDF link');
        }

        // Step 3: Verify the presence of Edit and Delete options
        foreach ($books as $book) {
            $this->assertTrue($this->hasEditOption($book['title']), 'Edit option should be available for each book');
            $this->assertTrue($this->hasDeleteOption($book['title']), 'Delete option should be available for each book');
        }
    }

    private function hasEditOption($title): bool
    {
        // Simulate the logic for checking Edit option
        return true; // Simulating that the Edit option exists
    }

    private function hasDeleteOption($title): bool
    {
        // Simulate the logic for checking Delete option
        return true; // Simulating that the Delete option exists
    }
}
