<?php

// ----------------------------------------------------------------------------
// create.php
//
// Creates a new entry in the database after validating user input.
// ----------------------------------------------------------------------------
  session_start();

  require_once ('../database.php');
  require_once ('books.php');

  $bookList = new Books($conn);

  // Validate user input and check for errors
  $error = $bookList->validateInput();

  // If no errors, create new entry in DB
  if(!$error){
    echo $bookList->create();
  }else{ // else, print error
    echo $bookList->printErr;
  }


?>
