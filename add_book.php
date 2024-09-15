<?php
include 'db.php';



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
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
    <title>Add Book</title>
</head>
<header>
    <div class="main">
        <nav class="navr navr-inverse1">
            <div class="navdiv1">
            <div class="logo">
          <h2>Wisdom Woods Library</h2>
        </div>
                <ul class="nav nav-underline">
                    <li class="nav-item"><a href="dashboard.php"><button type="button"> Home</button></a></li>
                    <!-- <li><a href="login.php"><button type="button" class="active-btn">Log In</button></a></li> -->
                </ul>
            </div>
        </nav>
    </div>
</header>
<body>
<div class="container">
<?php require_once 'message.php'; ?>
    <form action="add_book.php" id="form" method="POST" onsubmit="return validateForm()">
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
        <div class="input-group">
        <label for="pdf">Upload PDF</label>
                <input type="file" id="pdf" name="pdf" accept=".pdf">
        </div>
        <button type="submit">Add Book</button>
    </form>
</div>
<footer class="footer">
Software Testing - Group 06
</footer>
</body>
<script src="./js/script1.js"></script>
</html>
