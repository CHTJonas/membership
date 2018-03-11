<?php

class Curl {

  // singleton design pattern
  private static $instance;
  private function __construct() {}
  private function __clone() {}
  private function __sleep() {}
  private function __wakeup() {}

  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new Curl();
    }
    return self::$instance;
  }

  public static function JSONdecode($serverOutput) {
    return json_decode($serverOutput, true);
  }

  public static function JSONencode($serverInput) {
    return json_encode($serverInput);
  }

  public function HTTPget($url, $headers) {
    $session = curl_init($url);
    curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($session, CURLOPT_HTTPGET, true);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($session);
    curl_close ($session);
    return $response;
  }

  public function HTTPpost($url, $headers, $data) {
    $session = curl_init($url);
    $post_fields = $this->JSONencode($data);
    curl_setopt($session, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($session, CURLOPT_POST, true);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($session);
    curl_close ($session);
    return $response;
  }
}

?>
