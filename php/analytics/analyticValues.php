<?php

// ----------------------------------------------------------------------------
// analyticsValues.php
//
// Gets values for Overall Statistics section.
// ----------------------------------------------------------------------------

  require_once ('../database.php');
  require_once ('../Books/books.php');

  //mysql connection
  $database = new Database();
  $conn = $database->connectToDatabase();

  $bookList = new Books($conn);

  echo $bookList->readAnalyticValues();

 ?>
