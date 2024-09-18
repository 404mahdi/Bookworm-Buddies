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

        .commentbox {
            width: 30%;
            max-width: 500px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            resize: vertical;
        }

        .commentbox:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
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
        
            <!-- All Books, Showcased Books, Register New Book, Swapped Books Options -->
            <div>
                <?php if ($viewingUser == $userID): ?>
                <button onclick="window.location.href='?userID=<?= $viewingUser ?>&show=all'" class="btn-primary">All Books</button>
                <button onclick="window.location.href='?userID=<?= $viewingUser ?>&show=showcased'" class="btn-primary">Showcased Books</button>
                <button onclick="window.location.href='?userID=<?= $viewingUser ?>&show=swapped'" class="btn-primary">Swapped Books</button>
                <button onclick="window.location.href='registerbook.php'" class="btn-primary">Register New Book</button>
                <?php endif; ?>
            </div>

            <?php
            // Display books based on the query parameter (all, showcased, or swapped)
            if (!isset($_GET['show']) || $_GET['show'] != 'swapped'): ?>
                <?php $bookList = (isset($_GET['show']) && $_GET['show'] == 'showcased') ? $showcasedBooks : $books; ?>
                <?php if (!empty($bookList)): ?>
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

                                <!-- Feedback section -->
                                <?php if ($viewingUser != $userID): ?>
                                    <form action="submitfeedback.php" method="post">
                                        <input type="hidden" name="bookID" value="<?= htmlspecialchars($book['bookID']) ?>">
                                        <textarea name="comment" class="commentbox" placeholder="Leave your feedback here" required></textarea><br>
                                        <button type="submit" class="btn-primary">Submit Feedback</button>
                                    </form>
                                <?php endif; ?>

                                <h3>Feedback:</h3>
                                <ul>
                                    <?php
                                    $feedbackQuery = $conn->prepare("SELECT feedback.feedbackID, feedback.comment, feedback.userID, feedback.reply, users.username FROM feedback JOIN users ON feedback.userID = users.userID WHERE feedback.bookID = ?");
                                    $feedbackQuery->bind_param("i", $book['bookID']);
                                    $feedbackQuery->execute();
                                    $feedbackResult = $feedbackQuery->get_result();

                                    if ($feedbackResult->num_rows > 0): 
                                        while ($feedback = $feedbackResult->fetch_assoc()): ?>
                                            <li>
                                                <b><a href="profile.php?userID=<?= htmlspecialchars($feedback['userID']) ?>" class="username-link"><?= htmlspecialchars($feedback['username']) ?></a>:</b> 
                                                <?= htmlspecialchars($feedback['comment']) ?><br>
                                                
                                                <!-- User options to delete their own feedback -->
                                                <?php if ($feedback['userID'] == $userID): ?>
                                                    <form action="deletefeedback.php" method="post" style="display: inline;">
                                                    <input type="hidden" name="feedbackID" value="<?= htmlspecialchars($feedback['feedbackID']) ?>">
                                                    <button type="submit" class="btn-primary" style="padding: 10px 10px;">Delete</button>
                                                    </form>
                                                <?php endif; ?>


                                                <!-- Book owner can reply to feedback -->
                                                <?php if ($viewingUser == $userID && $feedback['userID'] != $userID): ?>
                                                    <form action="replyfeedback.php" method="post">
                                                        <input type="hidden" name="feedbackID" value="<?= htmlspecialchars($feedback['feedbackID']) ?>">
                                                        <textarea name="reply" class="commentbox" placeholder="Reply to this feedback" required></textarea><br>
                                                        <button type="submit" class="btn-primary">Submit Reply</button>
                                                    </form>
                                                <?php endif; ?>

                                                <!-- Display reply if exists -->
                                                <?php if (!empty($feedback['reply'])): ?>
                                                    <div class="reply">
                                                        <b>Reply from Book Owner:</b> <?= htmlspecialchars($feedback['reply']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </li>
                                        <?php endwhile;
                                    else: ?>
                                        <p>No feedback available for this book.</p>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No books available.</p>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Swapped Books Section (shown only if the user clicks on the "Swapped Books" button) -->
            <?php if (isset($_GET['show']) && $_GET['show'] == 'swapped'): ?>
            <section class="swapped-books" id="swappedBooks">
                <h2>Swapped Books:</h2>

                <?php
                // Fetch swapped books for the user with 'accepted' status
                $swappedBooksQuery = $conn->prepare("
                    SELECT b.bookID, b.title, b.author, b.year_published
                    FROM books b
                    JOIN swap s ON (b.bookID = s.bookID OR b.bookID = s.desired_bookID)
                    WHERE (s.requesterID = ? OR s.ownerID = ?) AND s.status = 'accepted'
                ");
                $swappedBooksQuery->bind_param("ii", $userID, $userID);
                $swappedBooksQuery->execute();
                $swappedBooksResult = $swappedBooksQuery->get_result();

                if ($swappedBooksResult->num_rows > 0): ?>
                    <ul>
                        <?php while ($swappedBook = $swappedBooksResult->fetch_assoc()): ?>
                            <li>
                                <b>Title:</b> <?= htmlspecialchars($swappedBook['title']) ?><br>
                                <b>Author:</b> <?= htmlspecialchars($swappedBook['author']) ?><br>
                                <b>Published Year:</b> <?= htmlspecialchars($swappedBook['year_published']) ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No swapped books available.</p>
                <?php endif; ?>
            </section>
            <?php endif; ?>

        </section>
    </div>
    <script>
    function confirmDeleteAccount() {
        const confirmed = confirm("Are you sure you want to delete your account?");
        if (confirmed) {
            window.location.href = 'deleteaccount.php';
        }
    }
    </script>
</body>
</html>