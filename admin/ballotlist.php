<?php

require_once "../Database.php";
require_once "../Member.php";
require_once "../History.php";
require_once "../MembershipType.php";
require_once "../Institution.php";
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
  History::log($member->getMemberId(), "Access to add member page denied.");
  die();
} else {
  // Log the event
  $member = Member::memberFromCrsid($_SESSION['crsid']);
  History::log($member->getMemberId(), "Access to ballot list granted.");
  // Do that database query
  $conn = Database::getInstance()->getConn();
  $stmt = $conn->prepare('SELECT * FROM members WHERE graduation_year >= 2019');
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  $conn->close();
  // Create the UI
  ?>
  <!doctype html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <link rel="shortcut icon" href="favicon.png">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" 
integrity="sha384-Gn5384xq$
      <link rel="stylesheet" href="../sticky-footer-navbar.css">
      <title>CUADC MMC - Add Member</title>
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
          <h1 class="mt-3 mb-5">Ballot list</h1>
          <p>Use ctrl+F (cmd+F on Mac) to search this page from your browser.</p>
          <p>Names (Graduation Year) - Membership Expiry Date</p>
          <p>
          <?php while ($row = $result->fetch_assoc()) {
              printf("%s %s (%s) - %s <br /> \n", $row["other_names"], $row["last_name"], $row["graduation_year"], 
$row["expiry"]);
          } ?>
          </p>
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
