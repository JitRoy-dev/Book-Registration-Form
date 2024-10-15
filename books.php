<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'userdb'); // Replace with your database credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert a book
if (isset($_POST['insert'])) {
    $book_name = $_POST['book_name'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $price = $_POST['price'];

    $sql = "INSERT INTO books (book_name, author, publisher, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssd", $book_name, $author, $publisher, $price);

    if ($stmt->execute()) {
        echo "Book inserted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Update a book
if (isset($_POST['update'])) {
    $book_id = $_POST['book_id'];
    $book_name = $_POST['book_name'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $price = $_POST['price'];

    $sql = "UPDATE books SET book_name=?, author=?, publisher=?, price=? WHERE book_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdi", $book_name, $author, $publisher, $price, $book_id);

    if ($stmt->execute()) {
        echo "Book updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Delete a book
if (isset($_POST['delete'])) {
    $book_id = $_POST['book_id'];

    $sql = "DELETE FROM books WHERE book_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);

    if ($stmt->execute()) {
        echo "Book deleted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Search a book
if (isset($_POST['search'])) {
    $book_name = $_POST['book_name'];
    $sql = "SELECT * FROM books WHERE book_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_param = "%" . $book_name . "%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Display all books if no search
    $sql = "SELECT * FROM books";
    $result = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books</title>
    <link rel="stylesheet" href="books.css">
</head>
<body>
    

    <form action="books.php" method="POST">
        <div class="text">
            <h2>Welcome! Manage Books</h2>
        </div>
        <div class="info">
            <label for="book_name">Book ID:</label>
            <input type="text" name="book_id" required><br><br>
        </div>
        <div class="info">
            <label for="book_name">Book Name:</label>
            <input type="text" id="book_name" name="book_name" required><br><br>
        </div>
        <div class="info">
            <label for="author">Author:</label>         
            <input type="text" id="author" name="author" required><br><br>
        </div>
        <div class="info">
            <label for="publisher">Publisher:</label>
            <input type="text" id="publisher" name="publisher"><br><br>
        </div>
        <div class="info">
            <label for="price">Price:</label>
            <input type="text" id="price" name="price"><br><br>
        </div>
        <div class="btn">
            <button type="submit" name="insert">Insert</button>
            <button type="submit" name="update">Update</button>
            <button type="submit" name="delete">Delete</button>
            <button type="submit" name="search">Search</button>
        </div>
    </form>

    <div class="tab">
        <h3>Book Records</h3>
        <table border="1">
            <tr>
                <th>Book ID</th>
                <th>Book Name</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Price</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['book_id']}</td>
                        <td>{$row['book_name']}</td>
                        <td>{$row['author']}</td>
                        <td>{$row['publisher']}</td>
                        <td>{$row['price']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No records found</td></tr>";
            }
            ?>
        </table>
    </div>
    
    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
