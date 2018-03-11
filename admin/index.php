<?php

require_once "../Member.php";
require_once "../History.php";
require_once "../Version.php";
session_start();
session_regenerate_id();

if (!isset($_SESSION['authenticated'])) {
  // Not logged in
  header('Location: /login.php');
} elseif (!isset($_SESSION['crsid'])) {
  // CRSid not set
  header('Location: /index.php');
} elseif (!in_array($_SESSION['crsid'], Environment::admins)) {
  // Not an administrator
  header("HTTP/1.1 401 Unauthorized");
  echo "401 Unauthorized";
  // Log the event
  $member = Member::memberFromCrsid($_SESSION['crsid']);
  History::log($member->getMemberId(), "Access to administration page denied.");
  die();
} else {
  // Log the event
  $member = Member::memberFromCrsid($_SESSION['crsid']);
  History::log($member->getMemberId(), "Access to administration page granted.");
  // Create the admin UI
  ?>
  <!doctype html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <link rel="shortcut icon" href="../favicon.png">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
      <link rel="stylesheet" href="../sticky-footer-navbar.css">
      <title>CUADC MMC - Admin</title>
    </head>
      <body>
        <header>
          <!-- Fixed navbar -->
          <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="https://membership.cuadc.org/">CUADC Membership Management System</a>
            <div class="collapse navbar-collapse" id="navbarCollapse"></div>
          </nav>
        </header>

        <!-- Begin page content -->
        <main role="main" class="container">
          <h1 class="mt-3 mb-5">Membership Administration</h1>
          <div class="alert alert-danger" role="alert">
            <h5>Data Protection</h5>
            <p>Do not leave this web page unattended.</p>
          </div>
          <a class="btn btn-primary" href="checkshow.php" role="button">Check Show Membership</a>
          <a class="btn btn-primary" href="checkindividual.php" role="button">Check Individual Membership</a>
          <a class="btn btn-primary" href="ballotlist.php" role="button">Create Ballot List</a>
        </main>

        <footer class="footer">
          <div class="container">
            <span class="text-muted">CUADC MMS <?php echo Version::getVersion(); ?></span>
          </div>
        </footer>
      </body>
  </html>
<?php
}

?>
