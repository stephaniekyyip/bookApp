<html>

  <head>

    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width initial-scale=1.0" />

    <title> Book Tracker </title>

    <link rel= "stylesheet" href="css/style.css"/>
    <link href="https://fonts.googleapis.com/css?family=Sanchez" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script src = "js/addBookForm.js"></script>

    <!--Font Awesome  -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">

  </head>

  <?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $db_name  = "bookApp";
    $mysql  = "";
    $error = 0;
    $success = FALSE;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";

    // Create
    $title = $author = $forClass = $reread = $yearRead = $yearPub = $numPgs = "";

    $titleErr = $authorErr = $forClassErr = $rereadErr = $yearReadErr = $yearPubErr = $numPgsErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addSubmit"])){

      if(empty($_POST["title"])){
        $titleErr = "Error: Title is missing!";
      }else{
        $title = test_input($_POST["title"]);
        $titleErr = "";
      }

      if(empty($_POST["author"])){
        $authorErr = "Error: Author is missing!";
      }else{
        $author = test_input($_POST["author"]);
        $authorErr = "";
      }

      if(empty($_POST["yearRead"])){
        $yearReadErr = "Year Read is missing!";
      }else if (!is_numeric($_POST["yearRead"])){
        $yearReadErr = "Error: Year Read. Please enter a number.";
      }else{
        $yearRead = test_input($_POST["yearRead"]);
        $yearReadErr = "";
      }

      if(isset($_POST["forClass"])){
        $forClass = $_POST["forClass"];
      }

      if(isset($_POST["reread"])){
        $reread = $_POST["reread"];
      }

      if(!empty($_POST["yearPub"]) && !is_numeric($_POST["yearPub"])){
        $yearPubErr = "Error: Year Published. Please enter a number.";
      }else if (!empty($_POST["yearPub"])){
        $yearPub = test_input($_POST["yearPub"]);
        $yearPubErr = "";
      }

      if(!empty($_POST["numPgs"]) && !is_numeric($_POST["numPgs"])){
        $numPgsErr = "Error: Number of Pages. Please enter a number.";
      }else if (!empty($_POST["numPgs"])){
        $numPgs = test_input($_POST["numPgs"]);
        $numPgsErr = "";
      }

      // check for errors
      $errList = array($titleErr, $authorErr, $yearReadErr, $yearPubErr, $numPgsErr , $forClassErr, $rereadErr);

      foreach($errList as $val){
        if($val != ""){
          echo "<br>error: " . $val . "<br>";
          $error = 1;
        }
      }

      // if there are no errors, create new entry in mysql table
      if(!$error){
        $mysql .= "INSERT INTO book_list";
        $mysql .= "(title, author, year_read";

        if($yearPub != ""){
          $mysql .= ", year_pub";
        }

        if($numPgs != ""){
          $mysql .= ", num_pgs";
        }

        if($forClass != ""){
          $mysql .= ", for_class";
        }

        if ($reread != ""){
          $mysql .= ", reread";
        }

        $mysql .= ") VALUES ('$title', '$author', '$yearRead'";

        if($yearPub != ""){
          $mysql .= ", '$yearPub'";
        }

        if($numPgs != ""){
          $mysql .= ", '$numPgs'";
        }

        if($forClass != ""){
          if($forClass == "yes"){
            $mysql .= ", 1";
          }else{
            $mysql .= ", 0";
          }
        }

        if($reread != ""){
          if($reread  == "yes"){
            $mysql .= ", 1";
          }else{
            $mysql .= ", 0";
          }
        }

        $mysql .= ")";

        if ($conn->query($mysql) == TRUE) {
          $success = TRUE;
          echo "<br>success";
        } else {
          $success = FALSE;
          echo "<br>failed " . $mysql . " " . mysqli_error($conn);
        }

        //Prevent form resubmission
        header("Location:index.php");

      } // end no errors

    } //end CREATE


    // READ
    $mysql = "SELECT * FROM book_list";
    $data = $conn->query($mysql);


    //reset values
    if (isset($_POST["cancel"])){
      $title = $author = $forClass = $reread = $yearRead = $yearPub = $numPgs = "";
    }

    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    $conn->close();

  ?>



  <body>
    <div class = "container">
      <h1>Book Tracker</h1>
      <p>Keep track of all the books you have read.</p>

      <button id = "addBtn" class = "btn">Add Book <i class="fab fa-readme" alt = "book icon"></i></button>
      <div id = "addResponse">
        <?php

        $errList = array($titleErr, $authorErr, $yearReadErr, $yearPubErr, $numPgsErr , $forClassErr, $rereadErr);
          echo "<ul>";
          foreach($errList as $value){
            if($value != ""){
              echo "<li>$value</li> <br>";
            }
          }
          echo "</ul>";

          if ($success == TRUE && !$error){
            echo "Successfully added $title! <br>";

            $success  = FALSE;
            $title = $author = $forClass = $reread = $yearRead = $yearPub = $numPgs = "";

          }

        ?>

      </div>

      <div id = "addPanel">
        <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">

          Title <span class = "requiredFormat">(Required)</span><input type = "text" name = "title" value="<?php echo htmlspecialchars($title);?>" required> <br>
          Author <span class = "requiredFormat">(Required)</span><input type = "text" name = "author" value="<?php echo htmlspecialchars($author);?>"  required> <br>
          Year Read <span class = "requiredFormat">(Required)</span> <input type = "text" name = "yearRead" size = "4" maxlength = "4" value="<?php echo htmlspecialchars($yearRead);?>" required><br>
          Year Published <input type = "text" size = "4" maxlength = "4" name = "yearPub" value="<?php echo htmlspecialchars($yearPub);?>"> <br>
          Number of Pages <input type = "text" name = "numPgs" size = "4" value="<?php echo htmlspecialchars($numPgs);?>"><br>
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
        <?php
          if ($data->num_rows > 0){
            echo "<div class = 'rightSide'>
              Sort by:
              <select name = 'sortMenu' id = 'sortMenu'>
                <option value = '0'>Title</option>
                <option value = '1'>Author</option>
                <option value = '2'>Year Read</option>
                <option value = '3'>Year Published</option>
                <option value = '4'>Number of Pages</option>
                <option value = '5'>Read for Class</option>
                <option value = '6'>Reread</option>
              </select>

            </div>";


            while($row = $data->fetch_assoc()){
              echo "<div class = 'year'>" . $row["year_read"] . "</div><div class = 'titleAuthor'>" .
              $row["title"] . " by "  . $row["author"] . "</div>";

              echo "<div class = 'bookInfo'>";

              if ($row["year_pub"] != ""){
                echo "Published in " . $row["year_pub"];
              }

              if ($row["num_pgs"] != ""){
               echo "<br>" . $row["num_pgs"] . " pages" ;
              }

              if ($row["for_class"] != ""){
                if($row["for_class"] == 1){
                  echo "<br> Read for class " ;
                }
              }

              if ($row["reread"] != ""){
                if($row["reread"] == 1){
                  echo "<br> Reread ";
                }
              }

              echo "</div><div class = 'line'></div>";

            } //end while

          }else{
            echo "No books here <i class='far fa-frown'></i>";
          }

        ?>
      </div>



    </div> <!-- END container -->


  </body>

  <footer>
    Made by Stephanie Yip 2018
  </footer>

</html>

<?php



?>
