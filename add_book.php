<?php
include 'db.php';
session_start();

// Ensure that the user is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';  // Ensure title is set
    $author = $_POST['author'] ?? '';  // Ensure author is set
    $year = $_POST['year'] ?? '';  // Ensure year is set

    // Validate that all fields are filled out
    if (!empty($title) && !empty($author) && !empty($year)) {
        // Prepare and execute the query using placeholders to prevent SQL injection
        $sql = "INSERT INTO books (title, author, year) VALUES (:title, :author, :year)";
        $statement = $conn->prepare($sql);

        // Bind values
        $statement->bindParam(':title', $title);
        $statement->bindParam(':author', $author);
        $statement->bindParam(':year', $year);

        // Execute and check for success
        if ($statement->execute()) {
            echo "New book added successfully";
        } else {
            // Fetch error info if the execution failed
            $errorInfo = $statement->errorInfo();
            echo "Error adding book: " . $errorInfo[2];
        }
    } else {
        echo "Please fill out all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Add Book</title>
</head>
<header>
    <div class="main">
        <nav class="navr navr-inverse1">
            <div class="navdiv1">
                <div class="logo">
                    <a href="#">Wisdom Woods Library</a>
                </div>
                <ul>
                    <li><a href="index.php"><button type="button"> Home</button></a></li>
                    <li><a href="login.php"><button type="button" class="active-btn">Log In</button></a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<body>
<div class="container">
    <form action="add_book.php" method="POST">
        <h1>Add a new book</h1>
        <div class="input-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" placeholder="Enter Title" required>
        </div>
        <div class="input-group">
            <label for="author">Author</label>
            <input type="text" id="author" name="author" placeholder="Enter Author" required>
        </div>
        <div class="input-group">
            <label for="year">Year</label>
            <input type="number" id="year" name="year" placeholder="Enter/Select Year" required>
        </div>
        <button type="submit">Add Book</button>
    </form>
</div>
<footer class="footer">
Software Testing - Group 06
</footer>
</body>
</html>
