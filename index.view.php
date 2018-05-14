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

          Title <span class = "requiredFormat">(Required)</span><input type = "text" name = "title" value="<?php echo htmlspecialchars($myList->title);?>" required> <br>
          Author <span class = "requiredFormat">(Required)</span><input type = "text" name = "author" value="<?php echo htmlspecialchars($myList->author);?>"  required> <br>
          Year Read <span class = "requiredFormat">(Required)</span> <input type = "text" name = "yearRead" size = "4" maxlength = "4" value="<?php echo htmlspecialchars($myList->yearRead);?>" required><br>
          Year Published <input type = "text" size = "4" maxlength = "4" name = "yearPub" value="<?php echo htmlspecialchars($myList->yearPub);?>"> <br>
          Number of Pages <input type = "text" name = "numPgs" size = "4" value="<?php echo htmlspecialchars($myList->numPgs);?>"><br>
          Read for class?  <span style = "margin-left: 15px;">Yes</span> <input type = "radio" name = "forClass" value ="yes">
          No <input type = "radio" name = "forClass" value = "no"> <br>
          Reread?   <span style = "margin-left: 15px;">Yes</span>  <input type = "radio" name = "reread" value  = "yes">
          No <input type = "radio" name = "reread" value  = "no"> <br>
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
          <button class = "sortBtn" id = "sortOrder">Order Added</button>
          <button class = "sortBtn" id = "sortTitle">Title</button>
          <button class = "sortBtn" id = "sortAuthor">Author</button>
          <button class = "sortBtn" id = "sortYearRead">Year Read</button>
          <button class = "sortBtn" id = "sortYearPub">Year Published</button>
          <button class = "sortBtn" id = "sortNumPgs">Number of Pages</button>
          <button class = "sortBtn" id = "sortForClass">Read for Class</button>
          <button class = "sortBtn" id = "sortReread">Reread</button>

        </div>

        <div id = "updateOverlay">
          <div id = "updatePanel">
            <form method = "post">
              Title <span class = "requiredFormat">(Required)</span><input type = "text" name = "titleUpdate" value="" required> <br>
              Author <span class = "requiredFormat">(Required)</span><input type = "text" name = "authorUpdate" value=""  required> <br>
              Year Read <span class = "requiredFormat">(Required)</span><input type = "text" name = "yearReadUpdate" size = "4" maxlength = "4" value="" required><br>
              Year Published <input type = "text" size = "4" maxlength = "4" name = "yearPubUpdate" value=""> <br>
              Number of Pages <input type = "text" name = "numPgsUpdate" size = "4" value=""><br>
              Read for class?  <span style = "margin-left: 15px;">Yes</span> <input type = "radio" name = "forClass" value ="yes">
              No <input type = "radio" name = "forClassUpdate" value = "no"> <br>
              Reread?   <span style = "margin-left: 15px;">Yes</span>  <input type = "radio" name = "rereadUpdate" value  = "yes">
              No <input type = "radio" name = "rereadUpdate" value  = "no"> <br>
              <div class = "rightSide">
                <input type = "reset" name = "cancel" class = "btn" id = "cancelUpdateBtn" value = 'Cancel'>
                <input type = "submit" name = "updateSubmit" value = "Update" class = "btn">
              </div>
            </form>
          </div>
        </div>

        <div id = "deleteOverlay">
          <div id = "deletePanel">
            <form>
              Are you sure you want to delete this? <br><br>
              <input type = "submit" name = "deleteSubmit" value = "Delete " class = "btn">
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
