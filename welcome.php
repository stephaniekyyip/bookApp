<?php
  session_start();
?>

<!DOCTYPE HTML>
<html lang = "en">

  <head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,
      viewport-fit=cover" />

    <title>Book Tracker | Welcome</title>
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

    <section class = "container">
      <h1>Book Tracker</h1>
      <p>Keep track of all the books you read.</p>

      <!-- Add New Entry section -->

      <!-- Button to reveal add form -->
      <button id = "addBtn" class = "btn">Add Book
        <i class="fab fa-readme" title = "book icon"></i></button>

      <!-- Displays result of attempting to add new entry -->
      <div id = "addResponse"></div>

      <!-- Form for adding a new entry -->
      <div id = "addPanel">
        <form id = "addForm" action = "php/Books/create.php" method = "post">
          Title <span class = "requiredFormat">(Required)</span>
            <input type = "text"
            name = "title" id = "addTitle" value="" required/> <br>
          Author First Name <span class = "requiredFormat">(Required)</span>
            <input type = "text"
            name = "authorFirst" id = "addAuthorFirst" value="" required/> <br>
          Author Last Name <span class = "requiredFormat">(Required)</span>
            <input type = "text"
            name = "authorLast" id = "addAuthorLast" value=""  required/> <br>
          Year Read <span class = "requiredFormat">(Required)</span>
            <input type = "text"
            name = "yearRead" id = "addYearRead" size = "4" maxlength = "4" value=""
            pattern = "[0-9]{4}" required><br>
          Year Published <input type = "text" size = "4" maxlength = "4"
            name = "yearPub" id = "addYearPub" value="" pattern = "[0-9]{4}"/> <br>
          Number of Pages <input type = "text" name = "numPgs" id = "addNumPgs"
            size = "4" value="" pattern = "\d*" /><br>

          Read for class?
          <label class = "labelContainer"> Yes <i class="fas fa-check"></i>
            <input type = "radio" name = "forClass" value ="yes"
              id = "forClassYesAdd">
            <span class = "checkmark"></span>
          </label>

          <label class = "labelContainer"> No <i class="fas fa-times"></i>
            <input type = "radio" name = "forClass" value = "no"
              id = "forClassNoAdd">
            <span class = "checkmark"></span>
          </label>
          <br>

          Reread?
          <label class = "labelContainer"> Yes <i class="fas fa-check"></i>
            <input type = "radio" name = "reread" value  = "yes"
              id = "rereadYesAdd">
            <span class = "checkmark"></span>
          </label>

          <label class = "labelContainer"> No <i class="fas fa-times"></i>
            <input type = "radio" name = "reread" value  = "no"
              id = "rereadNoAdd">
            <span class = "checkmark"></span>
          </label>
          <br>

          <!-- Form buttons: cancel + submit -->
          <div class = "rightSide">
            <input type = "submit" name = "addSubmit" id = "addSubmitBtn"
              value = "Submit" class = "btn">
            <input type = "reset" name = "cancel" class = "btn"
              id = "cancelAddBtn" value = 'Cancel'>
          </div>
        </form>
      </div>

      <!-- Buttons for Upload + Analytics -->
      <div id = "actionBtns">
        <!-- Button link to csv upload -->
        <button class = "btn smallBtn" id = "uploadBtn">Upload CSV File
          <i class="fas fa-upload" title = "upload icon"></i></button>

        <!-- Button link to analytics page -->
        <a href = "displayAnalytics.php" class = "btn smallBtn" id = "analyticsBtn">
          Reading Analytics
          <i class="fas fa-chart-line" title = "analytics icon"></i>
        </a>
      </div>

      <!-- Upload CSV files-->
      <div id = "uploadOverlay">
        <div id = "uploadResponsePanel"></div>
        <div id = "uploadPanel">
          <form id = "uploadForm" method = "post"  enctype="multipart/form-data"
            action = "php/Books/upload.php">
            Upload a .csv file to add multiple book entries at once. <br>
            <div class = "marginTop30">Formatting:</div>
            <pre><code>Title*, Author First Name*, Author Last Name*, Year Read*, Year Published, Number of Pages, Read for Class? (y/n), Reread? (y/n)</code></pre>
            The fields indicated by * are required. Non-required fields can be
            omitted by adding "NULL" as a placeholder.<br>
            <input type="file" name="fileUpload" id="fileUpload"
              class="inputFile" accept="csv/*"/>
            <label for="fileUpload">Choose a file</label>
            <input type = "submit" name = "submitUploadBtn" class = "btn"
              id = "submitUploadBtn" value = 'Upload'>

            <div class = "rightSide">
            <input type = "reset" name = "cancel" class = "btn"
              id = "cancelUploadBtn" value = 'Cancel'>
            </div>
          </form>
        </div>
      </div>

      <!-- Update Entry section -->
      <div id = "updateOverlay">

        <!-- Replaces update form when update is successful (no errors) -->
        <div id = "updateSuccessPanel"> Sucessfully Updated!</div>

        <div id = "updatePanel">
          <!-- Shows any errors when submitting update form -->
          <div id = "updateFailed"></div>

          <!-- Form for update entry  -->
          <form method = "post" id = "updateForm" action = "php/Books/update.php">
            Title <span class = "requiredFormat">(Required)</span>
              <input type = "text"
              name = "titleUpdate" id = "titleUpdate" value="" required/> <br>

            Author's First Name <span class = "requiredFormat">(Required)</span>
              <input type = "text"
              name = "authorFirstUpdate" id = "authorFirstUpdate" value=""
              required/> <br>

            Author's Last Name <span class = "requiredFormat">(Required)</span>
              <input type = "text"
              name = "authorLastUpdate" id = "authorLastUpdate" value=""
              required/> <br>

            Year Read <span class = "requiredFormat">(Required)</span>
              <input type = "text"
              name = "yearReadUpdate" id = "yearReadUpdate" size = "4"
              maxlength = "4" value="" required pattern = "[0-9]{4}"/> <br>

            Year Published <input type = "text" size = "4" maxlength = "4"
              name = "yearPubUpdate" id = "yearPubUpdate" value=""
              pattern = "[0-9]{4}"/> <br>

            Number of Pages <input type = "text" name = "numPgsUpdate"
              id = "numPgsUpdate"
              size = "4" value="" pattern = "\d*"/><br>

            Read for class?
            <label class = "labelContainer"> Yes <i class="fas fa-check"></i>
              <input type = "radio" id = "forClassYes" value ="yes"
                name = "forClassUpdate">
              <span class = "checkmark"></span>
            </label>

            <label class = "labelContainer">No <i class="fas fa-times"></i>
              <input type = "radio"  value = "no" id = "forClassNo"
                name = "forClassUpdate">
              <span class = "checkmark"></span>
            </label>
            <br>

            Reread?
            <label class = "labelContainer"> Yes <i class="fas fa-check"></i>
              <input type = "radio" value  = "yes" id = "rereadYes"
                name = "rereadUpdate">
              <span class = "checkmark"></span>
            </label>

            <label class = "labelContainer"> No <i class="fas fa-times"></i>
              <input type = "radio" value  = "no" id = "rereadNo"
                name = "rereadUpdate">
              <span class = "checkmark"></span>
            </label>
            <br>

            <!-- Form buttons: cancel + update -->
            <div class = "rightSide">
              <input type = "submit" name = "updateSubmit" id = "updateSubmit"
                value = "Update" class = "btn">
              <input type = "reset" name = "cancel" class = "btn"
                id = "cancelUpdateBtn" value = 'Cancel'>
            </div>
          </form>

        </div>
      </div>

      <!-- Delete entry section -->
      <div id = "deleteOverlay">
        <div id = "deleteResponsePanel"></div>
        <div id = "deletePanel">

          <!-- Form for delete entry -->
          <form id = "deleteForm" method = "post">
            Are you sure you want to delete this entry? <br><br>

            <!-- Form buttons: delete + cancel -->
            <input type = "submit" name = "deleteSubmit" value = "Delete"
              class = "btn">
            <input type = "reset" name = "cancel" class = "btn"
              id = "cancelDeleteBtn" value = 'Cancel'>
          </form>

        </div>
      </div>

      <!-- Sort Options buttons section -->
      <div id = "sortOptions">
        Sort by: <br>
        <button class = "sortBtnClick" id = "sortOrder"> Order Added
          <span class id = "sortOrderIcon"><i class="fas fa-sort-down"></i>
          </span></button>
        <button class = "sortBtn" id = "sortTitle">Title
          <span id = "sortTitleIcon"></span></button>
        <button class = "sortBtn" id = "sortAuthor">Author
          <span id = "sortAuthorIcon"></span></button>
        <button class = "sortBtn" id = "sortYearRead">Year Read
          <span id = "sortYearReadIcon"></span></button>
        <button class = "sortBtn" id = "sortYearPub">Year Published
          <span id = "sortYearPubIcon"></span></button>
        <button class = "sortBtn" id = "sortNumPgs">Number of Pages
          <span id = "sortNumPgsIcon"></span></button>
        <button class = "sortBtn" id = "sortForClass">Read for Class
          <span id = "sortForClassIcon"></span></button>
        <button class = "sortBtn" id = "sortReread">Reread
          <span id = "sortRereadIcon"></span></button>
      </div>

      <!-- Search Bar section -->
      <div id = "searchBar">
        <form id = "searchForm" method = "get" action= "php/Books/search.php">
          <input type = "search" placeholder = "Type to search . . ."
            id = "searchInput" name = "searchInput" required>
          <i class="fas fa-search"></i>
        </form>
      </div>

      <!-- Display entries section -->
      <div id = "displayPanel">
         <div id = "dataTable"></div>
      </div>

      <!-- Scroll back to top button -->
      <button id = "scrollBtn"><i class="fas fa-arrow-up" title = "Back to Top"></i></button>

    </section> <!-- END container -->

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

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- JavaScript -->
    <script src = "js/functions.js"></script>


  </body>

</html>
