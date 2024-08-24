<?php
    session_start();
    include 'dbconnect.php';
    var_dump($_POST);
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header('Location: home.php');
    } else {
        header('Location: index.php?error=1');
    }
    $conn->close();
?>