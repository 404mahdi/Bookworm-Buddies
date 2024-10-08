<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./css/header.css">
    <title>Bookworm Buddies</title>
  </head>
  <body>
    <nav class="navbar">
      <div class="logo"><a href="home.php">BOOKWORM BUDDIES</a></div>
      
      <!-- Updated search form -->
      <div id="search-box">
        <form id="search-form" action="search.php" method="GET">
          <input type="text" id="search-bar" name="q" placeholder="Search..." required />
          <button type="submit" id="search-btn">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </form>
      </div>
      
      <ul class="nav-links">
        <li><a href="./home.php">Home</a></li>
        <li><a href="./availablebooks.php">Available Books</a></li>
        <li><a href="./registerbook.php">Add Book</a></li>
        <li><a href="./requests.php">Requests</a></li>
        <li><a href="./profile.php">My profile</a></li>
      </ul>
    </nav>

    <script
      src="https://kit.fontawesome.com/4599b1e468.js"
      crossorigin="anonymous"
    ></script>
  </body>
</html>