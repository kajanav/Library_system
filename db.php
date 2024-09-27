<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    if (defined('PHPUNIT_RUNNING')) {
        // Connect to the test database
        $dbname = "elibrary_test";
    } else {
        // Connect to the production database
        $dbname = "elibrary_system";
    }

    // Create a new PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optional: Enable UTF-8 encoding
    $conn->exec("set names utf8");

} catch (PDOException $e) {
    // Log the error to a file and display a user-friendly message
    error_log("Connection failed: " . $e->getMessage(), 3, "error_log.log");
    die("Connection failed. Please try again later.");
}
?>
