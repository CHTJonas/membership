<?php

require_once "Environment.php";

class Database {

  private $conn;
  private static $instance;

  private function __construct($host, $user, $pass, $db) {
    $this->conn = new mysqli($host, $user, $pass, $db);
    if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
    }
  }

  private function __clone() {}

  private function __sleep() {}

  private function __wakeup() {}

  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new Database(Environment::db_host,
                                     Environment::db_user,
                                     Environment::db_pass,
                                     Environment::db_name);
    }
    return self::$instance;
  }

  public function getConn() {
    return $this->conn;
  }

}

?>
