<?php

// ----------------------------------------------------------------------------
// database.php
//
// Class declaration: Database
// Creates the connection to the mySQL database.
// ----------------------------------------------------------------------------

  class Database{
    //mySQL DB connection for localhost
    // private $servername = "localhost";
    // private $username = "root";
    // private $db_name  = "bookApp";
    // private $password = "";

    //mySQL DB connection for heroku via ClearDB
    private $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
    private $servername = $cleardb_url["host"];
    private $username = $cleardb_url["user"];
    private $password = $cleardb_url["pass"];
    private $db_name = substr($cleardb_url["path"],1);

    public $conn;

    public function connectToDatabase(){
      $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->db_name);

      // Check connection
      if ($this->conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      return $this->conn;
    }

  }

?>
