<?php

  /* Displays the saved data of selected entry.
  This allows the user to see what they have previously entered for that entry as
  they make changes.  */

  require_once ( 'functions.php' );

  //mysqli connection
  $conn = connectToDatabase();

  if (!empty($_POST['id'])){

    // Query for selected entry using ID
    $mysql = "SELECT * from book_list WHERE ID = '";
    $mysql .= $_POST['id'];
    $mysql .= "'";

    //echo $mysql;
    $displayData = $conn->query($mysql);

    if ($displayData->num_rows > 0){

      while($row = $displayData->fetch_assoc()){
        $jsonData[] = array('title' => $row["title"], 'author' => $row["author"],
        'yearRead' => $row["year_read"], 'yearPub' => $row["year_pub"],
        'numPgs' => $row["num_pgs"], 'forClass' => $row["for_class"],
        'reread' => $row["reread"]);
      }

      echo json_encode($jsonData);

    }

  } //end if

  // Close mySQL connection
  $conn->close();

?>
