<?php
include 'fetchuserinfo.php';

$swapID = $_GET['swapID'] ?? 0;

// Validate input
if (filter_var($swapID, FILTER_VALIDATE_INT) === false) {
    header("Location: requests.php");
    exit;
}

// Fetch the swap request details
$fetchSwap = $conn->prepare("
    SELECT requesterID, ownerID FROM swap WHERE swapID = ?
");
$fetchSwap->bind_param("i", $swapID);
$fetchSwap->execute();
$fetchSwapResult = $fetchSwap->get_result();
$swapRow = $fetchSwapResult->fetch_assoc();

// Update the swap request status to "returned"
$updateSwapRequest = $conn->prepare("
    UPDATE swap
    SET status = 'returned'
    WHERE swapID = ?
");
$updateSwapRequest->bind_param("i", $swapID);
$updateSwapRequest->execute();

// Redirect back to the requests page
header("Location: requests.php");
exit;
?>
