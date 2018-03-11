<?php

require_once "Curl.php";
require_once "../Version.php";

class Camdram {

  // singleton design pattern
  private static $instance;
  private function __construct() {}
  private function __clone() {}
  private function __sleep() {}
  private function __wakeup() {}

  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new Camdram();
    }
    return self::$instance;
  }

  public function getPeople($slug) {
    // Scrape HTML from the show
    $url = "www.camdram.net/shows/" . $slug;
    $headers = array();
    $headers[] = "X-Application: CUADC MMS " . Version::getVersion();
    $html = Curl::getInstance()->HTTPget($url, $headers);

    // Now parse using a regex, yuk...
    preg_match_all('#/people/(.+?(?=">))#', $html, $peopleURLs, PREG_SET_ORDER);
    return $peopleURLs;
  }

}
