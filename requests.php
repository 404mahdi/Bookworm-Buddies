<?php
session_start();
include "header.php";
include 'dbconnect.php';

// Get the current user ID from the session
$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$userID) {
    header('Location: index.php');
    exit();
}

// Fetch requests where the current user is either the requester or owner
$query = $conn->prepare("
    SELECT swap.swapID, swap.requesterID, swap.ownerID, swap.bookID, swap.desired_bookID, swap.status, swap.message, 
           r.username AS requester_username, o.username AS owner_username, 
           b.title AS book_title, d.title AS desired_book_title
    FROM swap
    JOIN users AS r ON swap.requesterID = r.userID
    JOIN users AS o ON swap.ownerID = o.userID
    JOIN books AS b ON swap.bookID = b.bookID
    JOIN books AS d ON swap.desired_bookID = d.bookID
    WHERE swap.requesterID = ? OR swap.ownerID = ?
");

$query->bind_param("ii", $userID, $userID);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/design.css">
    <title>Requests - Bookworm Buddies</title>
    <style>
        body {
            background-image: url("./images/site.png");
            background-size: cover;
            background-color: black;
            color: white;
            background-repeat: no-repeat;
            height: 100vh;
            background-attachment: fixed;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Swap Requests</h1>
        <div class="request-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="request-box">
                        <p><b>Requested book:</b> <?= htmlspecialchars($row['desired_book_title']) ?></p>
                        <p><b>Requested by:</b> <a href="profile.php?userID=<?= htmlspecialchars($row['requesterID']) ?>" class="link"><?= htmlspecialchars($row['requester_username']) ?></a></p>
                        <p><b>Status:</b> <?= htmlspecialchars($row['status']) ?></p>
                        <?php if ($userID == $row['ownerID']): ?>
                            <?php if ($row['status'] == 'pending'): ?>
                                <a href="handlerequest.php?swapID=<?= htmlspecialchars($row['swapID']) ?>&action=accept" class="btn-primary">Accept</a>
                                <a href="handlerequest.php?swapID=<?= htmlspecialchars($row['swapID']) ?>&action=decline" class="btn-primary">Decline</a>
                            <?php endif; ?>
                        <?php elseif ($userID == $row['requesterID']): ?>
                            <?php if ($row['status'] == 'accepted'): ?>
                                <a href="handlerequest.php?swapID=<?= htmlspecialchars($row['swapID']) ?>&action=return" class="btn-primary">Return Book</a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($row['status'] == 'declined'): ?>
                            <p><b>Message:</b> <?= htmlspecialchars($row['message']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No requests found.</p>
            <?php endif; ?>
        </div>
        <br><button onclick="window.location.href='home.php'" class="btn-secondary">Back to Home</button>
    </div>
</body>
</html>

<?php
$query->close();
$conn->close();
?>