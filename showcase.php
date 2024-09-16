<?php
session_start();
include 'dbconnect.php';

// Get userID and bookID
$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$bookID = isset($_GET['bookID']) ? intval($_GET['bookID']) : null;

if (!$userID || !$bookID) {
    header('Location: index.php');
    exit();
}

// Update the book to be showcased
$updateShowcase = $conn->prepare("UPDATE collection SET showcase = 1 WHERE userID = ? AND bookID = ?");
$updateShowcase->bind_param("ii", $userID, $bookID);
if ($updateShowcase->execute()) {
    header("Location: profile.php?userID=$userID&show=all");
} else {
    echo "Error showcasing book.";
}
$updateShowcase->close();
?>