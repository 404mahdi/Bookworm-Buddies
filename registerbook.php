<?php
session_start();
include 'dbconnect.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input from the form
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year_published = $_POST['publish'];

    // Get the logged-in user's ID from the session
    $userID = $_SESSION['user_id'];

    // Prepare and execute the SQL to insert the new book into the `books` table
    $bookQuery = $conn->prepare("INSERT INTO books (title, author, year_published) VALUES (?, ?, ?)");
    $bookQuery->bind_param("ssi", $title, $author, $year_published);

    if ($bookQuery->execute()) {
        $bookID = $conn->insert_id;

        // Now insert this book into the user's collection
        $collectionQuery = $conn->prepare("INSERT INTO collection (userID, bookID, showcase) VALUES (?, ?, ?)");
        $showcase = 0; // Default value for showcase; you can change this based on your logic
        $collectionQuery->bind_param("iii", $userID, $bookID, $showcase);

        if ($collectionQuery->execute()) {
            // Book successfully added to the collection
            header('Location: profile.php?success=1'); // Redirect to profile with success message
            exit();
        } else {
            // If the book was added but the collection failed
            $error = "Error adding the book to your collection: " . $collectionQuery->error;
        }
    } else {
        // If the book registration failed
        $error = "Error registering the book: " . $bookQuery->error;
    }

    // Close the prepared statements
    $bookQuery->close();
    $collectionQuery->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/registerbook.css">
    <title>Register Book - Bookworm Buddies</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
        font-family: Arial, sans-serif;
        background: url('./images/site.png') no-repeat center center fixed;
        background-size: cover;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add a New Book</h1>
        <form action="registerbook.php" method="post">
            <label for="title">Book Title:</label>
            <input class="textbox" type="text" name="title" required><br>

            <label for="author">Author:</label>
            <input class="textbox" type="text" name="author" required><br>

            <label for="publish">Published Year:</label>
            <input class="textbox" type="text" name="publish" required><br>

            <button class="btn-primary" type="submit">Register</button>
        </form>

        <?php if (isset($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>