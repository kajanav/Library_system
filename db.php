<?php
$servername = "localhost";
$username = "root";
$password = "";

if (!defined('PHPUNIT_RUNNING')) {
try {
  $conn = new PDO("mysql:host=$servername;dbname=elibrary_system;charset=utf8", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // Optional: Enable UTF-8 encoding
  $conn->exec("set names utf8");
  // Test connection
  
} catch(PDOException $e) {
  error_log("Connection failed: " . $e->getMessage(), 3, "error_log.log");
  die("Connection failed. Please try again later.");
}
}
?>
