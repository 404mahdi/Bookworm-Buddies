<?php
include 'fetchuserinfo.php';

// Check if user is logged in
$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$userID) {
    header('Location: index.php');
    exit();
}

// Deleting user-related data in the correct order
$conn->begin_transaction();

try {
    // Delete feedback
    $deleteFeedback = $conn->prepare("DELETE FROM feedback WHERE userID = ?");
    $deleteFeedback->bind_param("i", $userID);
    $deleteFeedback->execute();
    $deleteFeedback->close();

    // Delete book swaps where the user is either the requester or the owner
    $deleteSwaps = $conn->prepare("DELETE FROM swap WHERE requesterID = ? OR ownerID = ?");
    $deleteSwaps->bind_param("ii", $userID, $userID);
    $deleteSwaps->execute();
    $deleteSwaps->close();

    // Delete user's collections
    $deleteCollections = $conn->prepare("DELETE FROM collection WHERE userID = ?");
    $deleteCollections->bind_param("i", $userID);
    $deleteCollections->execute();
    $deleteCollections->close();

    // Optionally: Delete books (if books are uniquely tied to users, otherwise keep them if books are shared)
    $deleteBooks = $conn->prepare("DELETE FROM books WHERE bookID IN (SELECT bookID FROM collection WHERE userID = ?)");
    $deleteBooks->bind_param("i", $userID);
    $deleteBooks->execute();
    $deleteBooks->close();

    // Finally, delete the user
    $deleteUser = $conn->prepare("DELETE FROM users WHERE userID = ?");
    $deleteUser->bind_param("i", $userID);
    $deleteUser->execute();
    $deleteUser->close();

    // Commit the transaction
    $conn->commit();

    // Logout and redirect to homepage after account deletion
    session_destroy();
    header('Location: index.php');
    exit();

} catch (Exception $e) {
    $conn->rollback();
    echo "Error deleting account: " . $e->getMessage();
}
?>