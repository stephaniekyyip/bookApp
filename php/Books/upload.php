<?php

// ----------------------------------------------------------------------------
// upload.php
//
// Creates multiple new entries in the database using a .csv file uploaded
// by the user.
// ----------------------------------------------------------------------------
  session_start();
  require_once ('../database.php');
  require_once ('books.php');

  $bookList = new Books($conn);

  if ($_FILES['fileUpload']){
    $file= $_FILES['fileUpload']['name'];
    $temp = $_FILES['fileUpload']['tmp_name']; //temp location for uploaded file

    echo $bookList->upload($file, $temp);

  }else{
    echo "noFile";
  }

?>
