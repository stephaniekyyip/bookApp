<?php

// ----------------------------------------------------------------------------
// delete.php
//
// Deletes selected entry in the database.
// ----------------------------------------------------------------------------

  require_once ('../database.php');
  require_once ('books.php');

  //mysql connection
  $database = new Database();
  $conn = $database->connectToDatabase();

  $bookList = new Books($conn);

  if(!empty($_POST['id'])){
    echo $bookList->delete($_POST['id']);
  }else{
    echo "404";
  }

?>
