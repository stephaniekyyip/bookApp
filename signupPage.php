<?php
  session_start();
?>

<!DOCTYPE HTML>
<html lang = "en">

  <head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,
      viewport-fit=cover" />

    <title> Book Tracker | Sign Up</title>
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

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- JavaScript -->
    <script src = "js/functions.js"></script>

  </head>

  <body>
    <header>
      <nav>
        <ul>
          <li><a href = "index.php">Home</a></li>
          <li><a href = "signupPage.php" class = "highlightColor">Sign Up</a></li>
          <li><a href = "loginPage.php">Login</a></li>
          <li><a href = "https://github.com/stephaniekyyip/bookTracker">
            See on GitHub</a></li>
        </ul>
      </nav>
    </header>

    <div class = "container">
      <h1>Sign Up</h1>
      <p>Create a new account to begin tracking your reading.</p>

      <div id = "signUpResponse" class = "highlightColor"></div>

      <form id = "signUpForm" method = "post" action = "php/signup.php">
        Name <span class = "requiredFormat">(Required)</span> <br>
        <input type = "text" name = "signUpName" id = "signUpName" required pattern = "[A-Za-z ]+"
          oninvalid="setCustomValidity('Please enter a valid name (only letters).')"
          oninput="setCustomValidity('')"/><br>
        E-mail <span class = "requiredFormat">(Required)</span> <br>
        <input type = "email" name = "signUpEmail" id = "signUpEmail" required/><br>
        Password <span class = "requiredFormat">(Required)</span> <br>
        <input type = "password" name = "signUpPwd" id = "signUpPwd" required/><br>
        Confirm Password <span class = "requiredFormat">(Required)</span> <br>
        <input type = "password" name = "confirmPwd" id = "confirmPwd" required/><br>
        <div class = "rightSide">
          <input type = "submit" name = "submit" id = "signupSubmit" class = "btn" value = "Sign Up"/>
        </div>
      </form>

    </div>

    <footer>
      Made by Stephanie Yip 2018
    </footer>

  </body>



</html>
