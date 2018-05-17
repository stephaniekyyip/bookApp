<?php

  $title = $author = $forClass = $reread = $yearRead = $yearPub = $numPgs = "";

  $titleErr = $authorErr = $forClassErr = $rereadErr = $yearReadErr = $yearPubErr = $numPgsErr = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty($_POST["title"])){
      $titleErr = "Title is missing!";
    }else{
      $title = $_POST["title"];
      $titleErr = "";
    }

    if(empty($_POST["author"])){
      $authorErr = "Author is missing!";
    }else{
      $author = $_POST["author"];
      $authorErr = "";
    }

    if(empty($_POST["yearRead"])){
      $yearReadErr = "Year Read is missing!";
    }else if (!is_numeric($_POST["yearRead"])){
      $yearReadErr = "Invalid Year Read value! Please enter a number.";
    }else{
      $yearRead = $_POST["yearRead"];
      $yearReadErr = "";
    }

    if(!isset($_POST["forClass"])){
      $forClassErr = "Invalid For Class value! Nothing is selected.";
    }else{
      $forClass = $_POST["forClass"];
      $forClassErr = "";
    }

    if(!isset($_POST["reread"])){
      $rereadErr = "Invalid Reread value! Nothing is selected.";
    }else{
      $reread = $_POST["reread"];
      $rereadErr  = "";
    }

    if(!empty($_POST["yearPub"]) && !is_numeric($_POST["yearPub"])){
      $yearPubErr = "Invalid Year Published value! Please enter a number.";
    }else if (!empty($_POST["yearPub"])){
      $yearPub = $_POST["yearPub"];
      $yearPubErr = "";
    }

    if(!empty($_POST["numPgs"]) && !is_numeric($_POST["numPgs"])){
      $numPgsErr = "Invalid Number of Pages value! Please enter a number.";
    }else if (!empty($_POST["numPgs"])){
      $numPgs = $_POST["numPgs"];
      $numPgsErr = "";
    }

  } //end if server request method post



?>
