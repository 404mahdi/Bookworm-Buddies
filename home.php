<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/signin.css">
    <title>Homepage - Bookworm Buddies</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nerko+One&family=Great+Vibes&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: "Times New Roman", sans-serif;
            background-color: #282c34; 
            color: white;
        }

        .header {
            font-family: "Times New Roman", sans-serif;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .header a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        .header a:hover {
            text-decoration: underline;
        }

        .logo {
            font-size: 3rem;
            color: white;
            text-align: center;
            margin-top: 3%;
            margin-bottom: 0;
        }

        .subhead {
            font-family: "Nerko One", sans-serif;
            font-size: 2rem;
            color: white;
            text-align: center;
            margin-top: 0;
        }

        .poem {
            font-family: 'Great Vibes', cursive;
            color: lavender;
            font-size: 2rem;
            line-height: 1.8;
            text-align: center;
            margin: 50px auto;
            max-width: 500px;
            padding: 10px;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .poem p {
            margin: 5px 0px;
        }
        .text {
            font-family: "Nerko One", sans-serif;
            font-size: 2.5rem;
            margin: 20px 0px -20px 50px;
        }

    </style>
</head>
<body>
    <h1 class="logo">Bookworm Buddies</h1>
    <h2 class="subhead">- A Book Exchange Site</h2>

    <?php
    include("dbconnect.php");
    session_start();
    $username = $_SESSION['username'];
    $sql = "SELECT username FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $name = $row['username'];
    ?>

    <div class="header">
        <div>
            <a href="about.php">About</a>
            <a href="history.php">History</a>
            <a href="available_books.php">Available Books</a>
        </div>
        <div>
            <a href="profile.php">Hi, <?php echo $name; ?>!</a>
        </div>
    </div>
    <div class="text">For now, a poem only for you</div>
    <div class="poem">
        <p style="text-decoration: underline; font-weight: bold;font-size: 2.5rem;">In Pages Bound</p>
        <p>In quiet nooks where whispers dwell,</p>
        <p>A book unfolds its magic spell,</p>
        <p>Each word a key, each page a door,</p>
        <p>To worlds unseen, and tales of yore.</p>
        <p>Lost in lines, the hours flee,</p>
        <p>In every book, we find we’re free,</p>
        <p>To travel far, to dream and learn,</p>
        <p>In every page, new worlds we turn.</p>
    </div>

    <?php
    $conn->close();
    ?>
</body>
</html>