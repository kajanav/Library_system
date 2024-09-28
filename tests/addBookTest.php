<?php

namespace Tests;

use PDO;
use PDOException;

class addBookTest extends databaseTestCase
{
    public function testAddBookWithValidDetails(): void
    {
        $title = 'In Class Activity';
        $author = 'Subodi';
        $year = '2024';
        $pdf = 'In Class Activity.pdf';

        $sql = "INSERT INTO books (title, author, year, pdf) VALUES (:title, :author, :year, :pdf)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':pdf', $pdf);

        try {
            $stmt->execute();
            $this->assertTrue(true); // Test passes if no exception
        } catch (PDOException $e) {
            $this->fail('Adding book failed: ' . $e->getMessage());
        }

        // Check book inserted
        $query = "SELECT * FROM books WHERE title = :title";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->execute();
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($book, 'Book should exist in the database');
        $this->assertEquals($title, $book['title'], 'Title should match');
        $this->assertEquals($author, $book['author'], 'Author should match');
        $this->assertEquals($year, $book['year'], 'Year should match');
    }

    public function testEmptyBookFields(): void
{
    // Prepare NULL values for book fields
    $title = null;
    $author = null;
    $year = null;
    $pdf = null;

    // Prepare the SQL statement
    $sql = "INSERT INTO books (title, author, year, pdf) VALUES (:title, :author, :year, :pdf)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':title', $title, PDO::PARAM_NULL);
    $stmt->bindParam(':author', $author, PDO::PARAM_NULL);
    $stmt->bindParam(':year', $year, PDO::PARAM_NULL);
    $stmt->bindParam(':pdf', $pdf, PDO::PARAM_NULL);

    try {
        // Attempt to execute the statement
        $stmt->execute();
        // If execute does not throw an exception, the test should fail
        $this->fail('Expected failure on empty fields did not occur. Insert should not have succeeded.');
    } catch (PDOException $e) {
        // Assert that the exception is related to the NOT NULL constraint
        $this->assertStringContainsString('SQLSTATE[23000]', $e->getMessage(), 'Expected a NOT NULL constraint violation.');
    }
}



public function testDuplicateBookTitle(): void
{
    $title = 'In Class Activity';
    $author = 'Another Author';
    $year = 2023;  // Use integer instead of string for year
    $pdf = 'Activity.pdf';

    // Insert the first time
    $sql = "INSERT INTO books (title, author, year, pdf) VALUES (:title, :author, :year, :pdf)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':pdf', $pdf);

    try {
        $stmt->execute();
    } catch (PDOException $e) {
        $this->fail('Initial adding of book failed: ' . $e->getMessage());
    }

    // Attempt to insert the same book again
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':pdf', $pdf);

    try {
        $stmt->execute();
        // Fail if the second insert is successful
        $this->fail('Duplicate book title should not allow adding');
    } catch (PDOException $e) {
        // Assert that the exception message contains SQLSTATE[23000] indicating a unique constraint violation
        $this->assertStringContainsString('SQLSTATE[23000]', $e->getMessage(), 'Expected duplicate book title violation.');
    }

    // Clean up: Remove the inserted book after the test
    $deleteSql = "DELETE FROM books WHERE title = :title";
    $deleteStmt = $this->conn->prepare($deleteSql);
    $deleteStmt->bindParam(':title', $title);
    $deleteStmt->execute();
}


    public function testSuccessfulBookAddition(): void
    {
        $title = 'In Class Activity';
        $author = 'Subodi';
        $year = '2024';
        $pdf = 'In Class Activity.pdf';

        $sql = "INSERT INTO books (title, author, year, pdf) VALUES (:title, :author, :year, :pdf)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':pdf', $pdf);

        try {
            $stmt->execute();
            $this->assertTrue(true, 'Book added successfully');
        } catch (PDOException $e) {
            $this->fail('Adding book failed: ' . $e->getMessage());
        }

        // Verify the book exists
        $query = "SELECT * FROM books WHERE title = :title";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->execute();

        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($book, 'Book should exist in the database');
        $this->assertEquals($title, $book['title'], 'Title should match');
        $this->assertEquals($author, $book['author'], 'Author should match');
        $this->assertEquals($year, $book['year'], 'Year should match');
    }
}
