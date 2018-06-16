<?php
  session_start();

?>

<!DOCTYPE HTML>
<html>

  <head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,
      viewport-fit=cover">

    <title>Book Tracker | 404 Not Found</title>
    <meta name = "description" content = "Keep track of all the books you
    read.">

    <link rel= "stylesheet" href="css/style.min.css"/>

  </head>

  <body>
    <header>
      <nav>
        <ul>
          <li><a href = "index.php" class = "highlightColor">Home</a></li>
          <?php
            if(isset($_SESSION['user_name'])){
              echo "<li>Logged in as " . $_SESSION['user_name'] . "</li>";
              echo "<li><span id = 'logoutBtn'>Logout</span></li>";
            }else{
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

    <div class = "container centered" id = "notFound">
      <h1>404</h1>
      <h2>Page Not Found</h2>

      <a href = "index.php"><button class = "btn" id ="goBackBtn">
        <i class="fas fa-arrow-left"></i> Back to Home </button>
      </a>
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
