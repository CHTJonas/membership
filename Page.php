<?php

require_once "Version.php";

class Page {

  public $title;
  public $heading;
  public $paragraph;

  public function print() {?><!doctype html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <link rel="shortcut icon" href="favicon.png">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
      <link rel="stylesheet" href="sticky-footer-navbar.css">
      <title>CUADC MMS - <?php echo $this->title; ?></title>
    </head>
    <body>
      <header>
        <!-- Fixed navbar -->
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
          <a class="navbar-brand" href="#">CUADC Membership Management System</a>
          <div class="collapse navbar-collapse" id="navbarCollapse">
          </div>
        </nav>
      </header>

      <!-- Begin page content -->
      <main role="main" class="container">
        <div class="mt-3">
          <h1><?php echo $this->heading; ?></h1>
        </div>
        <p class="lead"><?php echo $this->paragraph; ?></p>
      </main>

      <footer class="footer">
        <div class="container">
          <span class="text-muted">CUADC MMS <?php echo Version::getVersion(); ?></span>
        </div>
      </footer>
    </body>
  </html><?php
  }
}
?>
