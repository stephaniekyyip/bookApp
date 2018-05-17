<!DOCTYPE HTML>
<html>

  <head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width initial-scale=1.0" />

    <title> Book Tracker </title>

    <link rel= "stylesheet" href="css/style.css"/>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- jQuery UI -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Sanchez" rel="stylesheet">

    <!-- Radio button formatting from https://codepen.io/triss90/pen/XNEdRe-->


    <script src = "js/showHidePanels.js"></script>
    <script src = "js/toggleSortBtns.js"></script>

  </head>


  <?php $myList->connectToDatabase(); ?>
  <?php $myList->createEntry(); ?>

  <?php $myList->resetInput(); ?>

  <body>
    <div class = "container">
      <h1>Book Tracker</h1>
      <p>Keep track of all the books you have read.</p>

      <button id = "addBtn" class = "btn fullWidth">Add Book <i class="fab fa-readme" alt = "book icon"></i></button>

      <div id = "addResponse">
        <?php $myList->printErr(); ?>

      </div>

      <div id = "addPanel">
        <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">

          Title <span class = "requiredFormat">(Required)</span><input type = "text" name = "title" value="" required> <br>
          Author <span class = "requiredFormat">(Required)</span><input type = "text" name = "author" value=""  required> <br>
          Year Read <span class = "requiredFormat">(Required)</span> <input type = "text" name = "yearRead" size = "4" maxlength = "4" value="" required><br>
          Year Published <input type = "text" size = "4" maxlength = "4" name = "yearPub" name = "yearPub" value=""> <br>
          Number of Pages <input type = "text" name = "numPgs" size = "4" value=""><br>


          Read for class?
          <label class = "labelContainer"> Yes
            <input type = "radio" name = "forClass" value ="yes" id = "forClassYesAdd">
            <span class = "checkmark"></span>
          </label>

          <label class = "labelContainer"> No
            <input type = "radio" name = "forClass" value = "no" id = "forClassNoAdd">
            <span class = "checkmark"></span>
          </label>
          <br>

          Reread?
          <label class = "labelContainer"> Yes
            <input type = "radio" name = "reread" value  = "yes" id = "rereadYesAdd">
            <span class = "checkmark"></span>
          </label>

          <label class = "labelContainer"> No
            <input type = "radio" name = "reread" value  = "no" id = "rereadNoAdd">
            <span class = "checkmark"></span>
          </label>
          <br>

          <div class = "rightSide">
            <input type = "reset" name = "cancel" class = "btn" id = "cancelBtn" value = 'Cancel'>
            <input type = "submit" name = "addSubmit" value = "Submit" class = "btn">
          </div>
        </form>

      </div>


      <div id = "displayPanel">

        <!--<div class = 'rightSide'>
            Sort by:
            <select name = 'sortMenu' id = "sortMenu">
              <option value = 'orderAdded'>Order Added</option>
              <option value = 'title'>Title</option>
              <option value = 'author'>Author</option>
              <option value = 'yearRead'>Year Read</option>
              <option value = 'yearPub'>Year Published</option>
              <option value = 'numPgs'>Number of Pages</option>
              <option value = 'forClass'>Read for Class</option>
              <option value = 'reread'>Reread</option>
            </select>

        </div>-->
        Sort by:
        <div id = "sortOptions">
          <button class = "sortBtnClick" id = "sortOrder">Order Added <i class="fas fa-sort-down"></i></button>
          <button class = "sortBtn" id = "sortTitle">Title</button>
          <button class = "sortBtn" id = "sortAuthor">Author</button>
          <button class = "sortBtn" id = "sortYearRead">Year Read</button>
          <button class = "sortBtn" id = "sortYearPub">Year Published</button>
          <button class = "sortBtn" id = "sortNumPgs">Number of Pages</button>
          <button class = "sortBtn" id = "sortForClass">Read for Class</button>
          <button class = "sortBtn" id = "sortReread">Reread</button>
        </div>

        <div id = "updateOverlay">
          <div id = "updateSuccessPanel">
            <div class = "updateSuccessClose"><i class='far fa-window-close' id = "closeUpdateBtn"></i></div>
            Successfully updated!
          </div>
          <div id = "updatePanel">
            <div id = "updateFailed"></div>
            <form method = "post" id = "updateForm" action = "php/update.php">
              Title <span class = "requiredFormat">(Required)</span><input type = "text" name = "titleUpdate" id = "titleUpdate" value="" required> <br>
              Author <span class = "requiredFormat">(Required)</span><input type = "text" name = "authorUpdate" id = "authorUpdate" value="" required> <br>
              Year Read <span class = "requiredFormat">(Required)</span><input type = "text" name = "yearReadUpdate" id = "yearReadUpdate" size = "4" maxlength = "4" value="" required pattern = "\d*" oninvalid="setCustomValidity('Please enter a number.')"  oninput="setCustomValidity('')"><br>
              Year Published <input type = "text" size = "4" maxlength = "4" name = "yearPub" id = "yearPubUpdate" value="" pattern = "\d*" oninvalid="setCustomValidity('Please enter a number.')"  oninput="setCustomValidity('')"> <br>
              Number of Pages <input type = "text" name = "numPgs" name = "numPgs" id = "numPgsUpdate"  size = "4" value="" pattern = "\d*" oninvalid="setCustomValidity('Please enter a number.')"  oninput="setCustomValidity('')"><br>

              Read for class?
              <label class = "labelContainer"> Yes
                <input type = "radio" name = "forClass" id = "forClassYes" value ="yes">
                <span class = "checkmark"></span>
              </label>

              <label class = "labelContainer">No
                <input type = "radio" name = "forClass" value = "no" id = "forClassNo">
                <span class = "checkmark"></span>
              </label>
              <br>

              Reread?
              <label class = "labelContainer"> Yes
                <input type = "radio" name = "reread" value  = "yes" id = "rereadYes">
                <span class = "checkmark"></span>
              </label>

              <label class = "labelContainer"> No
                <input type = "radio" name = "reread" value  = "no" id = "rereadNo">
                <span class = "checkmark"></span>
              </label>
              <br>

              <div class = "rightSide">
                <input type = "reset" name = "cancel" class = "btn" id = "cancelUpdateBtn" value = 'Cancel'>
                <input type = "submit" name = "updateSubmit" id = "updateSubmit" value = "Update" class = "btn">
              </div>
            </form>
          </div>
        </div>

        <div id = "deleteOverlay">
          <div id = "deletePanel">
            <form id = "deleteForm">
              Are you sure you want to delete this? <br><br>
              <input type = "submit" name = "deleteSubmit" value = "Delete" class = "btn">
              <input type = "reset" name = "cancel" class = "btn" id = "cancelDeleteBtn" value = 'Cancel'>
            </form>
          </div>
        </div>

         <?php $myList->readEntries(); ?>

         <div id = "dataTable">
           <?php $myList->printData(); ?>
        </div>

      </div> <!-- END displayPanel -->

      <button id = "backToTop" class = "btn fullWidth">

        Back to Top <i class="fas fa-level-up-alt"></i>

      </button>

    </div> <!-- END container -->

  </body>

  <footer>
    Made by Stephanie Yip 2018
  </footer>

  <?php $myList->endDbConnection(); ?>

</html>
