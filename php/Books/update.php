<?php

// ----------------------------------------------------------------------------
// update.php
//
// Updates the selected entry in the database using user input, if any changes
// have been made.
// ----------------------------------------------------------------------------

  require_once ('../database.php');
  require_once ('books.php');

  //mysql connection
  $database = new Database();
  $conn = $database->connectToDatabase();

  $bookList = new Books($conn);

  // Validate user input and check for errors
  $inputError = $bookList->validateInput();

  // if no errors, update selected entry in DB
  if (!empty($_POST['id']) && $inputError == FALSE){
    echo $bookList->update($_POST['id']);
  }else if ($inputError){ // else, print errors
    echo $bookList->printErr;
  }


?>
