<?php
/*
  Creates new entries in the database using a .csv file uploaded by the user.
*/

  // Stores user inputs
  $title = "";
  $authorFirst = "";
  $authorLast = "";
  $yearRead = "";
  $yearPub = "";
  $numPgs = "";
  $forClass = "";
  $reread = "";

  // Errors messages for user input
  $titleErr = "";
  $authorFirstErr = "";
  $authorLastErr = "";
  $yearReadErr = "";
  $yearPubErr = "";
  $numPgsErr = "";
  $forClassErr = "";
  $rereadErr = "";
  $inputError = FALSE;

  // Stores user inputs
  $inputList = [ "title" => $title, "author_first" => $authorFirst,
  "author_last" => $authorLast,"year_read" => $yearRead,
  "year_pub" => $yearPub, "num_pgs" => $numPgs, "for_class" => $forClass,
  "reread" => $reread];

  // Error messages for user input
  $errList = [ "title" => $titleErr, "author_first" => $authorFirstErr,
  "author_last" => $authorLastErr,"year_read" => $yearReadErr,
  "year_pub" => $yearPubErr, "num_pgs" => $numPgsErr, "for_class" => $forClassErr,
  "reread" => $rereadErr];

  // Required inputs
  $requiredList = ["title", "author_first", "author_last", "year_read"];

  // Numeric inputs
  $numList = ["year_read", "year_pub", "num_pgs"];

  $dir = "../uploads/"; //directory to store uploaded file

  require_once ('functions.php' );

  $conn = connectToDatabase();

  if ($_FILES['fileUpload']){

    $fileName = $_FILES['fileUpload']['name'];
    $tmp = $_FILES['fileUpload']['tmp_name']; //temp location for uploaded file
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $filePath = $dir . $fileName;
    $lineCount = 1;

    if($fileType == "csv"){
      //move uploaded file from temp location to specified directory
      if(move_uploaded_file($tmp, $filePath)){
        //read from file
        $readFile = fopen($filePath, 'r');

         if($readFile){

           //read each line from uploaded file
           while($line = fgetcsv($readFile)){

            // Store input values in the array, inputList
            $count = 0;
            foreach($inputList as $input => $val){
              $inputList[$input] = $line[$count];
              // echo "col: $count \n";
              // echo "inputList: $input => $inputList[$input] \n";

              // if required input is missing, report error
              if(in_array($input, $requiredList) && $line[$count] == "NULL"){
                $errList[$input] = "Line $lineCount : Missing required field - $input. Input: $line[$count]";
                $inputError = TRUE;
              }

              // if numeric input is not a number, report error
              if(in_array($input, $numList) && !is_numeric($line[$count]) && $line[$count]!= "NULL"){
                $errList[$input] = "Line $lineCount : Input must be a number for $input. Input: $line[$count] ";
                $inputError = TRUE;
              }

              // if boolean inputs values are not 'y', 'n', or NULL
              if(($input == "reread" || $input == "for_class") && ($line[$count] != 'y' && $line[$count] != 'n' && $line[$count] != "NULL")){
                $errList[$input] = "Line $lineCount: Input must be either y or n for $input. Input: $line[$count]";
                $inputError = TRUE;
              }

              // check for valid year inputs
              if(($input == "year_read" || $input == "year_pub") && ($line[$count] != "NULL" && strlen($line[$count]) != 4)){
                $errList[$input] = "Line $lineCount : Invalid year for $input. Input: $line[$count]";
                $inputError = TRUE;
              }

              //print errors
              if($inputError){
                foreach($errList as $err){
                  if($err != ""){
                    echo "$err <br>";
                  }
                }
              }

              //reset Errors
              foreach($errList as &$err){
                $err = "";
              }

              $count = $count + 1;

            } // end foreach

            if(!$inputError){ // no input errors
              $needComma = FALSE;
              $mysql = "INSERT INTO book_list" . "(";

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

                if($needComma){ //add comma if needed
                  $mysql .= ", ";
                }

                // Change values of forClass and reread to boolean
                if(($input == "for_class" || $input == "reread") && $val != "NULL"){
                  if($val == 'y'){
                    $mysql .= "'1'";
                  }else if($val == 'n'){
                    $mysql .= "'0'";
                  }
                }else if ($val != "NULL"){ // check if input val is blank
                  $mysql .= "'" . $val . "'";
                }else{ //val is null
                  $mysql .= $val ;
                }
                $needComma = TRUE;
              }

              $mysql .= ")";

              // echo "mysql: $mysql \n";

              if ($conn->query($mysql) == FALSE) {
                echo $mysql . " " . mysqli_error($conn);
              }
            }

            //increment number of lines read so far
            $lineCount = $lineCount + 1;

           } //end while
           fclose($readFile);
           $conn->close();

           if(!$inputError){
             echo "200";
           }

         } // end if readFile
      }else{
        echo "File could not be uploaded. Try again.";
      }
    }else{
      echo "Invalid file type. Try again.";
    }

  }else{
    echo "File not detected. Try again.";
  }

?>
