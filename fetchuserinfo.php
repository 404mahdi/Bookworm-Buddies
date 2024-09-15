<?php
session_start();
include 'dbconnect.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

# Retrieve user ID and username from the session
$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
#Since userID and username both are unique we can use any one to identify the user

# Check if one user visits another user's profile
$viewingUser = isset($_GET['userID']) ? intval($_GET['userID']) : $userID;

if (!$userID) {
    header('Location: index.php');
    exit();
}

# Fetch User Info
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
?>