<?php

// ----------------------------------------------------------------------------
// users.php
//
// Class Declaration: users
// Handles users accounts in the database.
// ----------------------------------------------------------------------------

  class Users{
    private $usersTable = "users";
    private $conn; //Connection to Database

    public $email;
    public $name;
    private $pwd;

    public $errList; // Holds any input errors in creating a new user

    // ------------------------------------------------------------------------
    // Constructor- Gets connection to mySQL DB.
    // ------------------------------------------------------------------------
    public function __construct($db){
      $this->conn = $db;
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
    // validateInput(): Checks user input for errors and if an existing user
    // already exists.
    // ------------------------------------------------------------------------
    public function validateInput(){

      if(empty($_POST["signUpEmail"])){
        $this->errList[] = "Missing e-mail.";
      }else{
        if(!filter_var($_POST["signUpEmail"], FILTER_VALIDATE_EMAIL)){
          $this->errList[] = "Invalid e-mail.";
        }else{
          $tempEmail = $_POST['signUpEmail'];
          //Check for an existing user with the same e-mail
          $mysql = "SELECT * FROM $this->usersTable WHERE email =
          '$tempEmail'";

          $result = $this->conn->query($mysql);

          // if there are any results, there is an existing user
          if($result->num_rows > 0){
            $this->errList[] = "This e-mail has already been used.";
          }else{
            $this->email = $this->test_input($_POST["signUpEmail"]);
          }
        }
      }

      if(empty($_POST["signUpName"])){
        $this->errList[] = "Missing name";
      }else{
        if(!preg_match("/^[a-zA-Z ]*$/", $_POST["signUpName"])){
          $this->errList[] = "Invalid characters in name.";
        }else{
          $this->name = $this->test_input($_POST["signUpName"]);
        }
      }

      if(empty($_POST["signUpPwd"])){
        $this->errList[] = "Missing password.";
      }else{
        $this->pwd = $this->test_input($_POST["signUpPwd"]);
      }

      if(!empty($this->errList)){
        return TRUE;
      }else{
        return FALSE;
      }

    }

    // ------------------------------------------------------------------------
    // create(): Create a new user in DB.
    // ------------------------------------------------------------------------
    public function create(){

      // Hash password
      $hashedPwd = password_hash($this->pwd, PASSWORD_DEFAULT);

      $mysql = "INSERT INTO $this->usersTable (email, name, pwd) VALUES ('$this->email',
      '$this->name', '$hashedPwd')";

      if($this->conn->query($mysql) == FALSE){
        return $mysql . " " . mysqli_error($this->conn);
      }else{
        return "200";
      }

    }

    // ------------------------------------------------------------------------
    // verifyLogin(): Verify user login info.
    // ------------------------------------------------------------------------
    public function verifyLogin(){

      if(!empty($_GET["loginEmail"])){
        $this->email = $this->test_input($_GET["loginEmail"]);
      }else{

        $this->errList[] = "missing email";
      }

      if(!empty($_GET["loginPwd"])){
        $this->pwd = $this->test_input($_GET["loginPwd"]);
      }else{
        $this->errList[] = "missing password";
      }

      if(!empty($this->errList)){
        return json_encode($this->errList);
      }else{
        // Check DB for email
        $mysql = "SELECT * FROM $this->usersTable WHERE email = '$this->email'";
        $result = $this->conn->query($mysql);

        // Email found in DB
        if($result->num_rows > 0){
          if($row = $result->fetch_assoc()){

            // Check if pwd matches
            if(password_verify($this->pwd, $row['pwd'])){

              //Login user
              $_SESSION['user_name'] = $row['name'];
              $_SESSION['user_email'] = $row['email'];
              $_SESSION['user_id'] = $row['id'];

            }else{
              echo "error";
              $this->errList[] = "invalid password";
            }
          }
        }else{ //error
          echo "error";
          $this->errList[] = "invalid email";
        }

        if(!empty($this->errList)){
          return json_encode($this->errList);
        }else{
          return "200";
        }
      }

    }

    // ------------------------------------------------------------------------
    // logout(): Ends current session and logs user out.
    // ------------------------------------------------------------------------
    public function logout(){
      session_unset();
      session_destroy();
    }



  } //end class
