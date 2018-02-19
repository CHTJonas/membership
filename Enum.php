<?php

abstract class Enum {

  private static $constCacheArray = NULL;

  private static function getConstants() {
    if (self::$constCacheArray == NULL) {
      self::$constCacheArray = [];
    }
    $calledClass = get_called_class();
    if (!array_key_exists($calledClass, self::$constCacheArray)) {
      $reflect = new \ReflectionClass($calledClass);
      self::$constCacheArray[$calledClass] = $reflect->getConstants();
    }
    return self::$constCacheArray[$calledClass];
  }

  public static function isValidName($name) {
    $constants = self::getConstants();
    return array_key_exists($name, $constants);
  }

  public static function isValidValue($value) {
    $values = array_values(self::getConstants());
    return in_array($value, $values, true);
  }

  public static function fromString($name) {
    if (self::isValidName($name)) {
      $constants = self::getConstants();
      return $constants[$name];
    }
    throw new Exception('String argument is not an enumerated member.');
  }

  public static function toString($value) {
    if (self::isValidValue($value)) {
      return array_search($value, self::getConstants());
    }
    throw new Exception('Value argument is not an enumerated member.');
  }
}

?>
