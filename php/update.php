<?php

/*require_once ( '../index.php' );

$myList->connectToDatabase();
$myList->getUpdateEntry();*/

$servername = "localhost";
$username = "root";
$password = "";
$db_name  = "bookApp";
$conn = new mysqli($servername, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!empty($_POST['id'])){
  $mysql = "";
  $mysql .= "SELECT * from book_list WHERE ID = '";
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

}

?>
