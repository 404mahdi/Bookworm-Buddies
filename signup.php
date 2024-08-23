<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/signin.css">
    <title>Sign Up - Bookworm Buddies</title>
</head>
<body>
<div class="container">
        <div class="form-container">
            <?php
            ?>
            <h1 class="logo">Bookworm Buddies</h1>
            <form action="signup.php" method="post">
                <label for="fname">First Name:</label>
                <br>
                <input class="textbox" type="text" name="fname">
                <br>
                <label for="lname">Last Name:</label>
                <br>
                <input class="textbox" type="text" name="lname">
                <br>
                <label for="dob">Date of Birth:</label><br>
                <input class="textbox" type="date" id="dob" name="dob" required>
                <br>
                <label for="email">Email:</label>
                <br>
                <input class="textbox" type="email" name="email">
                <br>
                <label for="username">Username:</label>
                <br>
                <input class="textbox" type="text" name="username">
                <br>
                <label for="password">Password:</label>
                <br>
                <input class="textbox" type="password" name="password">
                <br>
                <button class="button"><strong>Sign Up</strong></button>
                <br>
            </form>
        </div>
    </div>
       
</body>
</html>