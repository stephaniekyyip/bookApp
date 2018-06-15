<?php
  session_start();
?>

<!DOCTYPE HTML>
<html lang = "en">

  <head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,
      viewport-fit=cover" />

    <title>Book Tracker | Login</title>
    <meta name = "description" content = "Keep track of all the books you
      read.">

    <link rel= "stylesheet" href="css/style.css"/>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"
      integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
      crossorigin="anonymous">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Sanchez" rel="stylesheet">

  </head>

  <body>
    <header>
      <nav>
        <ul>
          <li><a href = "index.php">Home</a></li>
          <?php
            if(isset($_SESSION['user_name'])){
              echo "<li>Logged in as " . $_SESSION['user_name'] . "</li>";
              echo "<li><span id = 'logoutBtn'>Logout</span></li>";
            }else{
              echo "
                <li><a href = 'signupPage.php'>Sign Up</a></li>
                <li><a href = 'loginPage.php' class = 'highlightColor'>Login</a></li>
              ";
            }
          ?>
          <li><a href = "https://github.com/stephaniekyyip/bookTracker">
            See on GitHub</a></li>
        </ul>
      </nav>
    </header>

    <div class = "container">
      <h1>Login</h1>
      <p>Log back in to continue tracking your books.</p>

      <div id = "loginResponse" class = "highlightColor"></div>

      <form id = "loginForm" method = "post" action = "php/Users/login.php">
        E-mail <br>
        <input type = "email" name = "loginEmail" id = "loginEmail" required/><br>
        Password  <br>
        <input type = "password" name = "loginPwd" id = "loginPwd" required/><br>
        <div class = "rightSide">
          <input type = "submit" name = "loginSubmit" id = "loginSubmit" class = "btn"
          value = "Login"/>
        </div>
      </form>

    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- JavaScript -->
    <script src = "js/functions.js"></script>

    <footer>
      Made by Stephanie Yip 2018
    </footer>

  </body>


</html>
