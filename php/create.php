<?php
/*
  create.php

  Creates a new entry in the database after validating user input.
*/

  require_once ('database.php');
  require_once ('books.php');

  // mysql connection
  $database = new Database();
  $conn = $database->connectToDatabase();

  $bookList = new Books($conn);

  // Validate user input and check for errors
  $error = $bookList->validateInput();

  if(!$error){

    echo $bookList->create();

  }else{ // else, print error
    echo $bookList->printErr;
  }


?>
