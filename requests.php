<?php
include("./header.php");
include 'fetchuserinfo.php';

// Fetch swap requests
$swapRequestsQuery = $conn->prepare("
    SELECT * FROM swap
    JOIN books ON swap.bookID = books.bookID
    WHERE ownerID = ? OR requesterID = ?
");
$swapRequestsQuery->bind_param("ii", $userID, $userID);
$swapRequestsQuery->execute();
$swapRequestsResult = $swapRequestsQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/design.css">
    <title>Swap Requests</title>
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
        .swap-request  {
            flex: 1 1 calc(33.333% - 20px);
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            box-sizing: border-box;
            margin-bottom: 20px;
        }
        .btn-danger { background-color: #dc3545; }
        .waiting-msg, .accepted-msg, .declined-msg { margin-top: 10px; }
    </style>
</head>
<body>
<div class="wholebody">
<div class="collections">
    <h1>Swap Requests</h1>
    <?php while ($swapRequestRow = $swapRequestsResult->fetch_assoc()): ?>
        <div class="swap-request">
            <h3>Swap Request Details</h3>
            <p><strong>Book ID: </strong> <?= htmlspecialchars($swapRequestRow['bookID']) ?></p>
            <p><strong>Book Title: </strong> <?= htmlspecialchars($swapRequestRow['title']) ?></p>
            <p><strong>Requester ID: </strong> <?= htmlspecialchars($swapRequestRow['requesterID']) ?> </p>
            <p><strong>Owner ID: </strong><?= htmlspecialchars($swapRequestRow['ownerID']) ?></p>
            <p><strong>Status: </strong> <?= htmlspecialchars($swapRequestRow['status']) ?></p>
            
            <?php if ($swapRequestRow['status'] == 'pending' && $swapRequestRow['ownerID'] == $userID): ?>
                <button onclick="window.location.href='acceptswap.php?swapID=<?= $swapRequestRow['swapID'] ?>'" class="btn-primary">Accept</button>
                <button onclick="window.location.href='declineswap.php?swapID=<?= $swapRequestRow['swapID'] ?>'" class="btn-danger">Decline</button>
            <?php elseif ($swapRequestRow['status'] == 'pending' && $swapRequestRow['requesterID'] == $userID): ?>
                <p class="waiting-msg">Waiting for owner's response...</p>
            <?php elseif ($swapRequestRow['status'] == 'accepted'): ?>
                <p class="accepted-msg">Swap accepted!</p>
                <button onclick="window.location.href='returnbook.php?swapID=<?= $swapRequestRow['swapID'] ?>'" class="btn-primary">Mark as Returned</button>
            <?php elseif ($swapRequestRow['status'] == 'declined'): ?>
                <p class="declined-msg">Swap declined.</p>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>
</div>  
</body>
</html>

<?php
$swapRequestsQuery->close();
$conn->close();
?>