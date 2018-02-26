<?php

require_once "Database.php";

class Institution {

  public static function fromId($institutionId) {
    $queryString = 'SELECT * FROM institutions';
    $conn = Database::getInstance()->getConn();
    $result = $conn->query($queryString);
    while ($row = $result->fetch_assoc()) {
      if ($institutionId == $row['institution_id']) {
        return $row['institution_name'];
      }
    }
    throw new Exception('Institution ID was not recognised as a valid institution.');
  }

  public static function fromName($institutionName) {
    $queryString = 'SELECT * FROM institutions';
    $conn = Database::getInstance()->getConn();
    $result = $conn->query($queryString);
    while ($row = $result->fetch_assoc()) {
      if ($institutionName == $row['institution_name']) {
        return $row['institution_id'];
      }
    }
    throw new Exception('Institution name was not recognised as a valid institution.');
  }

  public static function printHTML($institutionId) {
    $queryString = 'SELECT * FROM institutions';
    $conn = Database::getInstance()->getConn();
    $result = $conn->query($queryString);
    while ($row = $result->fetch_assoc()) {
      echo "                  ";
      if ($row['institution_id'] == $institutionId) {
        echo "<option selected=\"selected\">";
      } else {
        echo "<option>";
      }
      echo $row['institution_name'];
      echo "</option>";
      echo "\n";
    }
  }

}

?>
