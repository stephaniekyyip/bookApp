<?php

/*
  Displays all entries in the database according to the selected sorting
  option (either user-selected or by default- sorted by most recently added).
*/

  require_once ( 'functions.php' );

  function readEntries(){

    $conn = connectToDatabase();

    if (isset($_GET["sortMenu"]) && isset($_GET["order"])){

      // Sorting options
      if($_GET["sortMenu"] == 'title'){
        $mysql = "SELECT * FROM book_list ORDER BY title";
      }else if ($_GET['sortMenu'] == 'author'){
        $mysql = "SELECT * FROM book_list ORDER BY author_last";
      }else if ($_GET['sortMenu'] == 'yearRead'){
        $mysql = "SELECT * FROM book_list ORDER BY year_read";
      }else if ($_GET['sortMenu'] == 'yearPub'){
        $mysql = "SELECT * FROM book_list ORDER BY";
      }else if ($_GET['sortMenu'] == 'numPgs'){
        $mysql = "SELECT * FROM book_list ORDER BY";
      }else if($_GET['sortMenu'] == 'forClass'){
        $mysql = "SELECT * FROM book_list WHERE for_class = ";
      }else if ($_GET['sortMenu'] == 'reread'){
        $mysql = "SELECT * FROM book_list WHERE reread =  ";
      }else{
        $mysql = "SELECT * FROM book_list ORDER BY id";
      }

      // Sort descending order or  default
      if($_GET["order"] == "descend" || $_GET["sortMenu"] == "none"){

        if($_GET['sortMenu'] == 'yearPub'){
          $mysql .= " year_pub DESC";
        }else if ($_GET['sortMenu'] == 'numPgs'){
          $mysql .= " num_pgs DESC";
        }else{
          $mysql .= " DESC";
        }

      }else if ($_GET["order"] == "ascend"){ // Sort ascending order

        if($_GET['sortMenu'] == 'yearPub'){
          $mysql .= " -year_pub DESC";
        }else if ($_GET['sortMenu'] == 'numPgs'){
          $mysql .= " -num_pgs DESC";
        }else{
          $mysql .= " ASC";
        }

      }else if($_GET["order"] == "yes"){ // Sorting "yes": for_class & reread
        $mysql .= "1";
      }else if ($_GET["order"] == "no"){ // Sorting "no": for_class & reread
        $mysql .= "0";
      }

      $displayData = $conn->query($mysql);

      // Close mySQL connection
      $conn->close();
    }else{
      $displayData = "404";
    }

  return $displayData;

  }

  $data = readEntries();
  printData($data);

?>
