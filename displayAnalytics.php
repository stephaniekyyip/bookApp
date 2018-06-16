<?php
  session_start();
?>

<!DOCTYPE HTML>
<html lang = "en">

  <head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,
      viewport-fit=cover">

    <title>Book Tracker | Reading Analytics</title>
    <meta name = "description" content = "Keep track of all the books you have
      read.">

    <!-- C3 CSS -->
    <link href="c3/c3.css" rel="stylesheet"/>

    <link rel= "stylesheet" href="css/style.min.css"/>

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
      <!-- Nav bar -->
      <nav>
        <ul>
          <li><a href = "index.php">Home</a></li>
          <?php
            // Add user's name + logout option when user is logged in
            if(isset($_SESSION['user_name'])){
              echo "<li>Logged in as " . $_SESSION['user_name'] . "</li>";
              echo "<li><span id = 'logoutBtn'>Logout</span></li>";
            }
          ?>
          <li><a href = "https://github.com/stephaniekyyip/bookTracker">
            See on GitHub</a></li>
        </ul>
      </nav>
    </header>

    <div class = "container">
      <h1>Reading Analytics</h1>
      <p>Take a look at your reading habits.</p>

      <div class = "underline"></div>

      <div id = "dataVisuals">

        <!-- Visual chart to be filled by C3.js-->
        <div id = "chart"></div>

        <!-- Options to select different charts -->
        <div id = "chartOptions">
          View yearly statistics for: <br>
          <button class = "sortBtnClick" id = "totalBooksBtn">Total Books</button>
          <button class = "sortBtn" id = "totalPgsBtn">Total Pages</button>
          <button class = "sortBtn" id = "totalForClassBtn">Total Read For Class</button>
          <button class = "sortBtn" id = "totalRereadBtn">Total Reread</button>
          <button class = "sortBtn" id = "yearReadvsPublishedBtn">Year Read vs Year Published</button>
        </div>
      </div>

      <!-- Overall statistics values  -->
      <div id = "analytics">
        Overall Statistics:
        <ul>
        <li>
          <span id = "totalBooks" class = "highlightColor"></span>  books and
          <span id = "totalPgs" class = "highlightColor"></span> pages read since
          <span id = "earliestYear" class = "highlightColor"></span>.
        </li>

        <li>
          Read books from <span id = "numDistinctAuthors" class = "highlightColor">
          </span> author(s).
        </li>

        <li>
          Read the most books by <span id = "mostAuthor" class = "highlightColor"></span>
          with <span id = "mostAuthorBooks" class = "highlightColor"></span> books.</li>

        <li id = "max">
          Longest book read was <span id = "maxPgsTitle" class = "highlightColor"></span>
          by <span id = "authorMaxPgs" class = "highlightColor"></span> with
          <span id = "maxPgs" class = "highlightColor"></span> pages.
        </li>

        <li id = "min">
          Shortest book read was <span id = "minPgsTitle" class = "highlightColor"></span>
          by <span id = "authorMinPgs" class = "highlightColor"></span> with
          <span id = "minPgs" class = "highlightColor"></span> pages.
        </li>

        </ul>

      </div>

      <!-- Back to Homepage button -->
      <a href = "index.php" class = "btn" id ="goBackBtn">
      <i class="fas fa-arrow-left"></i> Go Back</a>

    </div>

    <footer>
      Made by <a href = "http://stephaniekyyip.github.io">Stephanie Yip</a> 2018
    </footer>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!--D3 data visualization -->
    <script src="https://d3js.org/d3.v4.min.js"></script>

    <!-- C3 JS-->
    <script src="c3/c3.min.js"></script> <!-- v0.6.1-->

    <!-- JavaScript -->
    <script src="js/visualizeData.js"></script>
    <script src = "js/functions.js"></script>
  </body>


</html>
