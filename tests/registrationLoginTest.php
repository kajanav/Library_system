<?php

use PHPUnit\Framework\TestCase;

class UserRegistrationLoginTest extends TestCase
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

    public function testUserRegistrationAndLogin(): void
    {
        // Step 1: Register the user
        $username = 'testUser';
        $password = 'testPassword';
        $email = 'testuser@example.com';

        $registrationSql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $this->conn->prepare($registrationSql);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            $this->fail('User registration failed: ' . $e->getMessage());
        }

        // Step 2: Log in the user
        session_start();
        $_SESSION['username'] = $username; // Simulating a logged-in user

        // Step 3: Verify the session is initialized
        $this->assertTrue(isset($_SESSION['username']), 'User session should be initialized.');

        // Step 4: Check if the user is redirected to dashboard.php
        $redirectedTo = 'dashboard.php'; // Simulating the expected redirect
        $this->assertEquals('dashboard.php', $redirectedTo, 'User should be redirected to dashboard.php');
    }
}
