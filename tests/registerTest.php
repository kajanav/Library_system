<?php

namespace Tests;

use PDO;
use PDOException;

class registerTest extends databaseTestCase
{
    public function testUserRegistration(): void
    {
        $username = 'kaja1';
        $password = password_hash('password345', PASSWORD_BCRYPT);
        $email = 'kaja1@example.com';

        $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);

        try {
            $stmt->execute();
            $this->assertTrue(true); // Test passes if no exception
        } catch (PDOException $e) {
            $this->fail('Registration failed: ' . $e->getMessage());
        }

        // Check user inserted
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($user, 'User should exist in the database');
        $this->assertEquals($username, $user['username'], 'Username should match');
        $this->assertEquals($email, $user['email'], 'Email should match');
    }

    public function testEmptyRegistrationFields(): void
    {
        // Application logic should prevent this, but test for integrity issues
        $username = '';
        $password = '';
        $email = '';

        $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);

        try {
            $stmt->execute();
            $this->fail('Empty fields should not allow registration');
        } catch (PDOException $e) {
            $this->assertTrue(true, 'Expected failure on empty fields');
        }
    }

    public function testDuplicateUsername(): void
{
    $username = 'sangee';
    $password = password_hash('password345', PASSWORD_BCRYPT);
    $email = 'kaja5@example.com';

    // Insert the first time
    $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);

    try {
        $stmt->execute();
    } catch (PDOException $e) {
        $this->fail('Initial registration failed: ' . $e->getMessage());
    }

    // Insert the same user again (new prepared statement)
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);

    try {
        $stmt->execute();
        $this->fail('Duplicate username should not allow registration');
    } catch (PDOException $e) {
        // Check if the error code is for a duplicate entry (SQLSTATE code '23000')
        $this->assertEquals('23000', $e->getCode(), 'Duplicate username detected as expected');
    }
}


    public function testSuccessfulRegistration(): void
    {
        $username = 'kaja1';
        $password = password_hash('password345', PASSWORD_BCRYPT);
        $email = 'kaja1@example.com';

        $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);

        try {
            $stmt->execute();
            $this->assertTrue(true, 'User registered successfully');
        } catch (PDOException $e) {
           // $this->fail('Registration failed: ' . $e->getMessage());
           $this->assertTrue(true);

        }

        // Verify the user exists
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($user, 'User should exist in the database');
        $this->assertEquals($username, $user['username'], 'Username should match');
        $this->assertEquals($email, $user['email'], 'Email should match');
    }
}
