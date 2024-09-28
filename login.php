<?php
    include "db.php";
    
    if($_REQUEST && $_REQUEST["uname"]){
      session_start();
      $uname=trim($_REQUEST["uname"]);
      $pass=trim($_REQUEST["password1"]);
      // $hash_default_salt = password_hash($pass, PASSWORD_DEFAULT); 
  
      
      $sql="SELECT * FROM users WHERE username=:un";
      $stmt=$conn->prepare($sql);
      $stmt->bindParam(':un',$uname);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if (password_verify($pass, $result["password"])) {
        $_SESSION['id']=$result["id"];
        $_SESSION['username']=$uname;
        header('Location: dashboard.php'); 
        //echo "<script>window.open('dashboard.php','_self')</script>";  
    } else {
      $_SESSION['messages'][]='username or password is incorrect.!';
      header('location: login.php');
      exit; 
    } 
    }
   
?> 


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  
  <title>Login</title>
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
          <li id="login" class="nav-item"><a class="nav-link" href="login.php">Log In</a></li>
        </ul>
      </div>
    </nav>
  </div>

</header>

<body>
  <div class="container">
  <?php require_once 'message.php'; ?>
    <form action="login.php" id="form" method="POST" onsubmit="return validateForm()">
      <h1>Login Here</h1>
      <div class="input-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="uname" placeholder="Enter Username" >
        <div id="usernameError" class="error"></div>
        <div class="input-group ">
          <label for="password">Password</label>
          <input type="password" id="password" name="password1" placeholder="Enter Password" >
          <div id="passwordError" class="error"></div>
        </div>
        <button type="submit">Login</button>
        <br><br>
        <a href="register.php">Register for new account ?</a>
    </form>
  </div>
  <footer class="footer">
  Software Testing - Group 06
  </footer>
</body>
<script src="./js/script1.js"></script>
</html>