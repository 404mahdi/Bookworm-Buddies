<?php
session_start();
include 'dbconnect.php';

# Retrieve user ID and username from the session
$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

if (!$userID) {
    header('Location: index.php');
    exit();
}

# Check if one user visits another user's profile
$viewingUser = isset($_GET['userID']) ? intval($_GET['userID']) : $userID;

# Fetch User Info based on viewingUser, not userID
$fetch = $conn->prepare("SELECT first_name, last_name, email, date_of_birth, username FROM users WHERE userID = ?");
$fetch->bind_param("i", $viewingUser);
$fetch->execute();
$result = $fetch->get_result();

# Check if the user exists or not
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    $user = null;
}

$fetch->close();

// Fetch all books added by the user (collections)
$collections = $conn->prepare("SELECT books.bookID, books.title, books.author, books.year_published, collection.showcase FROM collection JOIN books ON collection.bookID = books.bookID WHERE collection.userID = ?");
$collections->bind_param("i", $viewingUser);
$collections->execute();
$collectionResult = $collections->get_result();

$books = [];
$showcasedBooks = []; // Array to store showcased books separately

if ($collectionResult->num_rows > 0) {
    while ($row = $collectionResult->fetch_assoc()) {
        $books[] = $row; // Add each book to the $books array
        if ($row['showcase'] == 1) {
            $showcasedBooks[] = $row; // Add only showcased books to $showcasedBooks
        }
    }
}

$collections->close();
?>
