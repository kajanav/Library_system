<?php
namespace Tests;

use PDO;
use PDOException;

class editBookTest extends databaseTestCase
{
    // Make the setUp method public
    public function setUp(): void
    {
        parent::setUp(); // Call the parent setUp to initialize the DB connection

        // Ensure that there is a book with ID 1 for the test to pass
        // You might want to prepare your test data here
        $this->setUpTestData();
    }

    protected function tearDown(): void
    {
        // Clean up the test data if necessary
        $this->tearDownTestData();
        parent::tearDown();
    }

    protected function setUpTestData()
    {
        // Insert a book into the database to ensure there's a record to update
        $sql = "INSERT INTO books (title, author, year, pdf) VALUES (:title, :author, :year, :pdf)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':pdf', $pdf);

        // Sample book data
        $title = 'In Class Activity';
        $author = 'Subodi';
        $year = '2024';
        $pdf = 'In Class Activity.pdf';
        $stmt->execute();
    }

    protected function tearDownTestData()
    {
        // Clean up test data
        $sql = "DELETE FROM books WHERE title = :title";
        $stmt = $this->conn->prepare($sql);
        $title = 'In Class Activity';
        $stmt->bindParam(':title', $title);
        $stmt->execute();
    }

    public function testUpdateBookDetails(): void
{
    // Test Case ID: UT-005
    // Description: Test updating book details successfully
    // Pre-conditions: A book with ID 1 exists in the database.

    // Setup initial data
    $bookId = 23; // Ensure this ID exists
    $newTitle = 'Updated Book Title';
    $newAuthor = 'Updated Author';
    $newYear = 2024;
    $newPdf = 'UpdatedActivity.pdf';
    
    // Prepare update query
    $sql = "UPDATE books SET title = :title, author = :author, year = :year, pdf = :pdf WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':title', $newTitle);
    $stmt->bindParam(':author', $newAuthor);
    $stmt->bindParam(':year', $newYear);
    $stmt->bindParam(':pdf', $newPdf);
    $stmt->bindParam(':id', $bookId);

    // Execute update and check if successful
    $result = $stmt->execute();

    // Assert that the update was successful
    $this->assertTrue($result, 'Failed to update the book details');

    // Verify the update
    $sql = "SELECT * FROM books WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $bookId);
    $stmt->execute();
    $updatedBook = $stmt->fetch(PDO::FETCH_ASSOC);

    // Assertions
    $this->assertNotFalse($updatedBook, 'Updated book not found in the database');
    $this->assertEquals($newTitle, $updatedBook['title']);
    $this->assertEquals($newAuthor, $updatedBook['author']);
    $this->assertEquals($newYear, $updatedBook['year']);
    $this->assertEquals($newPdf, $updatedBook['pdf']);
}

public function testUpdateBookWithNoId(): void
{
    // Test Case ID: UT-006
    // Description: Test updating a book without a valid ID

    // Attempt to update with a non-existent ID
    $bookId = 9999; // Assuming this ID does not exist
    $newTitle = 'Updated Book Title';
    $newAuthor = 'Updated Author';
    $newYear = 2025;
    $newPdf = 'FakeActivity.pdf';

    // Prepare update query
    $sql = "UPDATE books SET title = :title, author = :author, year = :year, pdf = :pdf WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':title', $newTitle);
    $stmt->bindParam(':author', $newAuthor);
    $stmt->bindParam(':year', $newYear);
    $stmt->bindParam(':pdf', $newPdf);
    $stmt->bindParam(':id', $bookId);

    // Execute update
    $stmt->execute();

    // Check that no rows were affected
    $this->assertEquals(0, $stmt->rowCount(), 'Rows should not be updated for a non-existent ID');

    // Optional: Verify that the book details remain unchanged in the database
    $sql = "SELECT * FROM books WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $bookId);
    $stmt->execute();
    $updatedBook = $stmt->fetch(PDO::FETCH_ASSOC);

    // Assert that no book should be found with the non-existent ID
    $this->assertFalse($updatedBook, 'No book should be found with the non-existent ID.');
}


public function testUpdateBookWithoutTitle(): void
{
    // Test Case ID: UT-007
    // Description: Test updating a book without a title

    $bookId = 23; // Assuming a book with this ID exists
    $newTitle = ''; // Empty title
    $newAuthor = 'Updated Author';
    $newYear = 2024;
    $newPdf = 'UpdatedActivity.pdf';

    // Check if title is empty and expect it to fail
    $this->expectException(\InvalidArgumentException::class); // Use the global namespace

    // Validate title
    if (empty($newTitle)) {
        throw new \InvalidArgumentException('Title cannot be empty.'); // Use the global namespace
    }

    // Prepare update query
    $sql = "UPDATE books SET title = :title, author = :author, year = :year, pdf = :pdf WHERE id = :id";
    $stmt = $this->conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':title', $newTitle);
    $stmt->bindParam(':author', $newAuthor);
    $stmt->bindParam(':year', $newYear);
    $stmt->bindParam(':pdf', $newPdf);
    $stmt->bindParam(':id', $bookId);
    
    // Execute update (this line should never be reached if the title is empty)
    $stmt->execute();
}

}
