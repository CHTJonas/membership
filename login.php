<?php

require_once "Auth.php";
session_start();

if (isset($_SESSION['authenticated'])) {
   header('Location: /index.php');
} else {
   $error = null;
   if (!empty($_POST)) {
       $email = empty($_POST['inputEmail']) ? null : $_POST['inputEmail'];
       $password = empty($_POST['inputPassword']) ? null : $_POST['inputPassword'];
       $authEngine = Auth::getInstance();
       if ($authEngine->authenticate($email, $password)) {
           $_SESSION['authenticated'] = true;
           $_SESSION['primaryEmail'] = $email;
           header('Location: /');
           return;
       } else {
           $error = 'Incorrect username or password.';
       }
   }
   // Create the login form
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
    <link rel="stylesheet" href="login.css">
    <title>CUADC MMC - Login</title>
  </head>
  <body>
    <div class="container">
      <form class="form-signin" role="form" action="login.php" method="POST">
        <img class="mb-4" src="cuadc_logo.png" alt="" width="173" height="200">
        <h2 class="form-signin-heading">Please login</h2>
        <label for="inputUsername" class="sr-only">Primary Email</label>
        <input type="text" name="inputEmail" class="form-control" placeholder="Primary Email" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="inputPassword" class="form-control" placeholder="Password" required>
        <p class="form-signin-warning"><?php echo $error; ?></p>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        <h4 class="text-center my-3">or</h4>
        <a class="btn btn-lg btn-primary btn-block ravenbtn" role="button" href="/ravenlogin.php">
          <div class="ravenicon"></div>
          <div class="raventext">Login with Raven</div>
        </a>
      </form>
    </div>
  </body>
</html>
<?php
}
?>
