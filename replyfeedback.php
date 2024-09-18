<?php
session_start();
include 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedbackID = $_POST['feedbackID'];
    $reply = $_POST['reply'];
    $userID = $_SESSION['user_id']; // The book owner userID

    // Fetch the feedback details and ensure the current user is the book owner
    $feedbackQuery = $conn->prepare("SELECT collection.userID AS bookOwnerID FROM feedback JOIN collection ON feedback.bookID = collection.bookID WHERE feedback.feedbackID = ?");
    $feedbackQuery->bind_param("i", $feedbackID);
    $feedbackQuery->execute();
    $feedbackResult = $feedbackQuery->get_result();
    $feedbackData = $feedbackResult->fetch_assoc();

    if (!$feedbackData || $feedbackData['bookOwnerID'] != $userID) {
        // Error message if not authorized to reply
        header("Location: profile.php?error=not_allowed");
        exit();
    }

    // Update feedback with the reply
    $insertReply = $conn->prepare("UPDATE feedback SET reply = ? WHERE feedbackID = ?");
    $insertReply->bind_param("si", $reply, $feedbackID);

    if ($insertReply->execute()) {
        // Success message
        header("Location: profile.php?success=reply_added");
    } else {
        // Error message
        header("Location: profile.php?error=reply_failed");
    }

    exit();
} else {
    // Redirect if not a POST request
    header("Location: profile.php");
    exit();
}
?>