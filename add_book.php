<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];

    $sql = "INSERT INTO books (title, author, year) VALUES ('$title', '$author', '$year')";

    if ($conn->query($sql) === TRUE) {
        echo "New book added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
    <title>Add Book</title>
</head>
<body>
       
                <h1> Add a new book</h1><br><br>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
                <h2>Title:   </h2><input type="text" name="title" required><br><br>
                <h2>Author:   </h2><input type="text" name="author" required><br><br>
                <h2> Year:   </h2><input type="number" name="year" required><br><br><br><br><br>
                <input type="submit" value="Add Book">
                </form>
             
</body>
</html>
