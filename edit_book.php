<?php
include 'db.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Get the book ID from the URL parameter
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // Fetch book details from the database
    $query = "SELECT * FROM books WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $book_id);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        // If book doesn't exist, redirect back to dashboard
        header("Location: dashboard.php?error=Book not found");
        exit;
    }
} else {
    header("Location: dashboard.php?error=No book selected");
    exit;
}

// Handle form submission for updating the book
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $available = isset($_POST['available']) ? 1 : 0;

    // Handle PDF file update
    if (!empty($_FILES['pdf']['name'])) {
        $pdf_name = basename($_FILES['pdf']['name']);
        $pdf_target = "uploads/" . $pdf_name;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['pdf']['tmp_name'], $pdf_target)) {
            $pdf = $pdf_name;
        } else {
            header("Location: edit_book.php?id=$book_id&error=Failed to upload PDF");
            exit;
        }
    } else {
        // Keep the current PDF if no new file is uploaded
        $pdf = $book['pdf'];
    }

    // Update the book in the database
    $update_query = "UPDATE books SET title = :title, author = :author, year = :year, available = :available, pdf = :pdf WHERE id = :id";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindParam(':title', $title);
    $update_stmt->bindParam(':author', $author);
    $update_stmt->bindParam(':year', $year);
    $update_stmt->bindParam(':available', $available);
    $update_stmt->bindParam(':pdf', $pdf);
    $update_stmt->bindParam(':id', $book_id);

    if ($update_stmt->execute()) {
        header("Location: dashboard.php?message=Book updated successfully");
        exit;
    } else {
        header("Location: edit_book.php?id=$book_id&error=Failed to update book");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <h1>Edit Book</h1>

    <!-- Display success or error messages -->
    <?php if (isset($_GET['error'])): ?>
        <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <!-- Edit book form -->
    <form action="edit_book.php?id=<?php echo $book['id']; ?>" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required><br>

        <label for="author">Author:</label>
        <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required><br>

        <label for="year">Year:</label>
        <input type="text" id="year" name="year" value="<?php echo htmlspecialchars($book['year']); ?>" required><br>

        <label for="available">Available:</label>
        <input type="checkbox" id="available" name="available" <?php echo $book['available'] ? 'checked' : ''; ?>><br>

        <label for="pdf">PDF (optional):</label>
        <input type="file" id="pdf" name="pdf"><br>
        <p>Current PDF: <a href="uploads/<?php echo htmlspecialchars($book['pdf']); ?>" target="_blank">View PDF</a></p>

        <button type="submit">Update Book</button>
    </form>
</body>
</html>
