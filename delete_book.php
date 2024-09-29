<?php
include 'db.php';
session_start();


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}


if (isset($_GET['id'])) {
    
    $book_id = $_GET['id'];

   
    $sql = "DELETE FROM books WHERE id = :id";
    $statement = $conn->prepare($sql);

   
    $statement->bindParam(':id', $book_id, PDO::PARAM_INT);

    if ($statement->execute()) {
       
        header("Location: dashboard.php?message=Book+deleted+successfully");
        exit;
    } else {
       
        echo "Error deleting book.";
    }
} else {
   
    header("Location: dashboard.php?error=Invalid+book+ID");
    exit;
}
?>
