<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $year = $_POST['year'] ?? '';

    // Check if the PDF file is uploaded
    if (!empty($_FILES['pdf']['name'])) {
        $targetDir = "uploads/";  // Directory where PDF files will be stored
        $pdfFile = basename($_FILES["pdf"]["name"]);
        $targetFilePath = $targetDir . $pdfFile;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Check if the file is a valid PDF
        if ($fileType == 'pdf') {
            // Ensure the uploads directory exists
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true); // Create directory if it does not exist
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["pdf"]["tmp_name"], $targetFilePath)) {
                // Proceed to insert the book details and PDF path into the database
                if (!empty($title) && !empty($author) && !empty($year)) {
                    // Prepare and execute the query
                    $sql = "INSERT INTO books (title, author, year, pdf) VALUES (:title, :author, :year, :pdf)";
                    $statement = $conn->prepare($sql);
                    $statement->bindParam(':title', $title);
                    $statement->bindParam(':author', $author);
                    $statement->bindParam(':year', $year);
                    $statement->bindParam(':pdf', $pdfFile);

                    if ($statement->execute()) {
                        echo "New book added successfully";
                    } else {
                        echo "Error adding book";
                    }
                } else {
                    echo "Please fill out all fields.";
                }
            } else {
                echo "Error uploading the PDF file.";
            }
        } else {
            echo "Only PDF files are allowed.";
        }
    } else {
        echo "Please upload a PDF file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
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
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="dashboard.php">Home</a></li>
                <li class="nav-item" id="logout"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<body>
<div class="container">
<?php require_once 'message.php'; ?>
    <form action="add_book.php" id="form" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
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
