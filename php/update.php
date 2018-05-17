<?php

$servername = "localhost";
$username = "root";
$password = "";
$db_name  = "bookApp";
$conn = new mysqli($servername, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function test_input($data) {
 $data = trim($data);
 $data = stripslashes($data);
 $data = htmlspecialchars($data);
 return $data;
}

  // Stores user inputs
  $title = "";
  $author = "";
  $yearRead = "";
  $yearPub = "";
  $numPgs = "";
  $forClass = "";
  $reread = "";

  // Errors messages for user input
  $titleErr = "";
  $authorErr = "";
  $yearReadErr = "";
  $yearPubErr = "";
  $numPgsErr = "";
  $forClassErr = "";
  $rereadErr = "";
  $inputError = FALSE;

  $displayData = "";

 // Validate user input and add error messages when necessary
 if(empty($_POST["title"])){
   $titleErr = "Error: Title is missing!";
 }else{
   $title = test_input($_POST["title"]);
 }

 if(empty($_POST["author"])){
   $authorErr = "Error: Author is missing!";
 }else{
   $author = test_input($_POST["author"]);
 }

 if(empty($_POST["yearRead"])){
   $yearReadErr = "Year Read is missing!";
 }else if (!is_numeric($_POST["yearRead"])){
   $yearReadErr = "Error: Year Read. Please enter a number.";
 }else{
   $yearRead = test_input($_POST["yearRead"]);
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
 }

 if(!empty($_POST["numPgs"]) && !is_numeric($_POST["numPgs"])){
   $numPgsErr = "Error: Number of Pages. Please enter a number.";
 }else if (!empty($_POST["numPgs"])){
   $numPgs = test_input($_POST["numPgs"]);
 }

 // check for errors
 $errList = array($titleErr, $authorErr, $yearReadErr, $yearPubErr, $numPgsErr , $forClassErr, $rereadErr);
 foreach($errList as $printErr){
   if($printErr != ""){
     echo "<br>error: " . $printErr . "<br>";
     $inputError = TRUE;
   }
 }

 if (!empty($_POST['id'])){
  //Query for entry to be edited using id
  $mysql = "SELECT * from book_list WHERE ID = '";
  $mysql .= $_POST['id'];
  $mysql .= "'";

  $displayData = $conn->query($mysql);

  $inputList = array("title" => $title, "author" => $author,
  "year_read" => $yearRead, "year_pub" => $yearPub, "num_pgs" => $numPgs,
  "for_class" => $forClass, "reread" => $reread);

  $changeList = array("title" => FALSE, "author" => FALSE,
  "year_read" => FALSE, "year_pub" => FALSE, "num_pgs" => FALSE,
  "for_class" => FALSE, "reread" => FALSE);

  if ($displayData->num_rows > 0){

    // Check if the values in the entry have been edited
    while($row = $displayData->fetch_assoc()){

      if ($title != $row["title"]){
        $changeList["title"] = TRUE;
      }
      if($author != $row["author"]){
        $changeList["author"] = TRUE;
      }
      if($yearRead != $row["year_read"]){
        $changeList["year_read"]= TRUE;
      }
      if($yearPub != $row["year_pub"]){
        $changeList["year_pub"] = TRUE;
      }
      if($numPgs != $row["num_pgs"]){
        $changeList["num_pgs"] = TRUE;
      }
      if($forClass != $row["for_class"]){
        $changeList["for_class"] = TRUE;
      }
      if($reread != $row["reread"]){
        $changeList["reread"] = TRUE;
      }

    } // end while
  } // end if num_rows > 0

  $needComma = FALSE;
  $mysql = "UPDATE book_list SET ";

  foreach($changeList as $input => $val){
    if($val == TRUE){

      if($needComma == TRUE){
        $mysql .= ", ";
      }

      $mysql .= $input;
      $mysql .= " = ";

      if($inputList[$input] != ""){
        $mysql .= "'";
        $mysql .= $inputList[$input];
        $mysql .= "' ";
      }else{
        $mysql .= "NULL ";
      }

      $needComma = TRUE;
    }
  }

  $mysql .= "WHERE ID = '";
  $mysql .= $_POST['id'];
  $mysql .= "'";

  // $mysql;

  if($conn->query($mysql)== TRUE){
    echo "Success";
  }else{
    echo "Update failed!";
  }

} //end if id is not empty


/*
  if(!$inputError){
    echo "SUCCESS";
  }*/


?>
