<?php
session_start();
include 'dbconnect.php';

// Get the username and password from the POST request
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare the SQL statement to prevent SQL injection
$sql = "SELECT userID FROM users WHERE username=? AND password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the userID from the result
    $row = $result->fetch_assoc();
    $userID = $row['userID'];

    // Set the session variables
    $_SESSION['user_id'] = $userID;
    $_SESSION['username'] = $username;

    // Redirect to the home page
    header('Location: home.php');
    exit();
} else {
    // If login fails, redirect back to the index page with an error message
    header('Location: index.php?error=1');
    exit();
}

$stmt->close();
$conn->close();
?>