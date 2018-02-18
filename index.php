<?php

require_once "Page.php";
session_start();
session_regenerate_id();

if (!isset($_SESSION['authenticated'])) {
   header('Location: /login.php');
} else {
  // We are logged in
  // Make page content
  $page = new Page;
  $page->title = "Test";
  $page->heading = "Test heading";
  $page->paragraph = "This is a test paragraph of text.";
  $page->print();
}

?>
