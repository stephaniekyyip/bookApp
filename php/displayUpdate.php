<?php

  /*
    Displays the saved data of selected entry.
    This allows the user to see what they have previously entered for that entry
    as they make changes.
  */

  require_once ( 'functions.php' );

  //mysqli connection
  $conn = connectToDatabase();

  if (!empty($_GET['id'])){

    // Query for selected entry using ID
    $mysql = "SELECT * from book_list WHERE ID = '";
    $mysql .= $_GET['id'];
    $mysql .= "'";

    //echo $mysql;
    $displayData = $conn->query($mysql);

    if ($displayData->num_rows > 0){

      // Format selected entry as JSON
      while($row = $displayData->fetch_assoc()){
        $jsonData[] = array('title' => $row["title"],
        'authorFirst' => $row["author_first"], 'authorLast' => $row["author_last"],
        'yearRead' => $row["year_read"], 'yearPub' => $row["year_pub"],
        'numPgs' => $row["num_pgs"], 'forClass' => $row["for_class"],
        'reread' => $row["reread"]);
      }

      echo json_encode($jsonData);

    }

  }else{
    echo "404";
  }

  // Close mySQL connection
  $conn->close();

?>
