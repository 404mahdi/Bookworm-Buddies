<?php
include 'fetchuserinfo.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/profile.css">
    <title><?= htmlspecialchars($user['first_name']. " ". $user['last_name']) ?> - Bookworm Buddies</title>
</head>
<body>
    <section class="userinfo">
        <h1>Profile Information:</h1>
        <?php if ($user): ?>
            <p>Name: <b><?= htmlspecialchars($user['first_name']. " ". $user['last_name']) ?></b></p>
            <p>Email: <b><?= htmlspecialchars($user['email']) ?></b></p>
            <p>Date of Birth: <b><?= htmlspecialchars($user['date_of_birth']) ?></b></p>
            <p>Username: <b><?= htmlspecialchars($user['username']) ?></b></p>
            <?php if ($viewingUser == $userID): ?>
                <button onclick="window.location.href='updateprofile.php'" class="btn-primary">Update Profile</button>
            <?php endif; ?>
        <?php else: ?>
            <p>User not found.</p>
        <?php endif; ?>
    </section>

    <section class="collections">
        <!-- Collections can be added here -->
    </section>
</body>
</html>