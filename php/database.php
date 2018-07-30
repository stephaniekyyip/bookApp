<?php

// ----------------------------------------------------------------------------
// database.php
//
// Creates the connection to the mySQL database.
// ----------------------------------------------------------------------------

  //mySQL DB connection for localhost
  // private $servername = "localhost";
  // private $username = "root";
  // private $db_name  = "bookApp";
  // private $password = "";

  //mySQL DB connection for heroku via ClearDB
  $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
  $servername = $url["host"];
  $username = $url["user"];
  $password = $url["pass"];
  $db_name = substr($url["path"], 1);

  $conn = new mysqli($servername, $username, $password, $db_name);

?>
