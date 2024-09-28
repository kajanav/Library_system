<?php

namespace Tests;


use PDO; // Import PDO from the global namespace
use PDOException;

class deleteBookTest extends databaseTestCase
{
    public function setUp(): void
    {
        parent::setUp(); // Call the parent setup to ensure the connection is established
        $this->createTestBook(); // Create test book after connection is set up
    }

    protected function tearDown(): void
    {
        // Clean up: Remove the test book
        $this->deleteTestBook();
        parent::tearDown(); // Call the parent tear down
    }

    private function createTestBook()
    {
        // Create a test book with ID 1 for the test
        $sql = "INSERT INTO books (id, title, author, year, pdf) VALUES (1, 'Test Book', 'Test Author', 2024, 'test.pdf')";
        $this->conn->exec($sql);
    }

    private function deleteTestBook()
    {
        // Clean up the test book
        $sql = "DELETE FROM books WHERE id = 1";
        $this->conn->exec($sql);
    }

    public function testDeleteBook(): void
    {
        // Test Case ID: UT-008
        // Description: Test deleting a book successfully
        // Pre-conditions: A book with ID 1 exists in the database.

        // Assume this is the ID of the book to delete
        $bookId = 1; // Using the ID of the created book

        // Prepare the SQL DELETE query
        $sql = "DELETE FROM books WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);

        // Execute deletion
        $deleted = $stmt->execute();

        // Check if the deletion was successful
        $this->assertTrue($deleted, 'Book deletion failed.');

        // Verify that the book no longer exists in the database
        $sql = "SELECT * FROM books WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);
        $stmt->execute();
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        // Assert that the book does not exist
        $this->assertFalse($book, 'Book should have been deleted.');
    }
}
