
  <?php

    class bookEntry{

       protected $mysql  = ""; // store sql queries
       protected $inputError = FALSE; //check for errors in user input
       protected $dbAddSuccess = FALSE;
       protected $displayData; //stores requested data from DB
       protected $conn; //mysqli connection

       public $title = "";
       public $author = "";
       public $forClass = "";
       public $reread = "";
       public $yearRead = "";
       public $yearPub = "";
       public $numPgs = "";

       protected $titleErr = "";
       protected $authorErr = "";
       protected $forClassErr = "";
       protected $rereadErr = "";
       protected $yearReadErr = "";
       protected $yearPubErr = "";
       protected $numPgsErr = "";
       protected $errList;

       public function connectToDatabase(){
          $servername = "localhost";
          $username = "root";
          $password = "";
          $db_name  = "bookApp";
          $this->conn = new mysqli($servername, $username, $password, $db_name);

          // Check connection
          if ($this->conn->connect_error) {
              die("Connection failed: " . $this->conn->connect_error);
          }
          // echo "Connected successfully";
      }

       protected function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }

       protected function validateInput(){
         //echo "inside validate input <br>";
        // Validate user input and add error messages when necessary
        if(empty($_POST["title"])){
          $this->titleErr = "Error: Title is missing!";
        }else{
          $this->title = $this->test_input($_POST["title"]);
          $this->titleErr = "";
        }

        if(empty($_POST["author"])){
          $this->authorErr = "Error: Author is missing!";
        }else{
          $this->author = $this->test_input($_POST["author"]);
          $this->authorErr = "";
        }

        if(empty($_POST["yearRead"])){
          $this->yearReadErr = "Year Read is missing!";
        }else if (!is_numeric($_POST["yearRead"])){
          $this->yearReadErr = "Error: Year Read. Please enter a number.";
        }else{
          $this->yearRead = $this->test_input($_POST["yearRead"]);
          $this->yearReadErr = "";
        }

        if(isset($_POST["forClass"])){
          $this->forClass = $_POST["forClass"];
        }

        if(isset($_POST["reread"])){
          $this->reread = $_POST["reread"];
        }

        if(!empty($_POST["yearPub"]) && !is_numeric($_POST["yearPub"])){
          $this->yearPubErr = "Error: Year Published. Please enter a number.";
        }else if (!empty($_POST["yearPub"])){
          $this->yearPub = $this->test_input($_POST["yearPub"]);
          $this->yearPubErr = "";
        }

        if(!empty($_POST["numPgs"]) && !is_numeric($_POST["numPgs"])){
          $this->numPgsErr = "Error: Number of Pages. Please enter a number.";
        }else if (!empty($_POST["numPgs"])){
          $this->numPgs = $this->test_input($_POST["numPgs"]);
          $this->numPgsErr = "";
        }

        // check for errors
        $this->errList = array($this->titleErr, $this->authorErr, $this->yearReadErr, $this->yearPubErr, $this->numPgsErr , $this->forClassErr, $this->rereadErr);
        foreach($this->errList as $printErr){
          if($printErr != ""){
            //echo "<br>error: " . $printErr . "<br>";
            $this->inputError = TRUE;
          }
        }

      }

       public function createEntry(){

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addSubmit"])){

          $this->validateInput();

          // if there are no errors, create new entry in mysql table
          if(!$this->inputError){
            $this->mysql .= "INSERT INTO book_list";
            $this->mysql .= "(title, author, year_read";

            if($this->yearPub != ""){
              $this->mysql .= ", year_pub";
            }

            if($this->numPgs != ""){
              $this->mysql .= ", num_pgs";
            }

            if($this->forClass != ""){
              $this->mysql .= ", for_class";
            }

            if ($this->reread != ""){
              $this->mysql .= ", reread";
            }

            $this->mysql .= ") VALUES ('$this->title', '$this->author', '$this->yearRead'";

            if($this->yearPub != ""){
              $this->mysql .= ", '$this->yearPub'";
            }

            if($this->numPgs != ""){
              $this->mysql .= ", '$this->numPgs'";
            }

            if($this->forClass != ""){
              if($this->forClass == "yes"){
                $this->mysql .= ", 1";
              }else{
                $this->mysql .= ", 0";
              }
            }

            if($this->reread != ""){
              if($this->reread  == "yes"){
                $this->mysql .= ", 1";
              }else{
                $this->mysql .= ", 0";
              }
            }

            $this->mysql .= ")";

            if ($this->conn->query($this->mysql) == TRUE) {
              $this->dbAddSuccess = TRUE;
            } else {
              $this->dbAddSuccess = FALSE;
              echo "<br>failed " . $this->mysql . " " . mysqli_error($this->conn);
            }

            header("Location: index.php");
            exit();

          } // end if no errors

        } //end if POST


      } //end function Create

       public function resetInput(){
        //reset values
        if (isset($_POST["cancel"])){
          $this->title = $this->author = $this->forClass = $this->reread = $this->yearRead = $this->yearPub = $this->numPgs = "";
        }
      }

       public function readEntries(){

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sortMenu"])){
          // ADD different display views
          if($_POST["sortMenu"] == 'title'){
            $this->mysql = "SELECT * FROM book_list ORDER BY title";
          }else if ($_POST['sortMenu'] == 'author'){
            $this->mysql = "SELECT * FROM book_list ORDER BY author";
          }else if ($_POST['sortMenu'] == 'yearRead'){
            $this->mysql = "SELECT * FROM book_list ORDER BY year_read";
          }else if ($_POST['sortMenu'] == 'yearPub'){
            $this->mysql = "SELECT * FROM book_list ORDER BY year_pub";
          }else if ($_POST['sortMenu'] == 'numPgs'){
            $this->mysql = "SELECT * FROM book_list ORDER BY num_pgs";
          }else if ($_POST['sortMenu'] == 'forClass'){
            $this->mysql = "SELECT * FROM book_list ORDER BY for_class";
          }else if ($_POST['sortMenu'] == 'reread'){
            $this->mysql = "SELECT * FROM book_list ORDER BY reread";
          }else{
            $this->mysql = "SELECT * FROM book_list";
          }
        }else{
          $this->mysql = "SELECT * FROM book_list ORDER BY id DESC";
        }

        $this->displayData = $this->conn->query($this->mysql);
      }

       public function printErr(){

      //   if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addSubmit"])){

           // if there are errors in the user input, print errors
           if($this->inputError){
             $this->errList = array($this->titleErr, $this->authorErr, $this->yearReadErr, $this->yearPubErr, $this->numPgsErr , $this->forClassErr, $this->rereadErr);
             echo "<ul>";
             foreach($this->errList as $value){
               if($value != ""){
                  echo "<li>$value</li> <br>";
                }
             }
             echo "</ul>";
             // reset err messages
             $this->titleErr = $this->authorErr = $this->forClassErr = $this->rereadErr = $this->yearReadErr = $this->yearPubErr = $this->numPgsErr = "";
          }

          //if successfully added a new entry to db
           if ($this->dbAddSuccess && !$this->inputError){
             echo "Successfully added " . $this->title ."! <br>";

             $this->dbAddSuccess  = FALSE;
             $this->title = $this->author = $this->forClass = $this->reread = $this->yearRead = $this->yearPub = $this->numPgs = "";
           }

      //  } // end if POST
      }

       public function printData(){
        if ($this->displayData->num_rows > 0){

          while($this->row = $this->displayData->fetch_assoc()){
            echo "<div class = 'year'>" . $this->row["year_read"] .
            "<span class = 'updateIcons'><i class='fas fa-edit'></i>
            <i class='fas fa-trash-alt'></i>
            </div>
            <div class = 'titleAuthor'>" .
            $this->row["title"] . " by "  . $this->row["author"] . "</div>";

            echo "<div class = 'bookInfo'>";

            if ($this->row["year_pub"] != ""){
              echo "Published in " . $this->row["year_pub"] . "<br>";
            }

            if ($this->row["num_pgs"] != ""){
             echo $this->row["num_pgs"] . " pages <br>" ;
            }

            if ($this->row["for_class"] != ""){
              if($this->row["for_class"] == 1){
                echo "Read for class <i class='fas fa-check'></i><br>" ;
              }else{
                echo "Read for class <i class='fas fa-times'></i><br>" ;
              }
            }

            if ($this->row["reread"] != ""){
              if($this->row["reread"] == 1){
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

       public function endDbConnection(){
        $this->conn->close();
      }

    } // end class bookEntry

    $myList = new bookEntry;

    require_once ("index.view.php");

  ?>
