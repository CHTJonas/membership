<?php

require_once "Database.php";
require_once "MembershipType.php";
require_once "Institution.php";

class Member {
  private $membershipId;
  private $crsid;
  private $lastName;
  private $otherNames;
  private $primaryEmail;
  private $secondaryEmail;
  private $institution;
  private $graduationYear;
  private $membershipType;
  private $expiry;

  private function __construct($membershipId, $crsid, $lastName, $otherNames,
                               $primaryEmail, $secondaryEmail, $institution,
                               $graduationYear, $membershipType, $expiry) {
    $this->membershipId = $membershipId;
    $this->crsid = $crsid;
    $this->lastName = $lastName;
    $this->otherNames = $otherNames;
    $this->primaryEmail = $primaryEmail;
    $this->secondaryEmail = $secondaryEmail;
    $this->institution = $institution;
    $this->graduationYear = $graduationYear;
    $this->membershipType = $membershipType;
    $this->expiry = $expiry;
  }

  public static function memberFromPrimaryEmail($email) {
    $conn = Database::getInstance()->getConn();
    $stmt = $conn->prepare('SELECT * FROM members WHERE primary_email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $member = new Member($row['membership_id'], $row['crsid'], $row['last_name'],
                        $row['other_names'], $row['primary_email'],
                        $row['secondary_email'], $row['institution'],
                        $row['graduation_year'], $row['membership_type'],
                        $row['expiry']);
    return $member;
  }

  public static function memberFromCrsid($crsid) {
    $conn = Database::getInstance()->getConn();
    $stmt = $conn->prepare('SELECT * FROM members WHERE crsid = ?');
    $stmt->bind_param('s', $crsid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $member = new Member($row['membership_id'], $row['crsid'], $row['last_name'],
                        $row['other_names'], $row['primary_email'],
                        $row['secondary_email'], $row['institution'],
                        $row['graduation_year'], $row['membership_type'],
                        $row['expiry']);
    return $member;
  }

  public function flushToDatabase() {
    $conn = Database::getInstance()->getConn();
    $stmt = $conn->prepare('UPDATE members SET crsid = ?,
                                               last_name = ?,
                                               other_names = ?,
                                               primary_email = ?,
                                               secondary_email = ?,
                                               institution = ?,
                                               graduation_year = ?,
                                               membership_type = ?
                                               expiry = ?
                            WHERE membership_id = ?');
    $stmt->bind_param('sssssssss', $this->crsid, $this->lastName,
                                   $this->otherNames, $this->primaryEmail,
                                   $this->secondaryEmail, $this->institution,
                                   $this->graduationYear, $this->membershipType,
                                   $this->expiry);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
  }

  public function getMembershipId() {
    return $this->membershipId;
  }

  public function getCrsid() {
    return $this->crsid;
  }

  public function getLastName() {
    return $this->lastName;
  }

  public function getOtherNames() {
    return $this->otherNames;
  }

  public function getPrimaryEmail() {
    return $this->primaryEmail;
  }

  public function getSecondaryEmail() {
    return $this->secondaryEmail;
  }

  public function getInstitution() {
    return $this->institution;
  }

  public function getGraduationYear() {
    return $this->graduationYear;
  }

  public function getMembershipType() {
    return $this->membershipType;
  }

  public function getExpiry() {
    return $this->expiry;
  }

  public function setMembershipId($membershipId) {
    // Can't change membership id
    return false;
  }

  public function setCrsid($crsid) {
    $this->crsid = $crsid;
    return true;
  }

  public function setLastName($lastName) {
    $this->lastName = $lastName;
    return true;
  }

  public function setOtherNames($otherNames) {
    $this->otherNames = $otherNames;
    return true;
  }

  public function setPrimaryEmail($primaryEmail) {
    $this->primaryEmail = $primaryEmail;
    return true;
  }

  public function setSecondaryEmail($secondaryEmail) {
    $this->secondaryEmail = $secondaryEmail;
    return true;
  }

  public function setInstitution($institution) {
    if (!Institution::isValidName($institution)) {
      return false;
    }
    $constant = Institution::fromString($institution);
    $this->institution = new Institution($constant);
    return true;
  }

  public function setGraduationYear($graduationYear) {
    if (is_numeric($graduationYear)) {
      $this->graduationYear = $graduationYear;
      return true;
    } else {
      return false;
    }
  }

  public function setMembershipType($membershipType) {
    if (!MembershipType::isValidName($membershipType)) {
      return false;
    }
    $type = null;
    switch ($membershipType) {
      case "Ordinary":
        $type = new MembershipType(MembershipType::Ordinary);
        break;
      case "Associate":
        $type = new MembershipType(MembershipType::Associate);
        break;
      case "Special":
        $type = new MembershipType(MembershipType::Special);
        break;
      case "Honorary":
        $type = new MembershipType(MembershipType::Honorary);
      default:
        $type = new MembershipType(MembershipType::Unknown);
    }
    $this->membershipType = $type;
    return true;
  }

  public function setExpiry($expiry) {
    if ($expiry instanceof DateTime) {
      $this->expiry = $expiry;
      return true;
    } else {
      return false;
    }
  }

}
