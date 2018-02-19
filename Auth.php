<?php

require_once "Database.php";

class Auth {

  private static $instance;

  private function __construct() {}

  private function __clone() {}

  private function __sleep() {}

  private function __wakeup() {}

  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new Auth;
    }
    return self::$instance;
  }

  public function authenticate($email, $password) {
    $conn = Database::getInstance()->getConn();
    $stmt = $conn->prepare('SELECT * FROM members WHERE primary_email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows != 1) {
      return false;
    }
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
      return true;
    } else {
      return false;
    }
  }

  public function raven($crsid) {
    $conn = Database::getInstance()->getConn();
    $stmt = $conn->prepare('SELECT * FROM members WHERE crsid = ?');
    $stmt->bind_param('s', $crsid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
      return true;
    }
    // That didn't work so try querying primary_email instead
    $email = $crsid . "@cam.ac.uk";
    $stmt = $conn->prepare('SELECT * FROM members WHERE primary_email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows != 1) {
      return false;
    } else {
      return true;
    }
  }

}

?>
