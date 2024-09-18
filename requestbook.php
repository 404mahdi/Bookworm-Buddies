<?php
include 'fetchuserinfo.php'; // Ensure user authentication and connection setup

// Retrieve and validate input parameters
$bookID = isset($_GET['bookID']) ? (int)$_GET['bookID'] : 0;
$desiredBookID = isset($_GET['desiredBookID']) ? (int)$_GET['desiredBookID'] : null; // Optional field

// Validate inputs
if ($bookID <= 0 || ($desiredBookID !== null && $desiredBookID <= 0)) {
    header("Location: requests.php");
    exit;
}

// Check if the book exists in the books table
$checkBookQuery = $conn->prepare("SELECT bookID FROM books WHERE bookID = ?");
$checkBookQuery->bind_param("i", $bookID);
$checkBookQuery->execute();
$bookResult = $checkBookQuery->get_result();

if ($bookResult->num_rows === 0) {
    header("Location: requests.php");
    exit;
}

// If desiredBookID is provided, check if it exists in the books table
if ($desiredBookID !== null) {
    $checkDesiredBookQuery = $conn->prepare("SELECT bookID FROM books WHERE bookID = ?");
    $checkDesiredBookQuery->bind_param("i", $desiredBookID);
    $checkDesiredBookQuery->execute();
    $desiredBookResult = $checkDesiredBookQuery->get_result();
    
    if ($desiredBookResult->num_rows === 0) {
        header("Location: requests.php");
        exit;
    }
}

// Determine the owner of the book
$ownerQuery = $conn->prepare("
    SELECT users.userID 
    FROM collection
    JOIN users ON collection.userID = users.userID
    WHERE collection.bookID = ? AND collection.showcase = 1
");
$ownerQuery->bind_param("i", $bookID);
$ownerQuery->execute();
$ownerResult = $ownerQuery->get_result();

if ($ownerResult->num_rows === 0) {
    header("Location: requests.php");
    exit;
}

$ownerRow = $ownerResult->fetch_assoc();
$ownerID = $ownerRow['userID'];

// Insert swap request
$insertSwapRequest = $conn->prepare("
    INSERT INTO swap (requesterID, ownerID, bookID, desired_bookID, status)
    VALUES (?, ?, ?, ?, 'pending')
");
$insertSwapRequest->bind_param("iiii", $userID, $ownerID, $bookID, $desiredBookID); // Handle null properly

// Execute the query and check for errors
if ($insertSwapRequest->execute()) {
    // Redirect back to the requests page after successful insertion
    header("Location: requests.php");
} else {
    // Handle errors if insertion fails
    echo "Error: " . $conn->error;
}

// Close statement and connection
$insertSwapRequest->close();
$ownerQuery->close();
$conn->close();
?>