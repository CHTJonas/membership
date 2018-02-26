<?php

require_once "Database.php";

class MembershipType {

  public static function fromId($membershipId) {
    $queryString = 'SELECT * FROM membership';
    $conn = Database::getInstance()->getConn();
    $result = $conn->query($queryString);
    while ($row = $result->fetch_assoc()) {
      if ($membershipId == $row['membership_id']) {
        return $row['membership_name'];
      }
    }
    throw new Exception('Membership ID was not a recognised as a valid type of membership.');
  }

  public static function fromName($membershipName) {
    $queryString = 'SELECT * FROM membership';
    $conn = Database::getInstance()->getConn();
    $result = $conn->query($queryString);
    while ($row = $result->fetch_assoc()) {
      if ($membershipName == $row['membership_name']) {
        return $row['membership_id'];
      }
    }
    throw new Exception('Membership name was not a recognised as a valid type of membership.');
  }

  public static function printHTML($membershipId) {
    $queryString = 'SELECT * FROM membership';
    $conn = Database::getInstance()->getConn();
    $result = $conn->query($queryString);
    while ($row = $result->fetch_assoc()) {
      echo "                  ";
      if ($row['membership_id'] == $membershipId) {
        echo "<option selected=\"selected\">";
      } else {
        echo "<option>";
      }
      echo $row['membership_name'];
      echo "</option>";
      echo "\n";
    }
  }

}

?>
