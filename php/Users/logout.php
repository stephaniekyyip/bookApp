<?php
// ----------------------------------------------------------------------------
// logout.php
//
// Logs user out of account by ending session.
// ----------------------------------------------------------------------------
session_start();

require_once ('../database.php');
require_once ('users.php');

$usersList = new Users($conn);

$usersList->logout();
