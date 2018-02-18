<?php

require_once "Auth.php";
session_start();

if (isset($_SESSION['authenticated'])) {
   header('Location: /index.php');
} else {
  $crsid = $_SERVER['REMOTE_USER'];
  $authEngine = Auth::getInstance();
  if ($authEngine->raven($crsid)) {
      $_SESSION['authenticated'] = true;
      $_SESSION['id'] = null;
      header('Location: /');
      return;
  } else {
      echo "User " . $crsid . " not recognised as a member in the database.";
  }
}

?>
