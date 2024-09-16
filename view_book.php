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
    <link rel="stylesheet" href="style1.css">
</head>

<header>
  <div class="main">
    <nav class="navr navr-inverse1">
      <div class="navdiv1">
        <div class="logo">
          <h2>Wisdom Woods Library</h2>
        </div>
        <ul class="nav nav-underline">
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
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
                <th>PDF</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if there are any books in the database
            if (count($books) > 0) {
                // Loop through each book and display it in the table
                foreach ($books as $row) {
                    echo "<tr>
                            <td>".htmlspecialchars($row['id'])."</td>
                            <td>".htmlspecialchars($row['title'])."</td>
                            <td>".htmlspecialchars($row['author'])."</td>
                            <td>".htmlspecialchars($row['year'])."</td>
                            <td>".($row['available'] ? 'Yes' : 'No')."</td>";
                    
                    // Check if a PDF file exists and display it as a link if it does
                    if (!empty($row['pdf'])) {
                        echo "<td><a href='uploads/".htmlspecialchars($row['pdf'])."' target='_blank'>View PDF</a></td>";
                    } else {
                        echo "<td>No PDF</td>";
                    }

                    // Edit and Delete links
                    echo "<td>
                            <a href='edit_book.php?id=".htmlspecialchars($row['id'])."'>Edit</a> |
                            <a href='delete_book.php?id=".htmlspecialchars($row['id'])."' onclick=\"return confirm('Are you sure you want to delete this book?');\">Delete</a>
                          </td>
                          </tr>";
                }
            } else {
                // If no books found
                echo "<tr><td colspan='7'>No books found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>

<footer class="footer">
    Software Testing - Group 06
</footer>
</html>
