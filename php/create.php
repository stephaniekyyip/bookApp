<?php
  /*
    Creates a new entry in the database after validating user input.
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

  require_once ('functions.php' );

  $conn = connectToDatabase();

  // Validate user input and check for errors
  $inputError = validateInput();

  if($inputError == FALSE){

    $mysql = "INSERT INTO book_list";
    $mysql .= "(title, author_first, author_last, year_read";

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

    $mysql .= ") VALUES ('$title', '$authorFirst', '$authorLast', '$yearRead'";

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

    //echo $mysql;

    if ($conn->query($mysql) == FALSE) {
      echo $mysql . " " . mysqli_error($conn);
    }else{
      echo "200";
    }

  }// end no input errors

  //Close mySQL connection
  $conn->close();

?>
