<?php
include 'fetchuserinfo.php';

$swapID = $_GET['swapID'] ?? 0;
$bookID = $_GET['bookID'] ?? 0;

// Validate inputs
if (filter_var($swapID, FILTER_VALIDATE_INT) === false || filter_var($bookID, FILTER_VALIDATE_INT) === false) {
    header("Location: requests.php");
    exit;
}

// Fetch the requesterID for sending notification
$fetchRequester = $conn->prepare("
    SELECT requesterID FROM swap WHERE swapID = ?
");
$fetchRequester->bind_param("i", $swapID);
$fetchRequester->execute();
$fetchRequesterResult = $fetchRequester->get_result();
$requesterRow = $fetchRequesterResult->fetch_assoc();
$requesterID = $requesterRow['requesterID'] ?? 0;

// Update the swap request with the selected book
$updateSwapRequest = $conn->prepare("
    UPDATE swap
    SET desired_bookID = ?
    WHERE swapID = ?
");
$updateSwapRequest->bind_param("ii", $bookID, $swapID);
$updateSwapRequest->execute();

// Send a notification to the requester
$sendNotification = $conn->prepare("
    INSERT INTO notifications (userID, message)
    VALUES (?, 'The book owner wants to swap books with your book.')
");
$sendNotification->bind_param("i", $requesterID);
$sendNotification->execute();

// Redirect back to the requests page
header("Location: requests.php");
exit;
?>
