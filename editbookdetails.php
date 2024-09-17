<?php
include 'fetchuserinfo.php';

// Get the bookID from the URL parameter
if (isset($_GET['bookID'])) {
    $bookID = intval($_GET['bookID']);
    
    // Fetch book details for the given bookID
    $fetchBook = $conn->prepare("SELECT title, author, year_published FROM books WHERE bookID = ?");
    $fetchBook->bind_param("i", $bookID);
    $fetchBook->execute();
    $bookResult = $fetchBook->get_result();
    
    // Check if the book exists
    if ($bookResult->num_rows > 0) {
        $book = $bookResult->fetch_assoc(); // Get the book details
    } else {
        echo "Book not found.";
        exit();
    }
    $fetchBook->close();
} else {
    echo "Invalid book ID.";
    exit();
}

// Handle form submission for updating the book details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year_published = $_POST['year_published'];

    // Validate fields
    if (!empty($title) && !empty($author) && !empty($year_published)) {
        // Update the book details in the database
        $update = $conn->prepare("UPDATE books SET title = ?, author = ?, year_published = ? WHERE bookID = ?");
        $update->bind_param("ssii", $title, $author, $year_published, $bookID);
        
        if ($update->execute()) {
            header('Location: profile.php?userID=' . $viewingUser);
            exit();
        } else {
            $message = "Error updating book details!";
        }
        $update->close();
    } else {
        $message = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/signin.css">
    <title>Edit Book - Bookworm Buddies</title>
    <style>
        body {
            background-image: url("./images/site.png");
            background-size: cover;
            background-color: black;
            color: white;
            background-repeat: no-repeat;
            height: 100vh;
            background-attachment: fixed;
        }
    </style>
</head>
<body>
<section class="container">
    <h1>Update Book Information</h1>
    <?php if (isset($message)): ?>
        <p class="error-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form class="form-container" action="editbookdetails.php?bookID=<?= htmlspecialchars($bookID) ?>" method="POST">
        <label for="title">Title:</label>
        <input class="textbox" type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required><br>

        <label for="author">Author:</label>
        <input class="textbox" type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required><br>

        <label for="year_published">Published Year:</label>
        <input class="textbox" type="number" id="year_published" name="year_published" value="<?= htmlspecialchars($book['year_published']) ?>" required><br>

        <button type="submit" class="button">Update</button>
    </form>

    <button onclick="window.location.href='profile.php?userID=<?= $viewingUser ?>'" class="button">Back to Profile</button>
</section>
</body>
</html>