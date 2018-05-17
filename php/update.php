<?php
  /* Updates the selected entry in database using user input */

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

  $entryData = ""; // Stores current data in selected entry

  require_once ('functions.php' );

  $conn = connectToDatabase();

  // Validate user input
  validateInput();

  // Check for errors
  $inputError = printErr();

  if (!empty($_POST['id']) && $inputError == FALSE){
    //Query for entry to be edited using id
    $mysql = "SELECT * from book_list WHERE ID = '";
    $mysql .= $_POST['id'];
    $mysql .= "'";

    $entryData = $conn->query($mysql);

    // Stores list of user inputs
    $inputList = array("title" => $title, "author" => $author,
    "year_read" => $yearRead, "year_pub" => $yearPub, "num_pgs" => $numPgs,
    "for_class" => $forClass, "reread" => $reread);

    // Stores whether the inputs have been changed by the user
    $changeList = array("title" => FALSE, "author" => FALSE,
    "year_read" => FALSE, "year_pub" => FALSE, "num_pgs" => FALSE,
    "for_class" => FALSE, "reread" => FALSE);

    if ($entryData->num_rows > 0){

      // Check if the values in the entry have been edited
      while($row = $entryData->fetch_assoc()){

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

    // Begin SQL update query
    $mysql = "UPDATE book_list SET ";
    $needComma = FALSE; // Determines whether a comma is needed in the SQL query

    foreach($changeList as $input => $val){
      // if input has been changed, add to SQL query
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
    } // end foreach

    $mysql .= "WHERE ID = '";
    $mysql .= $_POST['id'];
    $mysql .= "'";

    if($conn->query($mysql)== TRUE){
      echo "Success";
    }else{
      echo "Update failed!";
    }

  } //end if id is not empty

  //Close mySQL connection
  $conn->close();

?>
