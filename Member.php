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
    $this->setCrsid($crsid);
    $this->setLastName($lastName);
    $this->setOtherNames($otherNames);
    $this->setPrimaryEmail($primaryEmail);
    $this->setSecondaryEmail($secondaryEmail);
    $this->setInstitution($institution);
    $this->setGraduationYear($graduationYear);
    $this->setMembershipType($membershipType);
    $this->setExpiry($expiry);
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
    throw new Exception('Cannot change a membership ID value.');
  }

  public function setCrsid($crsid) {
    $this->crsid = $crsid;
  }

  public function setLastName($lastName) {
    $this->lastName = $lastName;
  }

  public function setOtherNames($otherNames) {
    $this->otherNames = $otherNames;
  }

  public function setPrimaryEmail($primaryEmail) {
    $this->primaryEmail = $primaryEmail;
  }

  public function setSecondaryEmail($secondaryEmail) {
    $this->secondaryEmail = $secondaryEmail;
  }

  public function setInstitution($institution) {
    $this->institution = Institution::fromString($institution);
  }

  public function setGraduationYear($graduationYear) {
    if (is_numeric($graduationYear)) {
      $this->graduationYear = $graduationYear;
    } else {
      throw new Exception('Graduation date was not a recognised number.');
    }
  }

  public function setMembershipType($membershipType) {
    $this->membershipType = MembershipType::fromString($membershipType);
  }

  public function setExpiry($expiry) {
    $time = strtotime($expiry);
    $format = date('d-m-Y', $time);
    $this->expiry = $format;
  }

}
