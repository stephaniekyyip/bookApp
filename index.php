<html>

  <head>

    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width initial-scale=1.0" />

    <title> Book Tracker </title>

    <link rel= "stylesheet" href="css/style.css"/>
    <link href="https://fonts.googleapis.com/css?family=Sanchez" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src = "js/addBookForm.js"></script>
    <!-- <script src = "php/create.php"></script>-->
    <!--Font Awesome  -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">

  </head>

  <?php

    $servername = "localhost";
    $username = "root";
    $password = "";

    // Create connection
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";

    // Create
    $title = $author = $forClass = $reread = $yearRead = $yearPub = $numPgs = "";

    $titleErr = $authorErr = $forClassErr = $rereadErr = $yearReadErr = $yearPubErr = $numPgsErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

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

    } //end POST

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

  ?>



  <body>
    <div class = "container">
      <h1>Book Tracker</h1>
      <p>Keep track of all the books you have read.</p>

      <button id = "addBtn" class = "btn">Add New Book <i class="fab fa-readme" alt = "book icon"></i></button>
      <div id = "addResponse">
        <?php

          $error = 0;

          // check for errors if submission was not canceled
          if (!isset($_POST["cancel"])){
            $errList = array($titleErr, $authorErr, $yearReadErr, $yearPubErr, $numPgsErr , $forClassErr, $rereadErr);
            echo "<ul>";
            foreach($errList as $value){
              if($value != ""){
                echo "<li>$value</li> <br>";
                $error = 1;
              }
            }
            echo "</ul>";
          }

          if (isset($_POST["submit"]) && !$error){
            echo "Successfully added $title! <br>";

            //reset values after successful submission
            $title = $author = $forClass = $reread = $yearRead = $yearPub = $numPgs = "";
          }

        ?>

      </div>

      <div id = "addPanel">
        <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">

          Title <span class = "requiredFormat">(Required)</span><input type = "text" name = "title" value="<?php echo htmlspecialchars($title);?>" > <br>
          Author <span class = "requiredFormat">(Required)</span><input type = "text" name = "author" value="<?php echo htmlspecialchars($author);?>"  > <br>
          Year Read <span class = "requiredFormat">(Required)</span> <input type = "text" name = "yearRead" size = "4" maxlength = "4" value="<?php echo htmlspecialchars($yearRead);?>"><br>
          Year Published <input type = "text" size = "4" maxlength = "4" name = "yearPub" value="<?php echo htmlspecialchars($yearPub);?>"> <br>
          Number of Pages <input type = "text" name = "numPgs" size = "4" value="<?php echo htmlspecialchars($numPgs);?>"><br>
          Read for class?  <span style = "margin-left: 15px;">Yes</span> <input type = "radio" name = "forClass" value ="yes">
          No <input type = "radio" name = "forClass" value = "no"> <br>
          Reread?   <span style = "margin-left: 15px;">Yes</span>  <input type = "radio" name = "reread" value  = "yes">
          No <input type = "radio" name = "reread" value  = "no"> <br>
          <div class = "rightSide">
            <input type = "reset" name = "cancel" class = "btn" id = "cancelBtn" value = 'Cancel'>
            <input type = "submit" name = "submit" id = "addSubmit" value = "Submit" class = "btn">
          </div>
        </form>
      </div>
    </div>


  </body>

  <footer>
    Made by Stephanie Yip 2018
  </footer>

</html>

<?php



?>
