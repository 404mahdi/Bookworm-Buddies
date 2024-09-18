<?php
include("./header.php");
include 'fetchuserinfo.php';

$booksFromAllUsers = $conn->prepare("
    SELECT books.bookID, books.title, books.author, books.year_published, users.username, users.userID
    FROM collection
    JOIN books ON collection.bookID = books.bookID
    JOIN users ON collection.userID = users.userID
    WHERE collection.showcase = 1
");

$booksFromAllUsers->execute();
$result = $booksFromAllUsers->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/design.css">
    <title>Homepage - Bookworm Buddies</title>
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
        .book-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .book-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .book-box {
            flex: 1 1 calc(33.333% - 20px);
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            box-sizing: border-box;
            margin-bottom: 20px;
        }

        .book-box h2 {
            margin-top: 0;
        }

        .link {
            color: #007bff;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="wholebody">
    <div class="container">
        <h1>Books From All Users</h1>
        <div class="book-list">
        <?php if ($result->num_rows > 0): // Check if there are books available ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="book-box">
                    <h2>Title: <?= htmlspecialchars($row['title']) ?></h2>
                    <p><b>Author:</b> <?= htmlspecialchars($row['author']) ?></p>
                    <p><b>Published Year:</b> <?= htmlspecialchars($row['year_published']) ?></p>
                    <p><b>Owner:</b> <a href="profile.php?userID=<?= htmlspecialchars($row['userID']) ?>" class="link"><?= htmlspecialchars($row['username']) ?></a></p>
                    
                    <?php
$checkExistingRequest = $conn->prepare("
    SELECT * FROM swap
    WHERE requesterID = ? AND bookID = ? AND status IN ('pending', 'accepted')
");
$checkExistingRequest->bind_param("ii", $userID, $row['bookID']);
$checkExistingRequest->execute();
$existingRequestResult = $checkExistingRequest->get_result();

if ($row['userID'] == $userID) {
    // The user is the owner of the book, don't show any swap options
    ?>
    <?php
} elseif ($existingRequestResult->num_rows > 0) {
    $existingRequestRow = $existingRequestResult->fetch_assoc();
    if ($existingRequestRow['status'] == 'pending') {
        // Display "Request Sent" button
        ?>
        <button class="btn-primary" disabled>Request Sent</button>
        <?php
    } elseif ($existingRequestRow['status'] == 'accepted') {
        // Display "Not Available" button
        ?>
        <button class="btn-primary" disabled>Not Available</button>
        <?php
    }
} else {
    // Display "Request For Swap" button
    ?>
    <button onclick="window.location.href='requestbook.php?bookID=<?= $row['bookID'] ?>'" class="btn-primary">Request For Swap</button>
    <?php
}
?>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No books found.</p>
        <?php endif; ?>
        </div>
        <button onclick="window.location.href='home.php'" class="btn-secondary">Back to Home</button>
    </div>
</div>
</body>
</html>

<?php
$booksFromAllUsers->close();
$conn->close();
?>