<?php

use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    protected $pdo;

    protected function setUp(): void
    {
        // Mock a PDO connection
        $this->pdo = $this->getMockBuilder(PDO::class)
                          ->disableOriginalConstructor()
                          ->getMock();
    }

    public function testEmptyFields()
    {
        // Arrange
        $_POST['uname'] = '';
        $_POST['password1'] = '';
        $_SESSION = [];

        // Act
        include 'register.php';  // Include your registration logic file

        // Assert
        $this->assertContains('Please fill all required fields!', $_SESSION['messages']);
    }

    public function testDuplicateUsername()
    {
        // Arrange
        $username = 'existinguser';
        $password = 'testpassword';

        // Simulate a database result that returns an existing user
        $user = [
            'username' => $username
        ];

        // Mock the PDO statement for SELECT query
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
        $_POST['uname'] = $username;
        $_POST['password1'] = $password;
        $_SESSION = [];

        include 'register.php';  // Include your registration logic file

        // Assert
        $this->assertContains('This username already added.!', $_SESSION['messages']);
    }

    public function testSuccessfulRegistration()
    {
        // Arrange
        $username = 'newuser';
        $password = 'testpassword';

        // Simulate empty result for SELECT (no duplicate user)
        $stmtSelect = $this->getMockBuilder(PDOStatement::class)
                           ->disableOriginalConstructor()
                           ->getMock();
        $stmtSelect->expects($this->once())
                   ->method('execute');
        $stmtSelect->expects($this->once())
                   ->method('fetch')
                   ->willReturn(false);

        // Mock the PDO statement for INSERT query
        $stmtInsert = $this->getMockBuilder(PDOStatement::class)
                           ->disableOriginalConstructor()
                           ->getMock();
        $stmtInsert->expects($this->once())
                   ->method('execute')
                   ->willReturn(true);

        // Mock the prepared statements
        $this->pdo->expects($this->exactly(2)) // First SELECT, then INSERT
                  ->method('prepare')
                  ->willReturnOnConsecutiveCalls($stmtSelect, $stmtInsert);

        // Act
        $_POST['uname'] = $username;
        $_POST['password1'] = $password;
        $_SESSION = [];

        include 'register.php';  // Include your registration logic file

        // Assert
        $this->assertContains('Thank you for your registration.!', $_SESSION['messages']);
    }
}
