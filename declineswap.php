<?php
include 'fetchuserinfo.php';

$swapID = $_GET['swapID'] ?? 0;

// Validate input
if (filter_var($swapID, FILTER_VALIDATE_INT) === false) {
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

// Update the swap request status to "declined"
$updateSwapRequest = $conn->prepare("
    UPDATE swap
    SET status = 'declined'
    WHERE swapID = ?
");
$updateSwapRequest->bind_param("i", $swapID);
$updateSwapRequest->execute();

// Send a notification to the requester
$sendNotification = $conn->prepare("
    INSERT INTO notifications (userID, message)
    VALUES (?, 'Your swap request has been declined.')
");
$sendNotification->bind_param("i", $requesterID);
$sendNotification->execute();

// Redirect back to the requests page
header("Location: requests.php");
exit;
?>