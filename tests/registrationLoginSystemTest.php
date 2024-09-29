<?php

use PHPUnit\Framework\TestCase;

class RegistrationLoginSystemTest extends TestCase
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

    public function testValidUserRegistrationAndLogin(): void
{
    // Step 1: Prepare valid user details
    $username = 'validUser';
    $password = 'validPassword123';
    $email = 'validUser@example.com';
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Cleanup: Remove user if it exists (to avoid duplicate entry error)
    $deleteSql = "DELETE FROM users WHERE username = :username";
    $deleteStmt = $this->conn->prepare($deleteSql);
    $deleteStmt->bindParam(':username', $username);
    $deleteStmt->execute();

    // Step 2: Register the user
    $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':email', $email);

    try {
        $stmt->execute();
    } catch (PDOException $e) {
        $this->fail('User registration failed: ' . $e->getMessage());
    }

    // Step 3: Attempt to log in with the new credentials
    // Start session to handle login
    session_start();

    // Mock login logic
    $loginQuery = "SELECT * FROM users WHERE username = :username";
    $stmt = $this->conn->prepare($loginQuery);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify login
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['loggedin'] = true;
        $this->assertTrue($_SESSION['loggedin'], 'User should be logged in successfully');
    } else {
        $this->fail('Login failed: Invalid credentials');
    }

    // Cleanup: Remove user after test
    $deleteSql = "DELETE FROM users WHERE username = :username";
    $deleteStmt = $this->conn->prepare($deleteSql);
    $deleteStmt->bindParam(':username', $username);
    $deleteStmt->execute();
}


    public function testInvalidLogin(): void
    {
        // Step 1: Prepare invalid credentials
        $username = 'invalidUser';
        $password = 'wrongPassword';

        session_start(); // Start session to handle login
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;

        // Step 2: Attempt to log in with invalid credentials
        $loginQuery = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($loginQuery);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify login
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
        } else {
            $_SESSION['loggedin'] = false;
            $this->assertFalse($_SESSION['loggedin'], 'User should not be logged in with invalid credentials');
            return; // Exit as we expect this to fail
        }

        // If we reach here, the login was incorrectly considered valid
        $this->fail('Login should have failed for invalid credentials');
    }
}
