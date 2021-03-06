<?php

// ----------------------------------------------------------------------------
// readAll.php
//
// Gets all entries in the database according to the selected sorting
// option (either user-selected or by default- sorted by most recently added).
// ----------------------------------------------------------------------------
  session_start();

  require_once ('../database.php');
  require_once ('books.php');

  $bookList = new Books($conn);

  // Read and sort entries from DB according to user sorting choice
  if (!empty($_GET["sortMenu"]) && !empty($_GET["order"]) && isset($_SESSION['user_id'])){
    echo $bookList->readAll($_GET["sortMenu"], $_GET["order"]);
  }else{
    echo "404";
  }

?>
