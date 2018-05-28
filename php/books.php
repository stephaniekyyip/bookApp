<?php
/*
  books.php

  Class declartion: Books
  Handles CRUD operations to the database.

*/

  class Books{
    private $tableName = "book_list";
    private $conn;

    // User input fields
    public $id = "";
    public $title = "";
    public $authorFirst = "";
    public $yearRead = "";
    public $yearPub = "";
    public $numPgs = "";
    public $forClass = "";
    public $reread = "";

    // Errors messages for user input
    public $errList = array("titleErr" => "", "authorFirstErr" => "",
    "authorLastErr" => "", "yearReadErr" =>"", "yearPubErr" => "",
    "numPgsErr" => "");
    private $inputError = FALSE; //check for errors in user input
    public $printErr; //stores error messages

    public function __construct($db){
      $this->conn = $db;
    }

    private function formatJson($data){
      if($data == "404" ){
        echo "404";
      }else if ($data->num_rows > 0){
        while($row = $data->fetch_assoc()){

          $jsonData[] = array( 'id' => $row["id"],'title' => $row["title"],
          'authorFirst' => $row["author_first"], 'authorLast' => $row["author_last"],
          'yearRead' => $row["year_read"], 'yearPub' => $row["year_pub"],
          'numPgs' => $row["num_pgs"], 'forClass' => $row["for_class"],
          'reread' => $row["reread"]);

        } //end while

        echo json_encode($jsonData);

      }else{
        echo "none";
      }
    }

    public function readOne($id){
      // SQL for to get selected entry using ID
      $mysql = "SELECT * from $this->tableName WHERE ID = '";
      $mysql .= $id;
      $mysql .= "'";

      // Query database for selected entry
      $data = $this->conn->query($mysql);

      $this->formatJson($data);
    }

    public function readAll($sort, $order){
      // Sorting options
      if($sort == 'title'){
        $mysql = "SELECT * FROM $this->tableName ORDER BY title";
      }else if ($sort == 'author'){
        $mysql = "SELECT * FROM $this->tableName ORDER BY author_last";
      }else if ($sort == 'yearRead'){
        $mysql = "SELECT * FROM $this->tableName ORDER BY year_read";
      }else if ($sort == 'yearPub'){
        $mysql = "SELECT * FROM $this->tableName ORDER BY";
      }else if ($sort == 'numPgs'){
        $mysql = "SELECT * FROM $this->tableName ORDER BY";
      }else if($sort == 'forClass'){
        $mysql = "SELECT * FROM $this->tableName WHERE for_class = ";
      }else if ($sort == 'reread'){
        $mysql = "SELECT * FROM $this->tableName WHERE reread =  ";
      }else{
        $mysql = "SELECT * FROM $this->tableName ORDER BY id";
      }

      // Sort descending order (default)
      if($order == "descend" || $sort == "none"){

        // Move entries to the end of sort if yearPub or numPgs is not defined
        if($sort == 'yearPub'){
          $mysql .= " year_pub DESC";
        }else if ($sort == 'numPgs'){
          $mysql .= " num_pgs DESC";
        }else{
          $mysql .= " DESC";
        }

      }else if ($order == "ascend"){ // Sort ascending order

        // Move entries to the end of sort if yearPub or numPgs is not defined
        if($sort == 'yearPub'){
          $mysql .= " -year_pub DESC";
        }else if ($sort == 'numPgs'){
          $mysql .= " -num_pgs DESC";
        }else{
          $mysql .= " ASC";
        }

      }else if($order == "yes"){ // Sorting "yes": for_class & reread
        $mysql .= "1";
      }else if ($order == "no"){ // Sorting "no": for_class & reread
        $mysql .= "0";
      }

      // query db for sorted entries
      $data = $this->conn->query($mysql);

      $this->formatJson($data);
    }

    private function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      $data = $this->conn->real_escape_string($data);
      return $data;
    }

    private function printErr(){
      // $startErrList = TRUE; // start <ul> if there is an error

       //if there are errors in the user input, print errors
      foreach($this->errList as $error){
        if($error != ""){

          // if($startErrList == TRUE){
          //   $this->printErr .= "<ul>";
          //   $startErrList = FALSE;
          // }
          //
          // $this->printErr .= "<li>$error</li> ";
          $this->inputError = TRUE;
        }
      }

      // if($this->inputError == TRUE){
      //   $this->printErr .= "</ul>";
      // }
      $this->printErr = json_encode($this->errList);
    }

    public function validateInput(){
      // Validate user input and add error messages when necessary
      if(empty($_POST["title"])){
        $this->errList["titleErr"] = "Error: Title is missing!";
      }else{
        $this->title = $this->test_input($_POST["title"]);
      }

      if(empty($_POST["authorFirst"])){
       $this->errList["authorFirstErr"] = "Error: Author first name is missing!";
      }else{
        $this->authorFirst = $this->test_input($_POST["authorFirst"]);
      }

      if(empty($_POST["authorLast"])){
        $this->errList["authorLastErr"] = "Error: Author last name is missing!";
      }else{
        $this->authorLast = $this->test_input($_POST["authorLast"]);
      }

      if(empty($_POST["yearRead"])){
        $this->errList["yearReadErr"] = "Year Read is missing!";
      }else if (!is_numeric($_POST["yearRead"])){
        $this->errList["yearReadErr"] = "Error: Year Read. Please enter a number.";
      }else{
        $this->yearRead = $this->test_input($_POST["yearRead"]);
      }

      if(isset($_POST["forClass"])){
        $this->forClass = $_POST["forClass"];
      }

      if(isset($_POST["reread"])){
        $this->reread= $_POST["reread"];
      }

      if(!empty($_POST["yearPub"]) && !is_numeric($_POST["yearPub"])){
        $this->errList["yearPubErr"] = "Error: Year Published. Please enter a number.";
      }else if (!empty($_POST["yearPub"])){
        $this->yearPub = $this->test_input($_POST["yearPub"]);
      }

      if(!empty($_POST["numPgs"]) && !is_numeric($_POST["numPgs"])){
        $this->errList["numPgsErr"] = "Error: Number of Pages. Please enter a number.";
      }else if (!empty($_POST["numPgs"])){
        $this->numPgs = $this->test_input($_POST["numPgs"]);
      }

      $this->printErr();
    }

    public function create(){
      $mysql = "INSERT INTO $this->tableName";
      $mysql .= "(title, author_first, author_last, year_read";

      if($this->yearPub != ""){
        $mysql .= ", year_pub";
      }

      if($this->numPgs != ""){
        $mysql .= ", num_pgs";
      }

      if($this->forClass != ""){
        $mysql .= ", for_class";
      }

      if ($this->reread != ""){
        $mysql .= ", reread";
      }

      $mysql .= ") VALUES ('$this->title', '$this->authorFirst',
        '$this->authorLast', '$this->yearRead'";

      if($this->yearPub != ""){
        $mysql .= ", '$this->yearPub'";
      }

      if($this->numPgs != ""){
        $mysql .= ", '$this->numPgs'";
      }

      if($this->forClass != ""){
        if($this->forClass == "yes"){
          $mysql .= ", 1";
        }else{
          $mysql .= ", 0";
        }
      }

      if($this->reread != ""){
        if($this->reread  == "yes"){
          $mysql .= ", 1";
        }else{
          $mysql .= ", 0";
        }
      }

      $mysql .= ")";

      if ($this->conn->query($mysql) == FALSE) {
        return $mysql . " " . mysqli_error($this->conn);
      }else{
        return "200";
      }

    }


  } //end class





?>
