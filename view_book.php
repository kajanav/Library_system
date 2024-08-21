<?php
include 'db.php';

$sql = "SELECT * FROM books";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            background-color: white;
            text-align:center;
            background-image: url('image7.jpg');
            background-size: cover; /* Make the background cover the entire area */
            background-repeat: no-repeat; /* Prevent the background from repeating */
        }
        table {
            width: 100%;
            height: 300px;
            
        }
        td {
            width: 5%;
            height: 250px;
            text-align: center;
            vertical-align: middle;
            
        }
        
    </style>
    <title>View Books</title>
</head>
<body>
    <h2>Book List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Year</th>
            <th>Available</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row['id']."</td>
                        <td>".$row['title']."</td>
                        <td>".$row['author']."</td>
                        <td>".$row['year']."</td>
                        <td>".($row['available'] ? 'Yes' : 'No')."</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No books found</td></tr>";
        }
        ?>
    </table>
</body>
</html>
