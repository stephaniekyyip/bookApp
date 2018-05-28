<?php

/*
  readAll.php

  Gets all entries in the database according to the selected sorting
  option (either user-selected or by default- sorted by most recently added).
*/

  require_once ('database.php');
  require_once ('books.php');

  //mysql connection
  $database = new Database();
  $conn = $database->connectToDatabase();

  $bookList = new Books($conn);

  if (!empty($_GET["sortMenu"]) && !empty($_GET["order"])){

    $bookList->readAll($_GET["sortMenu"], $_GET["order"]);

  }else{
    echo "404";
  }

?>
