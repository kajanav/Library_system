<?php
session_start();
include "db.php";

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['messages'][] = 'You need to log in first!';
    header('location: login.php');
    exit;
}

$current_username = $_SESSION['username'];

// Fetch current user data
$sql = "SELECT * FROM users WHERE username = :username";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':username', $current_username);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['messages'][] = 'User not found!';
    header('location: login.php');
    exit;
}

// Handle form submission
if (isset($_POST['new_username']) && isset($_POST['new_password'])) {
    $new_username = trim($_POST['new_username']);
    $new_password = trim($_POST['new_password']);
    $hash_new_password = password_hash($new_password, PASSWORD_DEFAULT);
    $isValid = true;

    // Check if fields are empty
    if ($new_username == '' || $new_password == '') {
        $isValid = false;
        $_SESSION['messages'][] = 'Please fill all required fields!';
    }

    // Check if the new username already exists
    if ($isValid) {
        $sql = "SELECT * FROM users WHERE username = :new_username AND username != :current_username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':new_username', $new_username);
        $stmt->bindParam(':current_username', $current_username);
        $stmt->execute();
        $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_user) {
            $isValid = false;
            $_SESSION['messages'][] = 'This username is already taken!';
        }
    }

    // Update user data
    if ($isValid) {
        $sql = "UPDATE users SET username = :new_username, password = :new_password WHERE username = :current_username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':new_username', $new_username);
        $stmt->bindParam(':new_password', $hash_new_password);
        $stmt->bindParam(':current_username', $current_username);
        $stmt->execute();

        $_SESSION['username'] = $new_username; // Update session username
        $_SESSION['messages'][] = 'Your profile has been updated!';
        header('location: edit_profile.php');
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
    <title>Edit Profile</title>
</head>
<body>
    <div class="container">
        <?php require_once 'message.php'; ?>
        <form action="edit_profile.php" method="POST">
            <h1>Edit Profile</h1>
            <div class="input-group">
                <label for="new_username">New Username</label>
                <input type="text" id="new_username" name="new_username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="input-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <button type="submit">Update</button>
        </form>
        <br>
        <a href="index.php">Back to Home</a>
    </div>
    <footer class="footer">
        Software Testing - Group 06
    </footer>
</body>
<script src="js/script.js"></script>
</html>
