<?php

require_once "Enum.php";

class MembershipType extends Enum {

  const Ordinary = 1;
  const Associate = 2;
  const Special = 3;
  const Honorary = 4;
  const Unknown = 5;

  public static function printHTML($numSelected) {
    for ($x = 1; $x <= 5; $x++) {
      echo "                  ";
      if ($x == $numSelected) {
        echo "<option selected=\"selected\">";
      } else {
        echo "<option>";
      }
      echo Self::toString($x);
      echo "</option>";
      echo "\n";
    }
  }

}

?>
