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

$query = "SELECT books.id, books.title, books.author, books.year, books.available, books.pdf FROM books ORDER BY books.id DESC";
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
          <h2>ReadNet</h2>
        </div>
                <ul class="nav nav-underline">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="view_book.php">View Book</a></li>
                    <li class="nav-item" id="logout"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>

<body>
<h1>WELCOME <?php echo $username; ?>!</h1>

<?php if (isset($_GET['message'])): ?>
    <p class="success"><?php echo htmlspecialchars($_GET['message']); ?></p>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
<?php endif; ?>

<!-- <?php if (!$is_admin): ?> -->
    <div class="user-card">
        <div class="user-info">
            <h3><?php echo $username; ?> </h3>
        </div>
        <a href="add_book.php"> <button class="create-post-btn">Add Book</button></a> 
    </div>
<!-- <?php endif; ?> -->
<h1>Available Books</h1>
<div style="min-height: 400px;">
<div class="cards">
    
    <?php foreach ($books as $book) : ?>
        <article class="card">
            
                <h3><?php echo $book['title']; ?></h3>
            
            <div class="content">
                <p><strong>Author:</strong> <?php echo $book['author']; ?></p>
                <p><strong>Year:</strong> <?php echo $book['year']; ?></p>
                <p><strong>Available:</strong> <?php echo $book['available'] ? 'Yes' : 'No'; ?></p>
                
          
                <?php if (!empty($book['pdf'])): ?>
                    <p><strong>PDF:</strong> <a href="uploads/<?php echo htmlspecialchars($book['pdf']); ?>" target="_blank">View PDF</a></p>
                <?php else: ?>
                    <p><strong>PDF:</strong> Not available</p>
                <?php endif; ?>
               
            </div>
            <div class="field btns">
            <a href="edit_book.php?id=<?php echo $book['id']; ?>">
    <button class="create-post-btn">Edit Book</button>
</a>
<br>
<br>

                <a href="delete_book.php?id=<?php echo $book['id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">
             <button class="create-post-btn">Delete Book</button>
</a>

            </div>
        </article>
    <?php endforeach; ?>
</div>
</div>
<footer class="footer">
    Software Testing - Group 06
</footer>
</body>
</html>
