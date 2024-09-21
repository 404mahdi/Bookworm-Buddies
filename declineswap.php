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

// Redirect back to the requests page
header("Location: requests.php");
exit;
?>