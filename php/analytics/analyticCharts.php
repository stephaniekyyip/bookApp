<?php

// ----------------------------------------------------------------------------
// analyticCharts.php
//
// Gets chart data.
// ----------------------------------------------------------------------------

  require_once ('../database.php');
  require_once ('../Books/books.php');

  //mysql connection
  $database = new Database();
  $conn = $database->connectToDatabase();

  $bookList = new Books($conn);

  if(!empty($_GET['chartSelect'])){
    echo $bookList->readAnalyticCharts($_GET['chartSelect']);
  }else{
    echo "404";
  }

 ?>
