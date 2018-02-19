<?php

require_once "Enum.php";

class MembershipType extends Enum {

  const Ordinary = 1;
  const Associate = 2;
  const Special = 3;
  const Honorary = 4;
  const Unknown = 5;

}

?>
