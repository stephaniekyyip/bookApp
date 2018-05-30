<?php

// ----------------------------------------------------------------------------
// database.php
//
// Class declaration: Database
// Creates the connection to the mySQL database.
// ----------------------------------------------------------------------------

  class Database{
    private $servername = "localhost";
    private $username = "root";
    private $db_name  = "bookApp";
    private $password = "";
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
