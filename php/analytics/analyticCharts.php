<?php

// ----------------------------------------------------------------------------
// analyticCharts.php
//
// Gets chart data.
// ----------------------------------------------------------------------------
  session_start();

  require_once ('../database.php');
  require_once ('../Books/books.php');

  $bookList = new Books($conn);

  if(!empty($_GET['chartSelect'])){
    echo $bookList->readAnalyticCharts($_GET['chartSelect']);
  }else{
    echo "404";
  }

 ?>
