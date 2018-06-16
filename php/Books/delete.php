<?php

// ----------------------------------------------------------------------------
// delete.php
//
// Deletes selected entry in the database.
// ----------------------------------------------------------------------------
  session_start();

  require_once ('../database.php');
  require_once ('books.php');

  $bookList = new Books($conn);

  if(!empty($_POST['id'])){
    echo $bookList->delete($_POST['id']);
  }else{
    echo "404";
  }

?>
