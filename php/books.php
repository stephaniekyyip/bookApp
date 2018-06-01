<?php

// ----------------------------------------------------------------------------
// books.php
//
// Class declartion: Books
// Handles CRUD operations to the database.
// ----------------------------------------------------------------------------

  class Books{
    private $tableName = "book_list"; // table in DB
    private $uploadPath = "../uploads/"; //directory to store uploaded files
    private $conn;

    // User input fields
    public $id = "";
    public $title = "";
    public $authorFirst = "";
    public $authorLast = "";
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

    // ------------------------------------------------------------------------
    // Constructor- Gets connection to mySQL DB.
    // ------------------------------------------------------------------------
    public function __construct($db){
      $this->conn = $db;
    }

    // ------------------------------------------------------------------------
    // formatJson($data): Formats DB data in $data into JSON format.
    // ------------------------------------------------------------------------
    private function formatJson($data){
      if($data == "404" ){
        return "404";
      }else if ($data->num_rows > 0){
        while($row = $data->fetch_assoc()){

          $jsonData[] = array( 'id' => $row["id"],'title' => $row["title"],
          'authorFirst' => $row["author_first"],
          'authorLast' => $row["author_last"],'yearRead' => $row["year_read"],
          'yearPub' => $row["year_pub"], 'numPgs' => $row["num_pgs"],
          'forClass' => $row["for_class"],'reread' => $row["reread"]);

        } //end while

        return json_encode($jsonData);

      }else{
        return "none";
      }
    }

    // ------------------------------------------------------------------------
    // readOne($id): Reads one entry from the DB using the id in $id.
    // ------------------------------------------------------------------------
    public function readOne($id){
      // SQL for to get selected entry using ID
      $mysql = "SELECT * from $this->tableName WHERE ID = '";
      $mysql .= $id;
      $mysql .= "'";

      // Query database for selected entry
      $data = $this->conn->query($mysql);

      return $this->formatJson($data);
    }

    // ------------------------------------------------------------------------
    // readAll($sort, $order): Reads all the entries in the DB and sorts them
    // according to the field in $input and in the direction (ascending/
    // descending) according to $order.
    // ------------------------------------------------------------------------
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

      return $this->formatJson($data);
    }

    // ------------------------------------------------------------------------
    //  test_input($data): Sanitizes the user input in $data.
    // ------------------------------------------------------------------------
    private function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      $data = $this->conn->real_escape_string($data);
      return $data;
    }

    // ------------------------------------------------------------------------
    // checkErr(): Checks for any errors, as denoted in the array errList.
    // ------------------------------------------------------------------------
    private function checkErr(){

       //if there are errors in the user input, print errors
      foreach($this->errList as $error){
        if($error != ""){
          $this->printErr[] = $error;
          $this->inputError = TRUE;
        }
      }

      if($this->inputError == TRUE){
        $this->printErr = json_encode($this->printErr);
      }else{
        $this->printErr = "200";
      }

      return $this->inputError;

    }

    // ------------------------------------------------------------------------
    // validateInput(): Validates user input for each field and checks for
    // any errors.
    // ------------------------------------------------------------------------
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

      return $this->checkErr();
    }

    // ------------------------------------------------------------------------
    // create(): Creates a new entry in the DB using user input.
    // ------------------------------------------------------------------------
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

    // ------------------------------------------------------------------------
    // update($updateId): If there are any changes to the entry, updates
    //  selected entry with user input using the entry's id in $updateId.
    // ------------------------------------------------------------------------
    public function update($updateId){
      //Query for selected entry using id
      $mysql = "SELECT * from $this->tableName WHERE ID = '";
      $mysql .= $updateId;
      $mysql .= "'";
      $entryData = $this->conn->query($mysql);

      // Stores list of user inputs
      $inputList = array("title" => $this->title, "author_first" =>
        $this->authorFirst, "author_last" => $this->authorLast,
        "year_read" => $this->yearRead, "year_pub" => $this->yearPub,
        "num_pgs" => $this->numPgs, "for_class" => $this->forClass,
        "reread" => $this->reread);

      // Stores whether the inputs have been changed by the user
      $changeList = array("title" => FALSE, "author_first" => FALSE,
        "author_last" => FALSE, "year_read" => FALSE, "year_pub" => FALSE,
        "num_pgs" => FALSE, "for_class" => FALSE, "reread" => FALSE);


      if ($entryData->num_rows > 0){
        while($row = $entryData->fetch_assoc()){

          // Check for differences between user input and current values in entry
          foreach($inputList as $input => $val){
            if($val != $row[$input]){
              $changeList[$input] = TRUE;
            }
          }

        }
      }

      // Begin SQL update query
      $mysql = "UPDATE $this->tableName SET ";
      $needComma = FALSE; // Determines whether a comma is needed in the SQL query
      $isChanged = FALSE; // Checks if any of the user inputs have been changed

      foreach($changeList as $input => $val){
        // if current input has been changed, add to SQL query
        if($val == TRUE){
          $isChanged = TRUE; //at least one of the user inputs have been changed

          if($needComma == TRUE){
            $mysql .= ", ";
          }

          $mysql .= $input . " = ";

          if($inputList[$input] != ""){
            $mysql .= "'" . $inputList[$input] . "' ";
          }else{
            $mysql .= "NULL ";
          }

          $needComma = TRUE;
        }
      } // end foreach

      // If entry inputs have been changed, update entry in DB
      if($isChanged){
        $mysql .= "WHERE ID = '" . $updateId . "'";

        if($this->conn->query($mysql)== TRUE){
          return "200";
        }else{
          return "404";
        }
      }else{ //else, no changes have been made to entry
        return "204";
      }
    }

    // ------------------------------------------------------------------------
    // delete($deleteId): Deletes entry from database with id of $deleteId
    // ------------------------------------------------------------------------
    public function delete($deleteId){
      $mysql = "DELETE FROM $this->tableName WHERE ID = '";
      $mysql .= $deleteId;
      $mysql .= "'";

      // echo $mysql;
      if($this->conn->query($mysql) == TRUE){
        return "200";
      }else{
        return "404";
      }
    }

    // ------------------------------------------------------------------------
    // search($query): Searches database for user search query in $query
    // ------------------------------------------------------------------------
    public function search($query){
      $inputList = array("title", "author_first" ,"author_last" , "year_read" ,
        "year_pub", "num_pgs", "for_class", "reread" );

        $mysql = "SELECT * FROM $this->tableName WHERE ";
        $or = FALSE; //determines whether to add another OR to the statement

        // Loops through all the fields in DB to search for user query
        foreach($inputList as $input){
          if($or){
            $mysql .= " OR ";
          }
          $mysql .= $input . " LIKE '%" . $this->test_input($query) . "%'";
          $or = TRUE;
        }

        // Concatenates the fields for author first and last name to better
        // search for the author's full name
        $mysql .= " OR CONCAT( author_first, ' ', author_last ) LIKE '%" .
          $this->test_input($query) . "%'";

        // Query DB
        $results = $this->conn->query($mysql);

        return $this->formatJson($results);
    }

    // ------------------------------------------------------------------------
    // uploadValidate($inputList): Validates each line of the user uploaded
    // CSV file, where $inputList stores the inputs.
    // ------------------------------------------------------------------------
    private function uploadValidate(&$inputList, $line, $lineCount, &$uploadErrList){
      // Required inputs
      $requiredList = ["title", "author_first", "author_last", "year_read"];

      // Numeric inputs
      $numList = ["year_read", "year_pub", "num_pgs"];
      $count = 0; // Keeps track of the current line number in the file

      // Check for errors for each input in each line of the file and
      // add user inputs to the array, $inputList.
      foreach($inputList as $input => $val){
        $inputList[$input] = $line[$count];

        // if required input is missing, report error
        if(in_array($input, $requiredList) && $line[$count] == "NULL"){
          // echo "required missing\n";
          $uploadErrList[] = array("lineNum" => $lineCount,
            "errType" => "required", "field" => $input, "value" => $line[$count]);
          $this->inputError = TRUE;
        }

        // if numeric input is not a number, report error
        if(in_array($input, $numList) && !is_numeric($line[$count])
        && $line[$count]!= "NULL"){
          // echo "not a num\n";
          $uploadErrList[] = array("lineNum" => $lineCount,
            "errType" => "number", "field" => $input, "value" => $line[$count]);
          $this->inputError = TRUE;
        }

        // if boolean inputs values are not 'y', 'n', or NULL
        if(($input == "reread" || $input == "for_class")
        && ($line[$count] != 'y' && $line[$count] != 'n'
        && $line[$count] != "NULL")){
          // echo "not a bool\n";
          $uploadErrList[] = array("lineNum" => $lineCount,
            "errType" => "bool", "field" => $input, "value" => $line[$count]);
          $this->inputError = TRUE;
        }

        // check for valid year inputs
        if(($input == "year_read" || $input == "year_pub")
        && ($line[$count] != "NULL" && strlen($line[$count]) != 4)){
          // echo "not a year\n";
          $uploadErrList[] = array("lineNum" => $lineCount,
            "errType" => "year", "field" => $input, "value" => $line[$count]);
          $this->inputError = TRUE;
        }

        $count = $count + 1;
      } // end foreach

    }

    // ------------------------------------------------------------------------
    // upload($fileName, $tmp): Processes user uploaded CSV file with name
    //   $fileName and temp location of $tmp
    // ------------------------------------------------------------------------
    public function upload($fileName, $tmp){
      // Stores list of user inputs
      $inputList = array("title" => $this->title, "author_first" =>
        $this->authorFirst, "author_last" => $this->authorLast,
        "year_read" => $this->yearRead, "year_pub" => $this->yearPub,
        "num_pgs" => $this->numPgs, "for_class" => $this->forClass,
        "reread" => $this->reread);

      //Stores list of errors in file
      $uploadErrList = array();

      $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
      $filePath = $this->uploadPath . $fileName;
      $lineCount = 1;

      // Check if file is the right format
      if($fileType == "csv"){

        //move uploaded file from temp location to specified directory
        if(move_uploaded_file($tmp, $filePath)){
          //read from file
          $readFile = fopen($filePath, 'r');

          // if file can be opened
          if($readFile){
             //read each line from uploaded file
             while($line = fgetcsv($readFile)){
               $this->inputError = FALSE; //reset inputError

               //Validates each value in the current line
               $this->uploadValidate($inputList, $line, $lineCount, $uploadErrList);

               // If no input errors, query DB
               if($this->inputError == FALSE){
                 $needComma = FALSE;
                 $mysql = "INSERT INTO $this->tableName" . "(";

                 // Add name of inputs to sql query
                 foreach($inputList as $input => $val){
                   if($needComma){
                    $mysql .= ",";
                   }

                   $mysql .= $input;
                   $needComma = TRUE;
                 }

                 $mysql .= ") VALUES (";
                 $needComma = FALSE;

                 // Add input values to sql query
                 foreach($inputList as $input => $val){

                   //add comma if needed
                   if($needComma){
                     $mysql .= ", ";
                   }

                   // Change values of forClass and reread to boolean
                   if(($input == "for_class" || $input == "reread") &&
                   $val != "NULL"){
                     if($val == 'y'){
                       $mysql .= "'1'";
                     }else if($val == 'n'){
                       $mysql .= "'0'";
                     }
                   }else if ($val != "NULL"){ // if input val is not null
                     $mysql .= "'" . $val . "'";
                   }else{ //val is null
                     $mysql .= $val ;
                   }
                   $needComma = TRUE;
                 }

                 $mysql .= ")";

                 // Create new entry in DB
                 if ($this->conn->query($mysql) == FALSE) {
                   return $mysql . " " . mysqli_error($this->conn);
                 }
               }//end if no input errors

              //increment number of lines read so far
              $lineCount = $lineCount + 1;
            } //end while reading from file

            fclose($readFile); //close file

            // Return error list if any errors exist
            if(!empty($uploadErrList)){
              return json_encode($uploadErrList);
            }else{
              return "200";
            }
          } // end if readFile
        }else{
          return "uploadErr";
        }
      }else{
        return "invalidFile";
      }

    }


  } //end class


?>
