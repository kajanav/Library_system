<?php
include 'db.php';
session_start();


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    
    $query = "SELECT * FROM books WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $book_id, PDO::PARAM_INT); 
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        
        error_log("Book with ID $book_id not found in database.", 3, "error_log.log");
        header("Location: dashboard.php?error=Book not found");
        exit;
    }
} else {
    header("Location: dashboard.php?error=No book selected");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $available = isset($_POST['available']) ? 1 : 0;

    
    if (!empty($_FILES['pdf']['name'])) {
        $pdf_name = basename($_FILES['pdf']['name']);
        $pdf_target = "uploads/" . $pdf_name;
   if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true); 
    }


      
        if (move_uploaded_file($_FILES['pdf']['tmp_name'], $pdf_target)) {
            $pdf = $pdf_name;
        } else {
            header("Location: edit_book.php?id=$book_id&error=Failed to upload PDF");
            exit;
        }
    } else {
     
        $pdf = $book['pdf'];
    }

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
    <link rel="stylesheet" href="style.css">
</head>
<header>
    <div class="main">
        <nav class="navr navr-inverse1">
            <div class="navdiv1">
            <div class="logo">
          <h2>ReadNet</h2>
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
   

   
    <?php if (isset($_GET['error'])): ?>
        <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <div class="container">

    <form action="edit_book.php?id=<?php echo $book['id']; ?>" method="POST"  id="form" enctype="multipart/form-data">
    <h1>Edit Book</h1>
    <div class="input-group">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required><br>
    </div>
    <div class="input-group">
    <label for="author">Author:</label>
    <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required><br>
    </div>
    <div class="input-group">
    <label for="year">Year:</label>
    <input type="text" id="year" name="year" value="<?php echo htmlspecialchars($book['year']); ?>" required><br>
    </div>
    <div class="input-group">
    <label for="available">Available:</label>
    <input type="checkbox" id="available" name="available" <?php echo $book['available'] ? 'checked' : ''; ?>><br>
    </div>
    <div class="input-group">
    <label for="pdf">PDF (optional):</label>
    <input type="file" id="pdf" name="pdf"><br>
    <?php if (!empty($book['pdf'])): ?>
        <p>Current PDF: <a href="uploads/<?php echo htmlspecialchars($book['pdf']); ?>" target="_blank">View PDF</a></p>
    <?php else: ?>
        <p>No PDF available.</p>
    <?php endif; ?>
    </div>
    <button type="submit">Update Book</button>
</form>
</div>

</body>
</html>
