<?php
  /*
    Updates the selected entry in the database using user input, if any changes
    have been made.
  */

  // Stores user inputs
  $title = "";
  $authorFirst = "";
  $authorLast = "";
  $yearRead = "";
  $yearPub = "";
  $numPgs = "";
  $forClass = "";
  $reread = "";

  // Errors messages for user input
  $titleErr = "";
  $authorFirstErr = "";
  $authorLastErr = "";
  $yearReadErr = "";
  $yearPubErr = "";
  $numPgsErr = "";
  $forClassErr = "";
  $rereadErr = "";
  $inputError = FALSE;

  $entryData = ""; // Stores current data in selected entry

  require_once ('functions.php' );

  $conn = connectToDatabase();

  // Validate user input and check for errors
  $inputError = validateInput();

  if (!empty($_POST['id']) && $inputError == FALSE){
    //Query for entry to be edited using id
    $mysql = "SELECT * from book_list WHERE ID = '";
    $mysql .= $_POST['id'];
    $mysql .= "'";

    $entryData = $conn->query($mysql);

    // Stores list of user inputs
    $inputList = array("title" => $title, "author_first" => $authorFirst,
      "author_last" => $authorLast, "year_read" => $yearRead,
      "year_pub" => $yearPub, "num_pgs" => $numPgs, "for_class" => $forClass,
      "reread" => $reread);

    // Stores whether the inputs have been changed by the user
    $changeList = array("title" => FALSE, "author_first" => FALSE,
      "author_last" => FALSE, "year_read" => FALSE, "year_pub" => FALSE,
      "num_pgs" => FALSE, "for_class" => FALSE, "reread" => FALSE);

    if ($entryData->num_rows > 0){

      // Check if the values in the entry have been edited
      while($row = $entryData->fetch_assoc()){

        if ($title != $row["title"]){
          $changeList["title"] = TRUE;
        }
        if($authorFirst != $row["author_first"]){
          $changeList["author_first"] = TRUE;
        }
        if($authorLast != $row["author_last"]){
          $changeList["author_last"] = TRUE;
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
    $isChanged = FALSE; // Checks if any of the user inputs have been changed

    foreach($changeList as $input => $val){
      // if current input has been changed, add to SQL query
      if($val == TRUE){
        $isChanged = TRUE; //at least one of the user inputs have been changed

        if($needComma == TRUE){
          $mysql .= ", ";
        }

        $mysql .= $input . " = ";

        if($inputList[$input] != ""){
          $mysql .= "'" . $inputList[$input] . "' ";
        }else{
          $mysql .= "NULL ";
        }

        $needComma = TRUE;
      }
    } // end foreach

    // If entry inputs have been changed
    if($isChanged){
      $mysql .= "WHERE ID = '" . $_POST['id'] . "'";

      if($conn->query($mysql)== TRUE){
        echo "200";
      }else{
        echo "404";
      }
    }else{
      echo "204";
    }

  }//end if id is not empty and no input errors

  //Close mySQL connection
  $conn->close();

?>
