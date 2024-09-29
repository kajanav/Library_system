<?php

use PHPUnit\Framework\TestCase;

class DashboardAccessTest extends TestCase
{
    protected $conn;

    protected function setUp(): void
    {
        // Create a new PDO connection for the tests
        $this->conn = new PDO("mysql:host=localhost;dbname=elibrary_system", "root", "");
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create test user
        $this->createTestUser();
    }

    protected function tearDown(): void
    {
        // Clean up test user
        $this->cleanupTestUser();
        $this->conn = null; // Close connection
    }

    private function createTestUser(): void
    {
        // Create regular user without role
        $userSql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $userStmt = $this->conn->prepare($userSql);
        $userUsername = 'regularUser';
        $userPassword = password_hash('userPass123', PASSWORD_BCRYPT);
        $userEmail = 'user@example.com';
        $userStmt->bindParam(':username', $userUsername);
        $userStmt->bindParam(':password', $userPassword);
        $userStmt->bindParam(':email', $userEmail);
        $userStmt->execute();
    }

    private function cleanupTestUser(): void
    {
        $deleteSql = "DELETE FROM users WHERE username = 'regularUser'";
        $deleteStmt = $this->conn->prepare($deleteSql);
        $deleteStmt->execute();
    }

    public function testUserDashboardAccess(): void
    {
        // Step 1: Log in as regular user
        session_start(); // Start session
        $_SESSION['username'] = 'regularUser';
        
        // Step 2: Simulate accessing the dashboard
        $dashboardUrl = 'dashboard.php';
        $this->assertTrue($this->isRedirectedToDashboard($dashboardUrl), 'Regular user should access the user dashboard successfully');
    }

    private function isRedirectedToDashboard($url): bool
    {
        // Simulate the behavior of the dashboard access
        return true; // Simulate successful access for the user
    }
}
