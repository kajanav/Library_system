<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    if (defined('PHPUNIT_RUNNING')) {
       
        $dbname = "elibrary_test";
    } else {
        
        $dbname = "elibrary_system";
    }

 
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn->exec("set names utf8");

} catch (PDOException $e) {
    
    error_log("Connection failed: " . $e->getMessage(), 3, "error_log.log");
    die("Connection failed. Please try again later.");
}
?>
