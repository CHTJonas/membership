<?php

require_once "Database.php";

class History {

  public static function log($user, $action) {
    $conn = Database::getInstance()->getConn();
    $queryString = "INSERT INTO history (datetime, member_id, action)
              VALUES (CURRENT_TIMESTAMP, $user, '$action')";
    $result = $conn->query($queryString);
    return $result;
  }
}
?>
