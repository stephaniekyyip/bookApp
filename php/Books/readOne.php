<?php

// ----------------------------------------------------------------------------
// readOne.php
//
// Get values of selected entry from database.
// This allows the user to see what they have previously entered for that entry
// as they make changes.
// ----------------------------------------------------------------------------
  session_start();

  require_once ('../database.php');
  require_once ('books.php');

  $bookList = new Books($conn);

  // Get selected entry from DB
  if (!empty($_GET['id'])){
    echo $bookList->readOne($_GET['id']);
  }else{
    echo "404";
  }

?>
