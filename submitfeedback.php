<?php
session_start();
include 'dbconnect.php';

# Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=not_logged_in");
    exit();
}

$userID = $_SESSION['user_id'];
$bookID = filter_input(INPUT_POST, 'bookID', FILTER_SANITIZE_NUMBER_INT);
$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);

# Ensure bookID and comment are valid
if (!$bookID || !$comment) {
    header("Location: profile.php?error=invalid_input");
    exit();
}

# Fetch the book's owner
$bookOwnerQuery = $conn->prepare("SELECT userID FROM collection WHERE bookID = ?");
if ($bookOwnerQuery === false) {
    die('Error: ' . $conn->error);  # Error handling
}
$bookOwnerQuery->bind_param("i", $bookID);
$bookOwnerQuery->execute();
$bookOwnerResult = $bookOwnerQuery->get_result();
$bookOwner = $bookOwnerResult->fetch_assoc()['userID'];

# Check if the book owner exists
if (!$bookOwner) {
    header("Location: profile.php?error=book_not_found");
    exit();
}

# Check if the user is trying to leave feedback on their own book
if ($bookOwner == $userID) {
    header("Location: profile.php?userID=$bookOwner&error=own_feedback");
    exit();
}

# Insert feedback into the database
$insertFeedback = $conn->prepare("INSERT INTO feedback (userID, bookID, comment) VALUES (?, ?, ?)");
if ($insertFeedback === false) {
    die('Error: ' . $conn->error);  # Error handling
}
$insertFeedback->bind_param("iis", $userID, $bookID, $comment);
$insertFeedback->execute();

if ($insertFeedback->affected_rows > 0) {
    header("Location: profile.php?userID=$bookOwner&success=feedback_added");
} else {
    header("Location: profile.php?userID=$bookOwner&error=feedback_failed");
}

exit();
?>