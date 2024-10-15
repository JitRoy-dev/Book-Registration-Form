<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'userdb'); // Replace with your database credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if username is already taken
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Insert new user into the database
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            // Redirect to login page after successful sign-up
            echo "<script>alert('Registration Successfull.')</script>";
            header("Location: login.php");
            exit(); // Make sure to exit after redirection
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "<script>alert('Username Already taken.')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <form action="signup.php" method="POST">
        <h2>Sign Up</h2>
        <div class="info">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
        </div>
        <div class="info">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required minlength="8"><br><br>
        </div>
        <p>Already have an account? <a href="login.php">Login</a></p>
        <div class="btn">
            <button type="submit">Sign Up</button>
        </div>
    </form>
</body>
</html>
