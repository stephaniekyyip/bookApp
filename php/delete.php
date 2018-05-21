<?php

  require_once ('functions.php' );

  if(!empty($_POST['id'])){
    $conn = connectToDatabase();

    $mysql = "DELETE FROM book_list WHERE ID = '";
    $mysql .= $_POST['id'];
    $mysql .= "'";

    if($conn->query($mysql) == TRUE){
      echo "200";
    }else{
      echo "404";
    }

    //Close mySQL connection
    $conn->close();
  }else{
    echo "Failed";
  }

?>
