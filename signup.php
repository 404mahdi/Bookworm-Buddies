<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/signin.css">
    <title>Sign Up-Bookworm Buddies</title>
</head>
<body>
<div class="container">
        <div class="form-container">
            <?php
            include("dbconnect.php");

            if(isset($_POST['submit'])){
                $fname = $_POST['fname'];
                $lname = $_POST['lname'];
                $dob = $_POST['dob'];
                $email = $_POST['email'];
                $username = $_POST['username'];
                $password = $_POST['password'];

                $verify_email = mysqli_query($conn,"SELECT email FROM users WHERE email='$email'");
                $verify_username = mysqli_query($conn,"SELECT username FROM users WHERE username='$username'");
                
                if(mysqli_num_rows($verify_email) != 0) {
                    echo "<div class='message'>
                        <p>Email already used. Please, try again.</p>
                        </div> <br>";
                } elseif (mysqli_num_rows($verify_username) != 0) {
                    echo "<div class='message'>
                        <p>Username already used. Please, try again.</p>
                        </div> <br>";
                } else{
                    $query = "INSERT INTO users(username, password, email, first_name, last_name, date_of_birth) VALUES('$username', '$password', '$email', '$fname', '$lname', '$dob')";
                    if (mysqli_query($conn, $query)) {
                        header('Location: index.php?message=1');
                    } else {
                        echo "<div class='message'>
                            <p>Error: " . mysqli_error($conn) . "</p>
                            </div> <br>";
                    }
                }
            }
            $conn->close();
            ?>
            <h1 class="logo-s">Bookworm Buddies</h1>
            <form action="signup.php" method="post">
                <label for="fname">First Name:</label>
                <br>
                <input class="textbox" type="text" name="fname" required>
                <br>
                <label for="lname">Last Name:</label>
                <br>
                <input class="textbox" type="text" name="lname" required>
                <br>
                <label for="dob">Date of Birth:</label>
                <br>
                <input class="textbox" type="date" name="dob" required>
                <br>
                <label for="email">Email:</label>
                <br>
                <input class="textbox" type="email" name="email" required>
                <br>
                <label for="username">Username:</label>
                <br>
                <input class="textbox" type="text" name="username" required>
                <br>
                <label for="password">Password:</label>
                <br>
                <input class="textbox" type="password" name="password" required>
                <br>
                <button class="button" name="submit"type="submit"><strong>Sign Up</strong></button>
                <br>
            </form>
        </div>
    </div>
</body>
</html>