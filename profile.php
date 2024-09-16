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
        <h2>Book Collection:</h2>
            <button onclick="window.location.href='registerbook.php'" class="btn-primary">Register New Book</button>
            <?php if (!empty($books)): ?>
                <ul>
                    <?php foreach ($books as $book): ?>
                        <li>
                            <b>Title:</b> <?= htmlspecialchars($book['title']) ?><br>
                            <b>Author:</b> <?= htmlspecialchars($book['author']) ?><br>
                            <b>Published Year:</b> <?= htmlspecialchars($book['year_published']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No books in your collection.</p>
            <?php endif; ?>
    </section>
</body>
</html>