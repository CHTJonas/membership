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

          <div class="alert alert-primary" role="alert">
            <h3 class="mb-3">Joining &amp; Leaving</h3>
            <a class="btn btn-primary" href="addmem.php" role="button">Add Member</a>
            <a class="btn btn-primary" href="delmem.php" role="button">Delete Member</a>
          </div>
          <div class="alert alert-primary" role="alert">
            <h3 class="mb-3">Membership Enforcement</h3>
            <form class="form-inline" role="form" action="checkshow.php" method="GET">
              <div class="form-group mx-sm-3 mb-2">
                <label for="showSlug" class="sr-only">Show Camdram Slug</label>
                <input type="text" class="form-control" name="showSlug" id="showSlug" placeholder="Show Camdram Slug">
              </div>
              <button type="submit" class="btn btn-primary mb-2">Check Show Membership</button>
            </form>
            <form class="form-inline" role="form" action="checkindividual.php" method="GET">
              <div class="form-group mx-sm-3 mb-2">
                <label for="lastName" class="sr-only">Person's last name</label>
                <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Person's last name">
              </div>
              <button type="submit" class="btn btn-primary mb-2">Check Individual Membership</button>
            </form>
          </div>
          <div class="alert alert-primary" role="alert">
            <h3 class="mb-3">Elections &amp; Voting</h3>
            <a class="btn btn-primary" href="ballotlist.php" role="button">Create Ballot List</a>
          </div>
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
