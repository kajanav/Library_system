<?php
include 'db.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if the 'id' parameter exists
if (isset($_GET['id'])) {
    // Get the book ID from the URL
    $book_id = $_GET['id'];

    // Prepare the SQL DELETE query
    $sql = "DELETE FROM books WHERE id = :id";
    $statement = $conn->prepare($sql);

    // Bind the ID value
    $statement->bindParam(':id', $book_id, PDO::PARAM_INT);

    // Execute the deletion
    if ($statement->execute()) {
        // If deletion is successful, redirect back to the dashboard
        header("Location: dashboard.php?message=Book+deleted+successfully");
        exit;
    } else {
        // If deletion fails, show an error
        echo "Error deleting book.";
    }
} else {
    // If 'id' is not set, redirect to the dashboard
    header("Location: dashboard.php?error=Invalid+book+ID");
    exit;
}
?>
