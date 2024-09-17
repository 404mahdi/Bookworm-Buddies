<?php
include 'fetchuserinfo.php';

// Check if the bookID is provided in the URL
if (isset($_GET['bookID'])) {
    $bookID = intval($_GET['bookID']);

    // Begin a transaction
    $conn->begin_transaction();

    try {
        //Delete from `collection` table
        $deleteFromCollection = $conn->prepare("DELETE FROM collection WHERE bookID = ?");
        $deleteFromCollection->bind_param("i", $bookID);
        $deleteFromCollection->execute();
        $deleteFromCollection->close();

        //Delete from `swap` table (if any swaps are related to this book)
        $deleteFromSwap = $conn->prepare("DELETE FROM swap WHERE bookID = ? OR desired_bookID = ?");
        $deleteFromSwap->bind_param("ii", $bookID, $bookID);
        $deleteFromSwap->execute();
        $deleteFromSwap->close();

        //Delete from `feedback` table (all feedback related to this book)
        $deleteFromFeedback = $conn->prepare("DELETE FROM feedback WHERE bookID = ?");
        $deleteFromFeedback->bind_param("i", $bookID);
        $deleteFromFeedback->execute();
        $deleteFromFeedback->close();

        //Delete the book from the `books` table
        $deleteFromBooks = $conn->prepare("DELETE FROM books WHERE bookID = ?");
        $deleteFromBooks->bind_param("i", $bookID);
        $deleteFromBooks->execute();
        $deleteFromBooks->close();

        // Commit the transaction after all deletions
        $conn->commit();

        // Redirect to profile page after deletion
        header("Location: profile.php?userID=" . $viewingUser);
        exit();

    } catch (Exception $e) {
        // Rollback the transaction if any query fails
        $conn->rollback();
        $message = "Error: Could not delete book.";
    }
} else {
    // If no bookID is provided in the URL
    $message = "Invalid book ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/signin.css">
    <title>Delete Book - Bookworm Buddies</title>
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
    <h1>Delete Book</h1>
    <?php if (isset($message)): ?>
        <p class="error-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <button onclick="window.location.href='profile.php?userID=<?= $viewingUser ?>'" class="button">Back to Profile</button>
</section>
</body>
</html>