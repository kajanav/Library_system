<?php 
include "db.php";
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if the user is an admin
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$username = $_SESSION['username'];

$query = "SELECT books.id, books.title, books.author, books.year, books.available FROM books ORDER BY books.id DESC";
$statement = $conn->prepare($query);
$statement->execute();
$books = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Dashboard</title>
</head>
<header>
    <div class="main">
        <nav class="navr navr-inverse1">
            <div class="navdiv1">
                <div class="logo">
                    <a href="#">Wisdom Woods Library</a>
                </div>
                <ul class="nav nav-underline">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="browse_books.php">Browse Books</a></li>
                    <li class="nav-item"><a class="nav-link" href="return_books.php">Return Book</a></li>
                    <li class="nav-item" id="logout"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>

<body>
<h1>WELCOME <?php echo $username; ?>!</h1>

<!-- Display only if the user is not an admin -->
<?php if (!$is_admin): ?>
    <div class="user-card">
        <div class="user-info">
            <h2><?php echo $username; ?> </h2>
        </div>
        <a href="add_book.php"> <button class="create-post-btn">Add Book</button></a> 
    </div>
<?php endif; ?>

<div class="cards">
    <h1>Available Books</h1>
    <?php foreach ($books as $book) : ?>
        <article class="card">
            <header>
                <h3><?php echo $book['title']; ?></h3>
            </header>
            <div class="content">
                <p><strong>Author:</strong> <?php echo $book['author']; ?></p>
                <p><strong>Year:</strong> <?php echo $book['year']; ?></p>
                <p><strong>Available:</strong> <?php echo $book['available'] ? 'Yes' : 'No'; ?></p>
            </div>
        </article>
    <?php endforeach; ?>
</div>

<footer class="footer">
    Developed By:20APSE4878 Kajana.V
</footer>
</body>
</html>
