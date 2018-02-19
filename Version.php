<?php

class Version {
  const MAJOR = 0;
  const MINOR = 2;
  const PATCH = 0;

  public static function getVersion() {
    $commitNumber = trim(exec('git rev-list --count HEAD'));
    $commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));
    $commitDate = new \DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
    $commitDate->setTimezone(new \DateTimeZone('UTC'));
    return sprintf('v%s.%s.%s-dev.%s (%s)', self::MAJOR, self::MINOR, self::PATCH, $commitHash, $commitDate->format('Y-m-d H:m:s'));
  }
}

?>
