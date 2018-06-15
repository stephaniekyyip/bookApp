<?php
// ----------------------------------------------------------------------------
// logout.php
//
// Logs user out of account by ending session.
// ----------------------------------------------------------------------------
session_start();

require_once ('../database.php');
require_once ('users.php');

// mysql connection
// $database = new Database();
// $conn = $database->connectToDatabase();

$usersList = new Users($conn);

$usersList->logout();
