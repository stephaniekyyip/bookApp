<?php

// ----------------------------------------------------------------------------
// search.php
//
// Searches for entries in the database that match the user's search query.
// ----------------------------------------------------------------------------
  session_start();
  
  require_once ('../database.php');
  require_once ('books.php');

  //mysql connection
  $database = new Database();
  $conn = $database->connectToDatabase();

  $bookList = new Books($conn);

  if(!empty($_GET['query'])){
    echo $bookList->search($_GET['query']);
  }else{
    echo "404";
  }


 ?>
