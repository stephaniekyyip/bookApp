<?php

  require_once ('functions.php' );

  $conn = connectToDatabase();

  $inputList = array("title", "author_first" ,"author_last" , "year_read" ,
    "year_pub", "num_pgs", "for_class", "reread" );

  if(!empty($_GET['query'])){

    $mysql = "SELECT * FROM book_list WHERE ";
    $or = FALSE; //determines whether to add another OR to the statement

    foreach($inputList as $input){
      if($or){
        $mysql .= " OR ";
      }

      $mysql .= $input . " LIKE '%" . test_input($_GET['query']) . "%'";

      $or = TRUE;
    }

    $mysql .= " OR CONCAT( author_first, ' ', author_last ) LIKE '%" .
      test_input($_GET['query']) . "%'";

    $results = $conn->query($mysql);

    echo "<div class = 'searchResult'>Search results for " .
      test_input($_GET['query']) . "</div>";

    printData($results);

  }else{
    echo "404";
  }

  //Close mySQL connection
  $conn->close();

 ?>
