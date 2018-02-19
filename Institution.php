<?php

require_once "Enum.php";

class Institution extends Enum {

  const ADC = 1;
  const ARU = 2;
  const Christs = 3;
  const Churchill = 4;
  const Clare = 5;
  const ClareHall = 6;
  const Corpus = 7;
  const Darwin = 8;
  const Downing = 9;
  const Emmanuel = 10;
  const Fitzwilliam = 11;
  const Girton = 12;
  const Caius = 13;
  const Homerton = 14;
  const Hughes = 15;
  const Jesus = 16;
  const Kings = 17;
  const Lucy = 18;
  const Magdalene = 19;
  const Medwards = 20;
  const Newnham = 21;
  const Pembroke = 22;
  const Peterhouse = 23;
  const Queens = 24;
  const Robinson = 25;
  const StCatharines = 26;
  const StEdmunds = 27;
  const StJohns = 28;
  const Selwyn = 29;
  const Sidney = 30;
  const Trinity = 31;
  const TitHall = 32;
  const Wolfson = 33;
  const University = 34;

  public static function printHTML($numSelected) {
    for ($x = 1; $x <= 34; $x++) {
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
