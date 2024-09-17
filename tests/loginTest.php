<?php

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    protected $pdo;

    protected function setUp(): void
    {
        // Mock a PDO connection
        $this->pdo = $this->getMockBuilder(PDO::class)
                          ->disableOriginalConstructor()
                          ->getMock();
    }

    public function testValidLogin()
    {
        // Arrange
        $username = 'testuser';
        $password = 'testpassword';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Simulate database result
        $user = [
            'id' => 1,
            'username' => $username,
            'password' => $hashedPassword
        ];

        // Set up the PDO mock
        $stmt = $this->getMockBuilder(PDOStatement::class)
                     ->disableOriginalConstructor()
                     ->getMock();
        $stmt->expects($this->once())
             ->method('execute');
        $stmt->expects($this->once())
             ->method('fetch')
             ->willReturn($user);

        // Mock the prepared statement
        $this->pdo->expects($this->once())
                  ->method('prepare')
                  ->willReturn($stmt);

        // Act
        // Simulate login (invoke the login function and assert session)
        $_REQUEST['uname'] = $username;
        $_REQUEST['password1'] = $password;
        $_SESSION = [];

        // Include the login script here to test its behavior.
        include 'login.php';

        // Assert
        $this->assertEquals($_SESSION['username'], $username);
        $this->assertEquals($_SESSION['id'], $user['id']);
    }

    public function testInvalidLogin()
    {
        // Arrange
        $username = 'invaliduser';
        $password = 'invalidpassword';

        // Simulate empty result (user not found)
        $stmt = $this->getMockBuilder(PDOStatement::class)
                     ->disableOriginalConstructor()
                     ->getMock();
        $stmt->expects($this->once())
             ->method('execute');
        $stmt->expects($this->once())
             ->method('fetch')
             ->willReturn(false);

        // Mock the prepared statement
        $this->pdo->expects($this->once())
                  ->method('prepare')
                  ->willReturn($stmt);

        // Act
        $_REQUEST['uname'] = $username;
        $_REQUEST['password1'] = $password;
        $_SESSION = [];

        // Include the login script here to test its behavior.
        include 'login.php';

        // Assert
        $this->assertContains('username or password is incorrect.!', $_SESSION['messages']);
    }
}
