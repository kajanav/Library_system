<?php

namespace Tests;

use PDO;
use PDOException;

class loginTest extends databaseTestCase
{
    public function setUp(): void
    {
        parent::setUp(); // Ensure parent setup is called
        
        // Insert a test user to use for the login tests
        $username = 'kaja1';
        $password = password_hash('password345', PASSWORD_BCRYPT);
        $email = 'kaja1@example.com';

        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userExists) {
            $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
        }
    }

    public function testValidLogin(): void
    {
        $username = 'kaja1';
        $password = 'password345'; // Use the original password (not hashed)

        // Simulate login logic
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($user, 'User should exist in the database');

        // Verify the password
        $this->assertTrue(password_verify($password, $user['password']), 'Password should match');

        // Set session variables (simulating a successful login)
        $_SESSION['username'] = $user['username'];
        $_SESSION['id'] = $user['id'];

        $this->assertSame($username, $_SESSION['username']);
        $this->assertSame($user['id'], $_SESSION['id']);
    }

    public function testInvalidLogin(): void
    {
        $username = 'wronguser'; // Use a username that should not exist
        $password = 'password123'; // Use an incorrect password

        // Simulate login logic
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Assert that the user should not exist
        $this->assertEmpty($user, 'User should not exist');

        // Simulate login failure
        $_SESSION['messages'] = []; // Ensure it's an array
        if (empty($user) || !password_verify($password, $user['password'])) {
            $_SESSION['messages'][] = 'username or password is incorrect!'; // Add message to array
        }

        // Assert that the failure message is set correctly
        $this->assertContains('username or password is incorrect!', $_SESSION['messages']);
    }

    public function testEmptyLoginFields(): void
    {
        $username = '';
        $password = '';

        // Initialize messages as an array
        $_SESSION['messages'] = []; // Ensure it's an array

        // Check if fields are empty
        if (empty($username) || empty($password)) {
            $_SESSION['messages'][] = 'username or password is incorrect!'; // Add message to array
        }

        // Assert that the failure message is set correctly
        $this->assertContains('username or password is incorrect!', $_SESSION['messages']);
    }

    protected function tearDown(): void
    {
        // Clean up test user from the database after tests
        $sql = "DELETE FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        
        // Ensure this matches the username created in the setup
        $usernameToDelete = 'kaja1'; // Change to the appropriate test username if necessary
        $stmt->bindParam(':username', $usernameToDelete);
        $stmt->execute();
        
        parent::tearDown(); // Call parent teardown if needed
    }
}
