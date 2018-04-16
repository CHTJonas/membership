<?php

require_once "Curl.php";
require_once "../Version.php";
require_once "../Member.php";

class Camdram {

  // singleton design pattern
  private static $instance;
  private function __construct() {}
  private function __clone() {}
  private function __sleep() {}
  private function __wakeup() {}

  private $members = array();
  private $nonMembers = array();

  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new Camdram();
    }
    return self::$instance;
  }

  private function getPeople($slug) {
    // Scrape HTML from the show
    $url = "www.camdram.net/shows/" . $slug;
    $headers = array();
    $headers[] = "X-Application: CUADC MMS " . Version::getVersion();
    $html = Curl::getInstance()->HTTPget($url, $headers);

    // Now parse using a regex, yuk...
    preg_match_all('#/people/(.+?(?=">))#', $html, $peopleURLs, PREG_SET_ORDER);
    return $peopleURLs;
  }

  private function getIDs($personURL) {
    $url = "www.camdram.net" . $personURL . ".json";
    $headers = array();
    $headers[] = "X-Application: CUADC MMS " . Version::getVersion();
    $json = Curl::getInstance()->HTTPget($url, $headers);
    $response = Curl::JSONdecode($json);
    return $response["id"];
  }

  private function validatePerson($id, $slug) {
    try {
      $member = Member::memberFromCamdramId($id);
      array_push($members, $member->getOtherNames() . " " . $member->getLastName());
    } catch (Exception $e) {
      array_push($nonMembers, $slug);
    }
  }

  public function print() {
    echo "<div class=\"alert alert-success\" role=\"alert\">";
    foreach ($members as $name) {
      echo $name . "\n";
    }
    echo "</div>";
    echo "<div class=\"alert alert-danger\" role=\"alert\">";
    foreach ($nonMembers as $slug) {
      echo $slug . "\n";
    }
    echo "</div>";
  }

  public static function checkShowMembers($slug) {
    echo '<a class="btn btn-dark" href="';
    echo "www.camdram.net/shows/" . $slug;
    echo '" role="button">View show on Camdram</a>';
    $i = self::$instance;
    $peopleURLs = $i->getPeople($slug);
    foreach ($peopleURLs as $val) {
      $id = $i->getIDs($val[0]);
      $i->validatePerson($id, $val[0]);
    }
    $i->print();
  }

}
