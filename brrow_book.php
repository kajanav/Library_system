<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $book_id = $_POST['book_id'];
    $borrow_date = date('Y-m-d');
    $return_date = date('Y-m-d', strtotime('+14 days'));

    $sql = "INSERT INTO borrowers (name, book_id, borrow_date, return_date) VALUES ('$name', '$book_id', '$borrow_date', '$return_date')";
    
    if ($conn->query($sql) === TRUE) {
        $update_sql = "UPDATE books SET available = FALSE WHERE id = $book_id";
        $conn->query($update_sql);
        echo "Book borrowed successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM books WHERE available = TRUE";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            background-color:lightgreen;
            text-align:center;
            background-image: url('image6.jpg');
            background-size: cover; /* Make the background cover the entire area */
            background-repeat: no-repeat; /* Prevent the background from repeating */
        }
        
    </style>
    <title>Borrow Book</title>
</head>
<body>
    <h1>Borrow a book</h1><br><br><br><br>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
       <h3> Name: </h3><input type="text" name="name" required><br><br><br>
        <h3>Book: </h3>
        <select name="book_id" required>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['id']."'>".$row['title']."</option>";
                }
            } else {
                echo "<option value=''>No books available</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Borrow Book">
    </form>
</body>
</html>
