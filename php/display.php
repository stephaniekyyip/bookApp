<?php

  require_once ( 'functions.php' );

  function readEntries(){

    $conn = connectToDatabase();

    if (isset($_POST["sortMenu"]) && isset($_POST["order"])){
    // ADD different display views

      if($_POST["sortMenu"] == 'title'){
        $mysql = "SELECT * FROM book_list ORDER BY title";
      }else if ($_POST['sortMenu'] == 'author'){
        $mysql = "SELECT * FROM book_list ORDER BY author";
      }else if ($_POST['sortMenu'] == 'yearRead'){
        $mysql = "SELECT * FROM book_list ORDER BY year_read";
      }else if ($_POST['sortMenu'] == 'yearPub'){
        $mysql = "SELECT * FROM book_list ORDER BY";
      }else if ($_POST['sortMenu'] == 'numPgs'){
        $mysql = "SELECT * FROM book_list ORDER BY";
      }else if($_POST['sortMenu'] == 'forClass'){
        $mysql = "SELECT * FROM book_list WHERE for_class = ";
      }else if ($_POST['sortMenu'] == 'reread'){
        $mysql = "SELECT * FROM book_list WHERE reread =  ";
      }else{
        $mysql = "SELECT * FROM book_list ORDER BY id";
      }

      // Sort descending order/ default
      if($_POST["order"] == "descend" || $_POST["sortMenu"] == "none"){

        if($_POST['sortMenu'] == 'yearPub'){
          $mysql .= " year_pub DESC";
        }else if ($_POST['sortMenu'] == 'numPgs'){
          $mysql .= " num_pgs DESC";
        }else{
          $mysql .= " DESC";
        }

      }else if ($_POST["order"] == "ascend"){ // Sort ascending order

        if($_POST['sortMenu'] == 'yearPub'){
          $mysql .= " -year_pub DESC";
        }else if ($_POST['sortMenu'] == 'numPgs'){
          $mysql .= " -num_pgs DESC";
        }else{
          $mysql .= " ASC";
        }

      }else if($_POST["order"] == "yes"){ // Sorting "yes": for_class & reread
        $mysql .= "1";
      }else if ($_POST["order"] == "no"){ // Sorting "no": for_class & reread
        $mysql .= "0";
      }

      // Ascending w/ nulls last: SELECT * FROM book_list ORDER BY -year_pub DESC
      // Descending w/ nulls last: SELECT * FROM book_list ORDER BY year_pub DESC

      $displayData = $conn->query($mysql);

      // Close mySQL connection
      $conn->close();
    }

  return $displayData;

  }

  function printData(){

    $displayData = readEntries();

    if ($displayData->num_rows > 0){
      while($row = $displayData->fetch_assoc()){
        echo "<div class = \"year\"> Read in " . $row["year_read"] .
        "<span class = \"updateIcons\"><i class=\"fas fa-edit\" value = \" "
        . $row["id"] . " \"></i>
        <i class=\"fas fa-trash-alt\" value = \" " . $row["id"] . " \"></i>
        </span></div>
        <div class = \"titleAuthor\">" .
        $row["title"] . " by "  . $row["author"] . "</div>";

        echo "<div class = \"bookInfo\">";

        if ($row["year_pub"] != ""){
          echo "Published in " . $row["year_pub"] . "<br>";
        }

        if ($row["num_pgs"] != ""){
         echo $row["num_pgs"] . " pages <br>" ;
        }

        if ($row["for_class"] != ""){
          if($row["for_class"] == 1){
            echo "Read for class <i class=\"fas fa-check\"></i><br>" ;
          }else{
            echo "Read for class <i class=\"fas fa-times\"></i><br>" ;
          }
        }

        if ($row["reread"] != ""){
          if($row["reread"] == 1){
            echo "Reread <i class=\"fas fa-check\"></i>";
          }else{
            echo "Reread <i class=\"fas fa-times\"></i>";
          }
        }

        echo "</div><div class = \"line\"></div>";

      } //end while

    }else{
      echo "No books added <i class=\"far fa-frown\"></i>";
    }

  }

  printData();


?>
