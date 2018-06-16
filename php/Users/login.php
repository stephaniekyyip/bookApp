<?php

// ----------------------------------------------------------------------------
// login.php
//
// Handles user signing into their account.
// ----------------------------------------------------------------------------
  session_start();

  require_once ('../database.php');
  require_once ('users.php');

  $usersList = new Users($conn);

  echo $usersList->verifyLogin();
