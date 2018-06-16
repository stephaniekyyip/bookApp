<?php

// ----------------------------------------------------------------------------
// analyticsValues.php
//
// Gets values for Overall Statistics section.
// ----------------------------------------------------------------------------
  session_start();

  require_once ('../database.php');
  require_once ('../Books/books.php');

  $bookList = new Books($conn);

  echo $bookList->readAnalyticValues();

 ?>
