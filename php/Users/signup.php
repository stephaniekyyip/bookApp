<?php

// ----------------------------------------------------------------------------
// signup.php
//
// Handles a new user signing up for an account.
// ----------------------------------------------------------------------------

  require_once ('../database.php');
  require_once ('users.php');

  // mysql connection
  $database = new Database();
  $conn = $database->connectToDatabase();

  $usersList = new Users($conn);

  //check for invalid user input
  $error = $usersList->validateInput();

  if($error){
    echo json_encode($usersList->errList);
  }else{
    echo $usersList->create();
  }
