<?php

  require_once ('functions.php' );

  if(!empty($_POST['id'])){
    $conn = connectToDatabase();

    $mysql = "DELETE FROM book_list WHERE ID = '";
    $mysql .= $_POST['id'];
    $mysql .= "'";

    if($conn->query($mysql)){
      echo "Success";
    }else{
      echo "Failed";
    }


    //Close mySQL connection
    $conn->close();
  }else{
    echo "Failed";
  }

?>
