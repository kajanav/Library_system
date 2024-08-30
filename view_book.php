<?php
// Include the database connection file
include 'db.php';

// SQL query to select all books
$sql = "SELECT * FROM books";
$stmt = $conn->query($sql);

// Fetch all rows as an associative array
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <link rel="stylesheet" href="style.css">
</head>

<header>

  <div class="main">
 
    <nav class="navr navr-inverse1">
      <div class="navdiv1">
        <div class="logo">
          <a href="#">Wisdom Woods Library</a>
        </div>
      

<ul class="nav nav-underline">
          <li class="nav-item"><a  class="nav-link active" aria-current="page" href="index.php">Home</a></li>
          <li id="login" class="nav-item"><a class="nav-link" href="login.php">Log In</a></li>
          <li id="register" class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="add_book.php">Add Book</a></li>
          <li class="nav-item"><a class="nav-link" href="brrow_book.php">Brrow Book</a></li>
          <li class="nav-item"><a class="nav-link" href="view_book.php">View Book</a></li>
          <li class="nav-item"><a class="nav-link" href="return_books.php">Return Book</a></li>
         
          <li class="nav-item" id="logout"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </nav>
  </div>

</header>
<body>
    <h2>Book List</h2>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Year</th>
                <th>Available</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if any books were returned by the query
            if (count($books) > 0) {
                // Loop through each book and display its details in a table row
                foreach ($books as $row) {
                    echo "<tr>
                            <td>".$row['id']."</td>
                            <td>".$row['title']."</td>
                            <td>".$row['author']."</td>
                            <td>".$row['year']."</td>
                            <td>".($row['available'] ? 'Yes' : 'No')."</td>
                          </tr>";
                }
            } else {
                // Display a message if no books are found
                echo "<tr><td colspan='5'>No books found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
