<?php
include 'fetchuserinfo.php'; // Ensure user authentication and connection setup

// Get the search query from the user
$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

// Check if the search query is empty
if (empty($searchQuery)) {
    echo "Please enter a search term.";
    exit();
}

// Escape special characters to prevent SQL injection
$searchQuery = '%' . $conn->real_escape_string($searchQuery) . '%';

// Search for books by title or author
$bookSearchQuery = $conn->prepare("
    SELECT bookID, title, author, year_published 
    FROM books 
    WHERE title LIKE ? OR author LIKE ?
");
$bookSearchQuery->bind_param("ss", $searchQuery, $searchQuery);
$bookSearchQuery->execute();
$bookResults = $bookSearchQuery->get_result();

// Search for users by username, first name, or last name
$userSearchQuery = $conn->prepare("
    SELECT userID, username, first_name, last_name 
    FROM users 
    WHERE username LIKE ? OR first_name LIKE ? OR last_name LIKE ?
");
$userSearchQuery->bind_param("sss", $searchQuery, $searchQuery, $searchQuery);
$userSearchQuery->execute();
$userResults = $userSearchQuery->get_result();

// Display the search results
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
</head>
<body>
    <h1>Search Results for "<?php echo htmlspecialchars($_GET['q']); ?>"</h1>

    <!-- Display book results -->
    <?php if ($bookResults->num_rows > 0): ?>
    <h2>Books</h2>
        <ul>
            <?php while ($bookRow = $bookResults->fetch_assoc()): ?>
                <li>
                    <strong><?php echo htmlspecialchars($bookRow['title']); ?></strong> by <?php echo htmlspecialchars($bookRow['author']); ?>
                    (Published: <?php echo htmlspecialchars($bookRow['year_published']); ?>)
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>

    <!-- Display user results -->
    <?php if ($userResults->num_rows > 0): ?>
    <h2>Users</h2>
        <ul>
            <?php while ($userRow = $userResults->fetch_assoc()): ?>
                <li>
                    <strong><?php echo htmlspecialchars($userRow['username']); ?></strong>
                    (Name: <?php echo htmlspecialchars($userRow['first_name'] . ' ' . $userRow['last_name']); ?>)
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>

    <!-- Close prepared statements -->
    <?php
    $bookSearchQuery->close();
    $userSearchQuery->close();
    $conn->close();
    ?>
</body>
</html>