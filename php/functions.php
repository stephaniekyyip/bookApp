<?php

  function connectToDatabase(){
     $servername = "localhost";
     $username = "root";
     $password = "";
     $db_name  = "bookApp";
     $conn = new mysqli($servername, $username, $password, $db_name);

     // Check connection
     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     }
     // echo "Connected successfully";

     return $conn;
   }

   function test_input($data) {
     $conn = connectToDataBase();

     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     $data = $conn->real_escape_string($data);
     return $data;
   }

   function printErr(){
     $inputError = FALSE; //check for errors in user input
     $startErrList = TRUE; // start an error <ul> if there is an error

      // if there are errors in the user input, print errors
     $errList = array($GLOBALS['titleErr'], $GLOBALS['authorFirstErr'],
      $GLOBALS['authorLastErr'],$GLOBALS['yearReadErr'],$GLOBALS['yearPubErr'],
      $GLOBALS['numPgsErr'], $GLOBALS['forClassErr'], $GLOBALS['rereadErr']);

     foreach($errList as $printErr){
       if($printErr != ""){

         if($startErrList == TRUE){
           echo "<ul>";
           $startErrList = FALSE;
         }

         echo "<li>$printErr</li> <br>";
         $inputError = TRUE;
       }
     }

     if($inputError == TRUE){
       echo "</ul>";
     }

     return $inputError;

     // reset err messages
     $GLOBALS['titleErr'] = $GLOBALS['authorFirstErr'] = $GLOBALS['authorLastErr']
      = $GLOBALS['forClassErr'] = $GLOBALS['rereadErr'] = $GLOBALS['yearReadErr']
      = $GLOBALS['yearPubErr'] = $GLOBALS['numPgsErr'] = "";

   }

   function validateInput(){
     // Validate user input and add error messages when necessary
     if(empty($_POST["title"])){
       $GLOBALS['titleErr'] = "Error: Title is missing!";
     }else{
       $GLOBALS['title'] = test_input($_POST["title"]);
     }

     if(empty($_POST["authorFirst"])){
       $GLOBALS['authorFirstErr'] = "Error: Author first name is missing!";
     }else{
       $GLOBALS['authorFirst'] = test_input($_POST["authorFirst"]);
     }

     if(empty($_POST["authorLast"])){
       $GLOBALS['authorLastErr'] = "Error: Author last name is missing!";
     }else{
       $GLOBALS['authorLast'] = test_input($_POST["authorLast"]);
     }

     if(empty($_POST["yearRead"])){
       $GLOBALS['yearReadErr'] = "Year Read is missing!";
     }else if (!is_numeric($_POST["yearRead"])){
       $GLOBALS['yearReadErr'] = "Error: Year Read. Please enter a number.";
     }else{
       $GLOBALS['yearRead'] = test_input($_POST["yearRead"]);
     }

     if(isset($_POST["forClass"])){
       $GLOBALS['forClass'] = $_POST["forClass"];
     }

     if(isset($_POST["reread"])){
       $GLOBALS['reread']= $_POST["reread"];
     }

     if(!empty($_POST["yearPub"]) && !is_numeric($_POST["yearPub"])){
       $GLOBALS['yearPubErr'] = "Error: Year Published. Please enter a number.";
     }else if (!empty($_POST["yearPub"])){
       $GLOBALS['yearPub'] = test_input($_POST["yearPub"]);
     }

     if(!empty($_POST["numPgs"]) && !is_numeric($_POST["numPgs"])){
       $GLOBALS['numPgsErr'] = "Error: Number of Pages. Please enter a number.";
     }else if (!empty($_POST["numPgs"])){
       $GLOBALS['numPgs'] = test_input($_POST["numPgs"]);
     }

     printErr();

   }



?>
