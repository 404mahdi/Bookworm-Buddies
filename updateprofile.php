<?php
include 'fetchuserinfo.php';

// Update Profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $date_of_birth = $_POST['date_of_birth'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($date_of_birth) && !empty($username) && !empty($password)) {
        $update = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, date_of_birth = ?, username = ?, password = ? WHERE userID = ?");
        $update->bind_param("ssssssi", $first_name, $last_name, $email, $date_of_birth, $username, $password, $viewingUser);
        if ($update->execute()) {
            header('Location: profile.php');
        } else {
            $message = "Error updating profile!";
        }
        $update->close();
    } else {
        $message = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/profile.css">
    <title>Update Profile - Bookworm Buddies</title>
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
<section class="userinfo">
        <h1>Update Profile Information</h1>
        <form action="updateprofile.php" method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?= isset($user['first_name']) ? htmlspecialchars($user['first_name']) : '' ?>" required><br>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?= isset($user['last_name']) ? htmlspecialchars($user['last_name']) : '' ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= isset($user['email']) ? htmlspecialchars($user['email']) : '' ?>" required><br>

            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="<?= isset($user['date_of_birth']) ? htmlspecialchars($user['date_of_birth']) : '' ?>" required><br>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= isset($user['username']) ? htmlspecialchars($user['username']) : '' ?>" required><br>

            <label for="password">Password:</label>
            <input type="text" id="password" name="password" value="<?= isset($user['password']) ? htmlspecialchars($user['password']) : '' ?>" required><br>

            <button type="submit" class="btn-primary">Update</button>
        </form>

        <button onclick="window.location.href='profile.php'" class="btn-secondary">Back to Profile</button>
    </section>
</body>
</html>