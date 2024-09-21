<?php
include("./header.php");
include 'fetchuserinfo.php';

// Query to fetch books with "declined" or "returned" status that are available for swap
$availableBooksQuery = $conn->prepare("
    SELECT books.bookID, books.title, books.author, books.year_published, users.username, users.userID
    FROM collection
    JOIN books ON collection.bookID = books.bookID
    JOIN users ON collection.userID = users.userID
    LEFT JOIN swap ON books.bookID = swap.bookID
    WHERE collection.showcase = 1
    AND (swap.status IS NULL OR swap.status IN ('declined', 'returned'))
    AND users.userID != ?
");

$availableBooksQuery->bind_param("i", $viewingUser); // Assuming $viewingUser contains the logged-in userID
$availableBooksQuery->execute();
$result = $availableBooksQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/design.css">
    <title>Available Books - Bookworm Buddies</title>
    <style>
        body {
            background-image: url("./images/site.png");
            background-size: cover;
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
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .book-box {
            flex: 1 1 calc(33.333% - 20px);
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
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
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
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
        <h1>Available Books From All Users</h1>
        <div class="book-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="book-box">
                    <h2>Title: <?= htmlspecialchars($row['title']) ?></h2>
                    <p><b>Author:</b> <?= htmlspecialchars($row['author']) ?></p>
                    <p><b>Published Year:</b> <?= htmlspecialchars($row['year_published']) ?></p>
                    <p><b>Owner:</b> <a href="profile.php?userID=<?= htmlspecialchars($row['userID']) ?>" class="link"><?= htmlspecialchars($row['username']) ?></a></p>

                    <!-- Show "Request For Swap" button as all books displayed are eligible for swap -->
                    <button onclick="window.location.href='requestbook.php?bookID=<?= $row['bookID'] ?>'" class="btn-primary">Request For Swap</button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No available books found.</p>
        <?php endif; ?>
        </div>
        <button onclick="window.location.href='home.php'" class="btn-secondary">Back to Home</button>
        </div>
    </div>
<?php include("./footer.php"); ?>
</body>
</html>

<?php
$availableBooksQuery->close();
$conn->close();
?>