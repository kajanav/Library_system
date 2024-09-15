<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $book_id = $_POST['book_id'];
    $borrow_date = date('Y-m-d');
    $return_date = date('Y-m-d', strtotime('+14 days'));

    // Check if the selected book exists and is available
    $check_sql = "SELECT * FROM books WHERE id = ? AND available = TRUE";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param('i', $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Insert into borrowers table
        $sql = "INSERT INTO borrowers (name, book_id, borrow_date, return_date) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('siss', $name, $book_id, $borrow_date, $return_date);

        if ($stmt->execute()) {
            // Update the book to mark it as not available
            $update_sql = "UPDATE books SET available = FALSE WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param('i', $book_id);
            $update_stmt->execute();

            echo "Book borrowed successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "The selected book is either not available or does not exist.";
    }
}

$sql = "SELECT * FROM books WHERE available = TRUE";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrow Book</title>
    <link rel="stylesheet" href="style1.css">
</head>
<header>
    <div class="main">
        <nav class="navr navr-inverse1">
            <div class="navdiv1">
            <div class="logo">
          <h2>Wisdom Woods Library</h2>
        </div>
                <ul>
                    <li><a href="index.php"><button type="button">Home</button></a></li>
                    <li><a href="login.php"><button type="button" class="active-btn">Log In</button></a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<body>
<div class="container">
    <?php require_once 'message.php'; ?>
    <form action="brrow_book.php" id="form" method="POST" onsubmit="return validateForm()">
        <h1>Borrow a book</h1>
        <div class="input-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Enter Name" required>
        </div>
        <div class="input-group">
            <label for="book_id">Book</label>
            <select name="book_id" id="book_id" required>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['id']."'>".$row['title']."</option>";
                    }
                } else {
                    echo "<option value=''>No books available</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit">Borrow Book</button>
    </form>
</div>
<footer class="footer">
Software Testing - Group 06
</footer>
</body>
</html>
