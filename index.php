<?php
  session_start();

  // Redirect to welcome page if logged in
  if(isset($_SESSION['user_name'])){
    header('Location:welcome.php');
    exit();
  }
?>

<!DOCTYPE HTML>
<html lang = "en">

  <head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,
      viewport-fit=cover">

    <title>Book Tracker</title>
    <meta name = "description" content = "Keep track of all the books you
    read.">

    <link rel= "stylesheet" href="css/style.min.css"/>

  </head>

  <body>
    <header>
      <!-- Nav bar -->
      <nav>
        <ul>
          <li><a href = "index.php" class = "highlightColor">Home</a></li>
          <?php
            // Show user's name + log out option if logged in
            if(isset($_SESSION['user_name'])){
              echo "<li>Logged in as " . $_SESSION['user_name'] . "</li>";
              echo "<li><span id = 'logoutBtn'>Logout</span></li>";
            }else{ //else, show signup + login pages
              echo "
                <li><a href = 'signupPage.php'>Sign Up</a></li>
                <li><a href = 'loginPage.php'>Login</a></li>
              ";
            }
          ?>
          <li><a href = "https://github.com/stephaniekyyip/bookTracker">
            See on GitHub</a></li>
        </ul>
      </nav>
    </header>

    <!-- Default index page w/ signup + login options -->
    <div class = "container centered">
      <h1>Book Tracker</h1>
      <p>Keep track of all the books you read.</p>
      <div class = "underline"></div>

      <h2>New here?</h2>
      <a href = "signupPage.php" class = "btn fullWidth">Sign Up</a>

      <h2>Already have an account?</h2>
      <a href = "loginPage.php" class = "btn fullWidth">Login</a>

    </div> <!-- END container -->

    <footer>
      Made by <a href = "http://stephaniekyyip.github.io">Stephanie Yip</a> 2018
    </footer>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"
      integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
      crossorigin="anonymous">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Sanchez" rel="stylesheet">


  </body>


</html>
