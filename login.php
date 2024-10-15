<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'userdb'); // Replace with your database credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if user exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Check password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: books.php");
        } else {
            echo "<script>alert('Incorrect Password!')</script>";
        }
    } else {
        // Redirect to signup if user not found
        echo "<script>alert('User not found, please sign up.')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>  
    <form action="login.php" method="POST">
        <h2>Login</h2>
        <div class="info">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
        </div>
        <div class="info">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required minlength="8"><br><br>
        </div>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        <div class="btn">
            <button type="submit">Login</button>
        </div>
    </form> 
</body>
</html>
