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
<header>

  <div class="main">
    <nav class="navr navr-inverse1">
      <div class="navdiv1">
        <div class="logo">
          <a href="#">Wisdom Woods Library</a>
        </div>
        <ul>

          <li><a href="index.php"><span class=""></span><button type="button" class=""> Home</button></a></li>
          <li><a href="login.php"><span class=""></span><button type="button" class="active-btn">Log In</button></a></li>
        </ul>
      </div>
    </nav>
  </div>

</header>
<body>
  <div class="container">
  <?php require_once 'message.php'; ?>
    <form action="add_book.php" id="form" method="POST" onsubmit="return validateForm()">
      <h1>Add a new book</h1>
      <div class="input-group">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" placeholder="Enter Title" >
        <div class="error"></div>
        </div>
        <div class="input-group">
        <label for="auther">Author</label>
        <input type="text" id="auther" name="name" placeholder="Enter Auther" >
        <div class="error"></div>
        </div>
        <div class="input-group">
        <label for="year">Year</label>
        <input type="number" id="username" name="year" placeholder="Enter/Select year" >
        <div class="error"></div>
        </div>
        <button type="submit">Add Book</button>
       
    </form>
  </div>
  <footer class="footer">
    Developed By:20APSE4878 Kajana.V
  </footer>
</body>

</html>
