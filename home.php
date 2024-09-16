<?php
session_start();
include 'dbconnect.php';
include("./header.php");

# Retrieve user ID and username from the session
$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$userID) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body>
    
</body>
</html>