<?php
  /* Create new entry in database using user input */

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

  require_once ('functions.php' );

  $conn = connectToDatabase();

  // Validate user input
  validateInput();

  // Check for errors
  $inputError = printErr();

  if($inputError == FALSE){

    $mysql = "INSERT INTO book_list";
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

    //echo $mysql;

    if ($conn->query($mysql) == FALSE) {
      echo "<br>failed " . $mysql . " " . mysqli_error($conn);
    }else{
      echo "Success";
    }

  }// end no input errors

  //Close mySQL connection
  $conn->close();

?>
