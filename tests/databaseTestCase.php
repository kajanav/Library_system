<?php

namespace Tests;

use PDO;
use PHPUnit\Framework\TestCase;

class databaseTestCase extends TestCase
{
    protected $conn;

    public function setUp(): void
    {
        parent::setUp();

        try {
            $this->conn = new PDO('mysql:host=localhost;dbname=elibrary_system', 'root', '');
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->fail('Connection failed: ' . $e->getMessage());
        }
    }
}
