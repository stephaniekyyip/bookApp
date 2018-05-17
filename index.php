  <?php

  require_once ("php/functions.php");

    function resetInput(){
    //reset values
    if (isset($_POST["cancel"])){
      $title = $author = $forClass = $reread = $yearRead = $yearPub = $numPgs = "";
    }
   }

  function readEntries(){

    $conn = connectToDatabase();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sortMenu"])){
      // ADD different display views
      if($_POST["sortMenu"] == 'title'){
        $mysql = "SELECT * FROM book_list ORDER BY title";
      }else if ($_POST['sortMenu'] == 'author'){
        $mysql = "SELECT * FROM book_list ORDER BY author";
      }else if ($_POST['sortMenu'] == 'yearRead'){
        $mysql = "SELECT * FROM book_list ORDER BY year_read";
      }else if ($_POST['sortMenu'] == 'yearPub'){
        $mysql = "SELECT * FROM book_list ORDER BY year_pub";
      }else if ($_POST['sortMenu'] == 'numPgs'){
        $mysql = "SELECT * FROM book_list ORDER BY num_pgs";
      }else if ($_POST['sortMenu'] == 'forClass'){
        $mysql = "SELECT * FROM book_list ORDER BY for_class";
      }else if ($_POST['sortMenu'] == 'reread'){
        $mysql = "SELECT * FROM book_list ORDER BY reread";
      }else{
        $mysql = "SELECT * FROM book_list";
      }
    }else{
      $mysql = "SELECT * FROM book_list ORDER BY id DESC";
    }

    $displayData = $conn->query($mysql);

    // Close mySQL connection
    $conn->close();

    return $displayData;
  }

   function printData(){
     $displayData = readEntries();

     if ($displayData->num_rows > 0){

       while($row = $displayData->fetch_assoc()){
         echo "<div class = 'year'> Read in " . $row["year_read"] .
         "<span class = 'updateIcons'><i class='fas fa-edit' value = ' "
         . $row["id"] . " '></i>
         <i class='fas fa-trash-alt' value = ' " . $row["id"] . " '></i>
         </span></div>
         <div class = 'titleAuthor'>" .
         $row["title"] . " by "  . $row["author"] . "</div>";

         echo "<div class = 'bookInfo'>";

         if ($row["year_pub"] != ""){
           echo "Published in " . $row["year_pub"] . "<br>";
         }

         if ($row["num_pgs"] != ""){
          echo $row["num_pgs"] . " pages <br>" ;
         }

         if ($row["for_class"] != ""){
           if($row["for_class"] == 1){
             echo "Read for class <i class='fas fa-check'></i><br>" ;
           }else{
             echo "Read for class <i class='fas fa-times'></i><br>" ;
           }
         }

         if ($row["reread"] != ""){
           if($row["reread"] == 1){
             echo "Reread <i class='fas fa-check'></i>";
           }else{
             echo "Reread <i class='fas fa-times'></i>";
           }
         }

         echo "</div><div class = 'line'></div>";

       } //end while

     }else{
       echo "No books added <i class='far fa-frown'></i>";
     }

   }

 function getUpdateEntry($conn){
  //echo "inside the get update entry func";
  if (!empty($_POST['id'])){
    $mysql = "";
    $mysql .= "SELECT * from book_list WHERE ID = '";
    $mysql .= $_POST['id'];
    $mysql .= "'";

    //echo $mysql;

    $displayData = $conn->query($mysql);

    if ($displayData->num_rows > 0){

      while($row = $displayData->fetch_assoc()){

        $jsonData = array($row["title"], $row["author"],
        $row["year_read"]);

        if($row["year_pub"] != ""){
          array_push($jsonData, $row["year_pub"]);
        }

        if($row["num_pgs"] != ""){
          array_push($jsonData, $row["num_pgs"]);
        }

        if($row["for_class"] != ""){
          array_push($jsonData, $row["for_class"]);
        }

        if($row["reread"] != ""){
          array_push($jsonData, $row["reread"]);
        }
      }

      print_r($jsonData);

      echo json_encode($jsonData);

    }

  }

  // Close mySQL connection
  $conn->close();

}

   function updateEntry($conn){
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateSubmit"])){
      $validateInput();

      // if there's no input errors
      if(!$inputError){
        $mysql = "";
        //UPDATE MyGuests SET lastname='Doe' WHERE id=2
        $mysql .= "UPDATE book_list SET";
        $mysql .= "(title, author, year_read";

        if($yearPub != ""){
          $mysql .= ", year_pub";
        }

        if($numPgs != ""){
          $mysql .= ", num_pgs";
        }

        if($forClass != ""){
          $mysql .= ", for_class";
        }

        if ($reread != ""){
          $mysql .= ", reread";
        }

        $mysql .= ") VALUES ('$title', '$author', '$yearRead'";

        if($yearPub != ""){
          $mysql .= ", '$yearPub'";
        }

        if($numPgs != ""){
          $mysql .= ", '$numPgs'";
        }

        if($forClass != ""){
          if($forClass == "yes"){
            $mysql .= ", 1";
          }else{
            $mysql .= ", 0";
          }
        }

        if($reread != ""){
          if($reread  == "yes"){
            $mysql .= ", 1";
          }else{
            $mysql .= ", 0";
          }
        }

        $mysql .= ")";

      }

    }

  }

    // Displays HTML for the index page
    require_once ("index.view.php");

  ?>
