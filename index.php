<!DOCTYPE HTML>
<html>

  <head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width initial-scale=1.0" />

    <title> Book Tracker </title>

    <link rel= "stylesheet" href="css/style.css"/>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"
      integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
      crossorigin="anonymous">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Sanchez" rel="stylesheet">
    <!--<link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">-->

  </head>


  <body>
    <div class = "container">
      <h1>Book Tracker</h1>
      <p>Keep track of all the books you have read.</p>

      <!-- Add New Entry section -->

      <!-- Button to reveal add form -->
      <button id = "addBtn" class = "btn fullWidth">Add Book
        <i class="fab fa-readme" alt = "book icon"></i></button>

      <!-- Displays result of attempting to add new entry -->
      <div id = "addResponse"></div>

      <div id = "addPanel">

        <!-- Form for adding a new entry -->
        <form id = "addForm" action = "php/create.php" method = "post">
          Title <span class = "requiredFormat">(Required)</span>
            <input type = "text"
            name = "title" value="" required
            oninvalid="setCustomValidity('Please enter the title of the book.')"
            oninput="setCustomValidity('')"> <br>
          Author First Name <span class = "requiredFormat">(Required)</span>
            <input type = "text"
            name = "authorFirst" value=""  required
            oninvalid="setCustomValidity('Please enter the author's first name.')"
            oninput="setCustomValidity('')"> <br>
          Author Last Name <span class = "requiredFormat">(Required)</span>
            <input type = "text"
            name = "authorLast" value=""  required
            oninvalid="setCustomValidity('Please enter the author's last name.')"
            oninput="setCustomValidity('')"> <br>
          Year Read <span class = "requiredFormat">(Required)</span>
            <input type = "text"
            name = "yearRead" size = "4" maxlength = "4" value=""
            pattern = "[0-9]{4}" required
            oninvalid="setCustomValidity('Please enter a valid year.')"
            oninput="setCustomValidity('')"><br>
          Year Published <input type = "text" size = "4" maxlength = "4"
            name = "yearPub" name = "yearPub" value="" pattern = "[0-9]{4}"
            oninvalid="setCustomValidity('Please enter a valid year.')"
            oninput="setCustomValidity('')"> <br>
          Number of Pages <input type = "text" name = "numPgs" size = "4" value=""
            pattern = "\d*" oninvalid="setCustomValidity('Please enter a number.')"
            oninput="setCustomValidity('')"><br>

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
            <input type = "reset" name = "cancel" class = "btn"
              id = "cancelAddBtn" value = 'Cancel'>
            <input type = "submit" name = "addSubmit" id = "addSubmitBtn"
              value = "Submit" class = "btn">
          </div>
        </form>

      </div>

      <!-- Update Entry section -->
      <div id = "updateOverlay">

        <!-- Replaces update form when update is successful (no errors) -->
        <div id = "updateSuccessPanel">
          <!--<div class = "updateSuccessClose"><i class='far fa-window-close'
            id = "closeUpdateBtn"></i></div>-->
          Sucessfully Updated!
        </div>

        <div id = "updatePanel">

          <!-- Shows any errors when submitting update form -->
          <div id = "updateFailed"></div>

          <!-- Form for update entry  -->
          <form method = "put" id = "updateForm" action = "php/update.php">
            Title <span class = "requiredFormat">(Required)</span>
              <input type = "text"
              name = "titleUpdate" id = "titleUpdate" value="" required
              oninvalid="setCustomValidity('Please enter the title of the book.')"
              oninput="setCustomValidity('')"> <br>
            Author's First Name <span class = "requiredFormat">(Required)</span>
              <input type = "text"
              name = "authorFirstUpdate" id = "authorFirstUpdate" value=""
              required
              oninvalid="setCustomValidity('Please enter the author's first name.')"
              oninput="setCustomValidity('')"> <br>
            Author's Last Name <span class = "requiredFormat">(Required)</span>
              <input type = "text"
              name = "authorLastUpdate" id = "authorLastUpdate" value=""
              required
              oninvalid="setCustomValidity('Please enter the author's last name.')"
              oninput="setCustomValidity('')"> <br>
            Year Read <span class = "requiredFormat">(Required)</span>
              <input type = "text"
              name = "yearReadUpdate" id = "yearReadUpdate" size = "4"
              maxlength = "4" value="" required pattern = "[0-9]{4}"
              oninvalid="setCustomValidity('Please enter a valid year.')"
              oninput="setCustomValidity('')"> <br>
            Year Published <input type = "text" size = "4" maxlength = "4"
              name = "yearPubUpdate" id = "yearPubUpdate" value=""
              pattern = "[0-9]{4}"
              oninvalid="setCustomValidity('Please enter a valid year.')"
              oninput="setCustomValidity('')"> <br>
            Number of Pages <input type = "text" name = "numPgsUpdate"
              id = "numPgsUpdate"
              size = "4" value="" pattern = "\d*"
              oninvalid="setCustomValidity('Please enter a number.')"
              oninput="setCustomValidity('')"><br>

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
              <input type = "reset" name = "cancel" class = "btn"
                id = "cancelUpdateBtn" value = 'Cancel'>
              <input type = "submit" name = "updateSubmit" id = "updateSubmit"
                value = "Update" class = "btn">
            </div>
          </form>

        </div>
      </div>

      <!-- Delete entry section -->
      <div id = "deleteOverlay">
        <div id = "deleteResponsePanel">
          <!--<div class = "deleteSuccessClose"><i class='far fa-window-close'
            id = "closeDeleteBtn"></i></div>-->
          Sucessfully deleted!
        </div>
        <div id = "deletePanel">

          <!-- Form for delete entry -->
          <form id = "deleteForm" method = "delete">
            Are you sure you want to delete this entry? <br><br>

            <!-- Form buttons: delete + cancel -->
            <input type = "submit" name = "deleteSubmit" value = "Delete"
              class = "btn">
            <input type = "reset" name = "cancel" class = "btn"
              id = "cancelDeleteBtn" value = 'Cancel'>
          </form>

        </div>
      </div>

      <!-- Sort Options section -->
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

      <div id = "searchBar">
        <form id = "searchForm" method = "get" action= "php/search.php">
          <input type = "search" placeholder = "Type to search . . ."
            id = "searchInput" name = "searchInput" class = "fullWidth"
            required oninvalid="setCustomValidity('Please enter a search query')">
          <i class="fas fa-search"></i>
        </form>
      </div>

      <!-- Display entries section -->
      <div id = "displayPanel">

         <div id = "dataTable"></div>

      </div>

      <button id = "scrollBtn"><i class="fas fa-arrow-up"></i></button>

    </div> <!-- END container -->

    <!-- SCRIPTS -->
      <!-- jQuery -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

      <script src = "js/showHidePanels.js"></script>

  </body>

  <footer>
    Made by Stephanie Yip 2018
  </footer>


</html>
