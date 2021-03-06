<?php

require_once "Member.php";
require_once "History.php";
require_once "MembershipType.php";
require_once "Institution.php";
require_once "Version.php";
session_start();
session_regenerate_id();

if (!isset($_SESSION['authenticated'])) {
   header('Location: /login.php');
} else {
  $member = null;
  if (isset($_SESSION['primaryEmail'])) {
    $member = Member::memberFromPrimaryEmail($_SESSION['primaryEmail']);
    History::log($member->getMemberId(), "New login via primary email.");
  } elseif (isset($_SESSION['crsid'])) {
    $member = Member::memberFromCrsid($_SESSION['crsid']);
    History::log($member->getMemberId(), "New login via Raven.");
  } else {
    throw new Exception('Unable to create a new member instance.');
  }

  // Create the member UI
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
      <link rel="stylesheet" href="sticky-footer-navbar.css">
      <title>CUADC MMC - Home</title>
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
          <h1 class="mt-3 mb-5">Manage your membersip</h1>
          <form>
            <div class="form-group row">
              <label for="outputStatus" class="col-sm-2 col-form-label">Membership Status</label>
              <div class="col-sm-10">
<?php
switch (MembershipType::fromId($member->getMembershipId())) {
case "Ordinary": ?>
                <div class="alert alert-success" id="outputStatus">
                  You are an Ordinary Member.
                  You may vote at meetings and elections.
                  You may take part in shows.
                </div>
<?php break;
case "Associate": ?>
                <div class="alert alert-dark" id="outputStatus">
                  You are an Associate Member.
                  You may not vote at meetings and elections.
                  You may not take part in shows.
                </div>
<?php break;
case "Special": ?>
                <div class="alert alert-info" id="outputStatus">
                  You are a Special Member.
                  You may not vote at meetings and elections.
                  You may take part in specific shows, with the approval of the Committee.
                </div>
<?php break;
case "Honorary": ?>
                <div class="alert alert-info" id="outputStatus">
                  You are an Honorary Member.
                  You may not vote at meetings and elections.
                  You may not take part in shows.
                </div>
<?php break;
case "Suspended": ?>
                <div class="alert alert-danger" id="outputStatus">
                  You are suspended from the Club.
                  You have no constitutional rights, other than to appeal your suspension.
                  You may not vote at meetings and elections.
                  You may not take part in shows.
                </div>
<?php break;
case "Banned": ?>
                <div class="alert alert-danger" id="outputStatus">
                  You are banned from the Club.
                  You have no constitutional rights.
                  You may not vote at meetings and elections.
                  You may not take part in shows.
                </div>
<?php break;
} ?>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputMembershipId3" class="col-sm-2 col-form-label">Member Number</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputMembershipId3" placeholder="Member Number" value="<?php echo $member->getMemberId(); ?>" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputCamdramId3" class="col-sm-2 col-form-label">Camdram ID</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputCamdramId3" placeholder="Camdram ID" value="<?php echo $member->getCamdramId(); ?>" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputOtherNames3" class="col-sm-2 col-form-label">First Name(s)</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputOtherNames3" placeholder="First Name(s)" value="<?php echo $member->getOtherNames(); ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputLastName3" class="col-sm-2 col-form-label">Last Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputLastName3" placeholder="Last Name" value="<?php echo $member->getLastName(); ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-2 col-form-label">Primary Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control" id="inputEmail3" placeholder="Primary Email" aria-describedby="primaryHelpBlock" value="<?php echo $member->getPrimaryEmail(); ?>">
                <small id="primaryHelpBlock" class="form-text text-muted">
                  Cambridge students, please use your @cam email address.
                </small>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-2 col-form-label">Secondary Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control" id="inputEmail3" placeholder="Secondary Email" aria-describedby="secondaryHelpBlock" value="<?php echo $member->getSecondaryEmail(); ?>">
                <small id="secondaryHelpBlock" class="form-text text-muted">
                  A non-University email address for our alumni mailing list.
                </small>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPassword3" placeholder="Password" aria-describedby="passwordHelpBlock">
                <small id="passwordHelpBlock" class="form-text text-muted">
                  Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
                </small>
              </div>
            </div>
            <div class="form-group row">
              <label for="institutionSelect1" class="col-sm-2 col-form-label">Institution</label>
              <div class="col-sm-10">
                <select class="form-control" id="institutionSelect1" disabled>
<?php Institution::printHTML($member->getInstitutionId()); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="membershipTypeSelect1" class="col-sm-2 col-form-label">Membership Type</label>
              <div class="col-sm-10">
                <select class="form-control" id="membershipTypeSelect1" disabled>
<?php MembershipType::printHTML($member->getMembershipId()); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputGradYear3" class="col-sm-2 col-form-label">Year of Graduation</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputGradYears3" placeholder="Year of Graduation" value="<?php echo $member->getGraduationYear(); ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputExpiry3" class="col-sm-2 col-form-label">Expiry Date</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputExpiry3" placeholder="Expiry Date" aria-describedby="expiryHelpBlock" value="<?php echo $member->getExpiry(); ?>" disabled>
                <small id="expiryHelpBlock" class="form-text text-muted">
                  dd-mm-yyyy
                </small>
              </div>
            </div>
            <div class="form-group row mt-2">
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Submit</button>
                <small id="submitHelpBlock" class="form-text text-muted my-3">
                  Please email membership@cuadc.org if there is any incorrect information on this page which you cannot change yourself.
                </small>
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
