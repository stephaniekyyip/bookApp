<?php

// ----------------------------------------------------------------------------
// analyticsValues.php
//
// Gets values for Overall Statistics section.
// ----------------------------------------------------------------------------

  require_once ('database.php');
  require_once ('books.php');

  //mysql connection
  $database = new Database();
  $conn = $database->connectToDatabase();

  $bookList = new Books($conn);

//  if(!empty($_GET['stat'])){
    echo $bookList->readAnalyticValues();
  // }else{
  //   echo "404";
  // }

 ?>
