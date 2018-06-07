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
} elseif (!empty($_POST["othernames"]) && !empty($_POST["lastname"]) && !empty($_POST["primemail"]) &&
          !empty($_POST["institution"]) && !empty($_POST["memtype"]) && !empty($_POST["gradyear"])) {
  $conn = Database::getInstance()->getConn();
  $stmt = $conn->prepare('SELECT institution_id FROM institutions WHERE institution_name LIKE ?');
  $query = "%" . $_POST["institution"] . "%";
  $stmt->bind_param('s', $query);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $institution = $row['institution_id'];

  $stmt = $conn->prepare('SELECT membership_id FROM membership WHERE membership_name LIKE ?');
  $query = "%" . $_POST["memtype"] . "%";
  $stmt->bind_param('s', $query);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $membershiptype = $row['membership_id'];

  $stmt = $conn->prepare('INSERT INTO members (camdram_id,
                                               last_name, other_names, primary_email,
                                               secondary_email, institution_id, graduation_year,
                                               membership_id, expiry)
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
  $stmt->bind_param('sssssssss', $_POST["camid"], $_POST["lastname"], $_POST["othernames"],
                                 $_POST["primemail"], $_POST["secemail"], $institution,
                                 $_POST["gradyear"], $membershiptype, $_POST["expiry"]);
  $stmt->execute();
  $stmt->close();
  $conn->close();
  ?>
  <!doctype html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <link rel="shortcut icon" href="favicon.png">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgF$
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
          <div class="alert alert-success" role="alert">
            New member successfully added!
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
} else {
  // Log the event
  $member = Member::memberFromCrsid($_SESSION['crsid']);
  History::log($member->getMemberId(), "Access to add member page granted.");
  // Create the admin UI
  ?>
  <!doctype html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <link rel="shortcut icon" href="favicon.png">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
          <h1 class="mt-3 mb-5">Add member</h1>
          <form role="form" action="addmem.php" method="POST">
            <div class="form-group row">
              <label for="camid" class="col-sm-2 col-form-label">Camdram ID</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="camid" id="camid" placeholder="Camdram ID">
              </div>
            </div>
            <div class="form-group row">
              <label for="othernames" class="col-sm-2 col-form-label">First Name(s)</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="othernames" id="othernames" placeholder="First Name(s)">
              </div>
            </div>
            <div class="form-group row">
              <label for="lastname" class="col-sm-2 col-form-label">Last Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last Name">
              </div>
            </div>
            <div class="form-group row">
              <label for="primemail" class="col-sm-2 col-form-label">Primary Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control" name="primemail" id="primemail" placeholder="Primary Email" aria-describedby="primaryHelpBlock">
                <small id="primaryHelpBlock" class="form-text text-muted">
                  For Cambridge students please use an @cam email address.
                </small>
              </div>
            </div>
            <div class="form-group row">
              <label for="secemail" class="col-sm-2 col-form-label">Secondary Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control" name="secemail" id="secemail" placeholder="Secondary Email" aria-describedby="secondaryHelpBlock">
                <small id="secondaryHelpBlock" class="form-text text-muted">
                  A non-University email address for the alumni mailing list.
                </small>
              </div>
            </div>
            <div class="form-group row">
              <label for="institution" class="col-sm-2 col-form-label">Institution</label>
              <div class="col-sm-10">
                <select class="form-control" name="institution" id="institution">
<?php Institution::printHTML(-1); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="memtype" class="col-sm-2 col-form-label">Membership Type</label>
              <div class="col-sm-10">
                <select class="form-control" name="memtype" id="memtype">
<?php MembershipType::printHTML(1); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="gradyear" class="col-sm-2 col-form-label">Year of Graduation</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="gradyear" id="gradyear" placeholder="Year of Graduation">
              </div>
            </div>
            <div class="form-group row">
              <label for="expiry" class="col-sm-2 col-form-label">Expiry Date</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="expiry" id="expiry" placeholder="Expiry Date" aria-describedby="expiryHelpBlock">
                <small id="expiryHelpBlock" class="form-text text-muted">
                  dd-mm-yyyy
                </small>
              </div>
            </div>
            <div class="form-group row mt-2">
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
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
