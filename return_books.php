<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $borrower_id = $_POST['borrower_id'];

    $sql = "SELECT book_id FROM borrowers WHERE id = $borrower_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $book_id = $row['book_id'];

    $update_sql = "UPDATE books SET available = TRUE WHERE id = $book_id";
    
    if ($conn->query($update_sql) === TRUE) {
        $delete_sql = "DELETE FROM borrowers WHERE id = $borrower_id";
        $conn->query($delete_sql);
        echo "Book returned successfully";
    } else {
        echo "Error: " . $update_sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT borrowers.id, borrowers.name, books.title FROM borrowers JOIN books ON borrowers.book_id = books.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    
    <title>Return Book</title>
</head>
<body>
    <h1>Return a book</h1><br><br>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <h2>Borrower:</h2> 
        <select name="borrower_id" required>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['id']."'>".$row['name']." borrowed ".$row['title']."</option>";
                }
            } else {
                echo "<option value=''>No borrowed books</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Return Book">
    </form>
</body>
</html>
