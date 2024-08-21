<?php

include "db.php";

if (isset($_POST['uname'])) {
  session_start();
 $username = trim($_POST['uname']);
 $password1 = trim($_POST['password1']);
 $hash_default_salt = password_hash($password1,PASSWORD_DEFAULT); 
   
   $isValid = true;

   // Check fields are empty or not
   if($username == '' ||  $password1 == ''){
      $isValid = false;
     $_SESSION['messages'][]='Please fill all required fields!';
header('location: register.php');
exit;
   }

// Insert records
   if($isValid){
    $sql="SELECT * FROM users WHERE username=:un";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':un',$username);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
      $_SESSION['messages'][]='This username already added.!';
    header('location: register.php');
    exit;
  } else {
    $insertSQL = "INSERT INTO users (username,password ) values(:un,:pss)";
     $stmt = $conn->prepare($insertSQL);
     $stmt->bindParam(':un',$username); 
     $stmt->bindParam(':pss', $hash_default_salt);
     $stmt->execute();
     
     $_SESSION['messages'][]='Thank you for your registration.!';
header('location: login.php');
exit;
     
  } 
    
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>User Register</title>
</head>
<header>

    <div class="main">
        <nav class="navr navr-inverse1">
            <div class="navdiv1">
                <div class="logo">
                    <a href="#">BlogHub</a>
                </div>
                <ul>

                    <li><a href="index.php"><span class=""></span><button type="button" class=""> Home</button></a></li>
                    <li><a href="login.php"><span class=""></span><button type="button" class="">Log In</button></a></li>
                </ul>
            </div>
        </nav>
    </div>

</header>

<body>
    <div class="container">
    <?php require_once 'message.php'; ?>
        <form name="form" action="register.php" id="form" method="POST" >
            <h1>Register Here</h1>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="uname" placeholder="Enter Username" >
                <div class=" error "></div>
                <div class="input-group ">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password1" placeholder="Enter Password" >
                    <div class="error"></div>
                </div>
                <button type="submit">Register</button>
                <br><br>
                <a href="login.php">existing user, login !?</a>
        </form>
        </div>
        <footer class="footer">
           Developed By:20APSE4878 Kajana.V
        </footer>
</body>

<script src="js/script.js"></script>
</html>