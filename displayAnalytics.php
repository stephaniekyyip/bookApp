<!DOCTYPE HTML>
<html>

  <head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,
      viewport-fit=cover" />

    <title> Book Tracker | Reading Analytics</title>
    <meta name = "description" content = "Keep track of all the books you have
      read.">

    <!-- C3.js -->
    <link href="c3/c3.css" rel="stylesheet"/>

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
    <div class = "container">
      <h1>Reading Analytics</h1>
      <p>Take a look at your reading habits.</p>

      <div class = "underline"></div>

      <section id = "dataVisuals">
        <div id = "chart"></div>
        <div id = "chartOptions">
          View yearly statistics for: <br>
          <button class = "sortBtnClick" id = "totalBooksBtn">Total Books</button>
          <button class = "sortBtn" id = "totalPgsBtn">Total Pages</button>
          <button class = "sortBtn" id = "totalForClassBtn">Total Read For Class</button>
          <button class = "sortBtn" id = "totalRereadBtn">Total Reread</button>
        </div>
      </section>

      <section id = "analytics">
        Overall Statistics:
        <ul>
        <li>
          <span id = "totalBooks" class = "stats"></span>  books and
          <span id = "totalPgs" class = "stats"></span> pages read since
          <span id = "earliestYear" class = "stats"></span>.
        </li>

        <li>
          Read books from <span id = "numDistinctAuthors" class = "stats">
          </span> authors.
        </li>

        <li>
          Read the most books by <span id = "mostAuthor" class = "stats"></span>
          with <span id = "mostAuthorBooks" class = "stats"></span> books.</li>

        <li>
          Longest book read was <span id = "maxPgsTitle" class = "stats"></span>
          by <span id = "authorMaxPgs" class = "stats"></span> with
          <span id = "maxPgs" class = "stats"></span> pages.
        </li>

        <li>
          Shortest book read was <span id = "minPgsTitle" class = "stats"></span>
          by <span id = "authorMinPgs" class = "stats"></span> with
          <span id = "minPgs" class = "stats"></span> pages.
        </li>

        </ul>

      </section>

      <!-- <span><a href = "index.php">Go Back</a></span> -->
      <a href = "index.php"><button class = "btn" id ="goBackBtn">
        <i class="fas fa-arrow-left"></i> Go Back</button>
      </a>
      <!--
      -Total books read in total years inputted (diff between low/ high years) X
      -Most/ least books read in a year X
      -Most books read by an author X
      -Longest book, shortest book (numPgs) X
      -Time between year read and year published
      -Number of books read for class, reread X
      -Year with the greatest total pages X
      -Total number of unique authors X


      -->

    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!--D3 data visualization -->
    <script src="https://d3js.org/d3.v4.min.js"></script>

    <!-- C3.js -->
    <script src="c3/c3.min.js"></script> <!-- v0.6.1-->

    <!-- JavaScript -->
    <script src="js/visualizeData.js"></script>
    <script src = "js/functions.js"></script>

  </body>

  <footer>
    Made by Stephanie Yip 2018
  </footer>


</html>
