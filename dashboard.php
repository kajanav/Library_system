<?php 
include "db.php";
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if the user is admin or regular user
$is_admin = $_SESSION['role'] === 'admin';
$username = $_SESSION['username'];
session_start(); // Ensure session is started



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
                <ul>
                    <li><a href="index.php"><button type="button">Home</button></a></li>
                    <li><a href="dashboard.php"><button type="button" class="active-btn">Dashboard</button></a></li>
                    <?php if ($is_admin): ?>
                        <li><a href="add_book.php"><button type="button">Add Book</button></a></li>
                    <?php endif; ?>
                    <li><a href="browse_books.php"><button type="button">Browse Books</button></a></li>
                    <li><a href="return_books.php"><button type="button">Return Book</button></a></li>
                    <li><a href="logout.php"><button type="button">Logout</button></a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>

<body>
<h1>WELCOME TO <?php echo $username; ?>!</h1>

<?php if ($is_admin): ?>
    <div class="user-card">
        <div class="user-info">
            <h2><?php echo $username; ?> (Admin)</h2>
        </div>
        <a href="add_book.php"> <button class="create-post-btn">Add Book</button></a> 
    </div>
<?php endif; ?>

<div class="cards">
    <h2>Available Books:</h2>
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
