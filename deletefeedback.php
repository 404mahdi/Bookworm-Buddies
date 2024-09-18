<?php
session_start();
include 'dbconnect.php';

$feedbackID = $_GET['feedbackID'];
$userID = $_SESSION['user_id'];

# Verify ownership
$verifyFeedback = $conn->prepare("SELECT userID FROM feedback WHERE feedbackID = ?");
$verifyFeedback->bind_param("i", $feedbackID);
$verifyFeedback->execute();
$feedbackOwner = $verifyFeedback->get_result()->fetch_assoc()['userID'];

if ($feedbackOwner != $userID) {
    header("Location: profile.php?error=not_allowed");
    exit();
}

# Delete feedback
$deleteFeedback = $conn->prepare("DELETE FROM feedback WHERE feedbackID = ?");
$deleteFeedback->bind_param("i", $feedbackID);
$deleteFeedback->execute();

header("Location: profile.php?success=feedback_deleted");
exit();
?>