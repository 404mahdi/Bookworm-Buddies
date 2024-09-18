<?php
include 'fetchuserinfo.php';
include("./header.php");

if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <p class="success-message">Book registered successfully!</p>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/design.css">
    <title><?= htmlspecialchars($user['first_name'] . " " . $user['last_name']) ?> - Bookworm Buddies</title>
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
    <div class="wholebody">
        <section class="userinfo">
            <h1>Profile Information:</h1>
            <?php if ($user): ?>
                <p>Name: <b><?= htmlspecialchars($user['first_name'] . " " . $user['last_name']) ?></b></p>
                <p>Email: <b><?= htmlspecialchars($user['email']) ?></b></p>
                <p>Date of Birth: <b><?= htmlspecialchars($user['date_of_birth']) ?></b></p>
                <p>Username: <b><?= htmlspecialchars($user['username']) ?></b></p>
                <!-- Only show update and logout buttons if the logged-in user is viewing their own profile -->
                <?php if ($viewingUser == $userID): ?>
                    <button onclick="window.location.href='updateprofile.php'" class="btn-primary">Update Profile</button>
                    <button onclick="confirmDeleteAccount()" class="btn-primary">Delete Account</button>
                    <button onclick="window.location.href='logout.php'" class="btn-primary">Logout</button>
                <?php endif; ?>
            <?php else: ?>
                <p>User not found.</p>
            <?php endif; ?>
        </section>

        <section class="collections">
            <h2>Book Collection:</h2>
        
            <!-- All Books, Showcased Books, Register New Book Options -->
            <div>
                <button onclick="window.location.href='?userID=<?= $viewingUser ?>&show=all'" class="btn-primary">All Books</button>
                <button onclick="window.location.href='?userID=<?= $viewingUser ?>&show=showcased'" class="btn-primary">Showcased Books</button>
                <?php if ($viewingUser == $userID): ?>
                <button onclick="window.location.href='registerbook.php'" class="btn-primary">Register New Book</button>
                <?php endif; ?>
            </div>

            <?php
            // Display books based on the query parameter (all or showcased)
            $bookList = (isset($_GET['show']) && $_GET['show'] == 'showcased') ? $showcasedBooks : $books;
            if (!empty($bookList)): ?>
                <ul>
                    <?php foreach ($bookList as $book): ?>
                        <li>
                            <b>Title:</b> <?= htmlspecialchars($book['title']) ?><br>
                            <b>Author:</b> <?= htmlspecialchars($book['author']) ?><br>
                            <b>Published Year:</b> <?= htmlspecialchars($book['year_published']) ?>
                            <br><br>

                            <!-- Book Edit or Delete Option -->
                            <?php if ($viewingUser == $userID): ?>
                                <button onclick="window.location.href='editbookdetails.php?bookID=<?= htmlspecialchars($book['bookID']) ?>'" class="btn-primary">Edit</button>
                                <button onclick="window.location.href='deletebook.php?bookID=<?= htmlspecialchars($book['bookID']) ?>'" class="btn-primary">Delete</button>
                            <?php endif; ?>
        
                            <!-- Showcase / Remove from Showcase Option -->
                            <?php if ($viewingUser == $userID && isset($book['bookID'])): ?>
                                <?php if ($book['showcase'] == 0): ?>
                                    <button onclick="window.location.href='showcase.php?bookID=<?= htmlspecialchars($book['bookID']) ?>'" class="btn-primary">Showcase</button>
                                <?php else: ?>
                                    <button onclick="window.location.href='removeshowcase.php?bookID=<?= htmlspecialchars($book['bookID']) ?>'" class="btn-primary">Remove from Showcase</button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No books available.</p>
        <?php endif; ?>
    </section>
    <script>
    function confirmDeleteAccount() {
        const confirmed = confirm("Are you sure you want to delete your account?");
        if (confirmed) {
            window.location.href = 'deleteaccount.php';
        }
    }
</script>
</div>
</body>
</html>