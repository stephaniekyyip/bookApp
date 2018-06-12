<?php

// ----------------------------------------------------------------------------
// login.php
//
// Handles user signing into their account.
// ----------------------------------------------------------------------------
  session_start();

  require_once ('../database.php');
  require_once ('users.php');

  // mysql connection
  $database = new Database();
  $conn = $database->connectToDatabase();

  $usersList = new Users($conn);

  echo $usersList->verifyLogin();
