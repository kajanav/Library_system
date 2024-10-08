<?php

include 'db.php';


$sql = "SELECT * FROM books";
$stmt = $conn->query($sql);


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
    <h2>Book List</h2>
    <table border="1" width="100%">
        <thead>
            <tr>
                <!-- <th>ID</th> -->
                <th>Title</th>
                <th>Author</th>
                <th>Year</th>
                <th>Available</th>
                <th>PDF</th>
                <!-- <th>Actions</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            
            if (count($books) > 0) {
                
                foreach ($books as $row) {
                    echo "<tr>
                   
                            <td>".htmlspecialchars($row['title'])."</td>
                            <td>".htmlspecialchars($row['author'])."</td>
                            <td>".htmlspecialchars($row['year'])."</td>
                            <td>".($row['available'] ? 'Yes' : 'No')."</td>";
                    
                    
                    if (!empty($row['pdf'])) {
                        echo "<td><a href='uploads/".htmlspecialchars($row['pdf'])."' target='_blank'>View PDF</a></td>";
                    } else {
                        echo "<td>No PDF</td>";
                    }

                    
                    
                }
            } else {
                
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
