<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookworm_buddies";

$conn = new mysqli($servername, $username, $password);

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
else{
    mysqli_select_db($conn, $dbname);
}

?>