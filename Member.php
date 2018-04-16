<?php

require_once "Database.php";
require_once "MembershipType.php";
require_once "Institution.php";

class Member {
  private $memberId;
  private $camdramId;
  private $crsid;
  private $lastName;
  private $otherNames;
  private $primaryEmail;
  private $secondaryEmail;
  private $institutionId;
  private $graduationYear;
  private $membershipId;
  private $expiry;

  private function __construct($memberId, $camdramId, $crsid, $lastName, $otherNames,
                               $primaryEmail, $secondaryEmail, $institutionId,
                               $graduationYear, $membershipId, $expiry) {
    $this->memberId = $memberId;
    $this->setCamdramId($camdramId);
    $this->setCrsid($crsid);
    $this->setLastName($lastName);
    $this->setOtherNames($otherNames);
    $this->setPrimaryEmail($primaryEmail);
    $this->setSecondaryEmail($secondaryEmail);
    $this->setInstitutionId($institutionId);
    $this->setGraduationYear($graduationYear);
    $this->setMembershipId($membershipId);
    $this->setExpiry($expiry);
  }

  public static function memberFromCamdramId($id) {
    $conn = Database::getInstance()->getConn();
    $stmt = $conn->prepare('SELECT * FROM ((members
                            INNER JOIN institutions
                            ON members.institution_id =
                                institutions.institution_id)
                            INNER JOIN membership
                            ON members.membership_id =
                                membership.membership_id)
                            WHERE camdram_id = ?');
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      throw new Exception("Member does not exist.");
    }
    $row = $result->fetch_assoc();
    $member = new Member($row['member_id'], $row['camdram_id'], $row['crsid'],
                        $row['last_name'], $row['other_names'],
                        $row['primary_email'], $row['secondary_email'],
                        $row['institution_id'], $row['graduation_year'],
                        $row['membership_id'], $row['expiry']);
    return $member;
  }

  public static function memberFromPrimaryEmail($email) {
    $conn = Database::getInstance()->getConn();
    $stmt = $conn->prepare('SELECT * FROM ((members
                            INNER JOIN institutions
                            ON members.institution_id =
                                institutions.institution_id)
                            INNER JOIN membership
                            ON members.membership_id =
                                membership.membership_id)
                            WHERE primary_email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      throw new Exception("Member does not exist.");
    }
    $row = $result->fetch_assoc();
    $member = new Member($row['member_id'], $row['camdram_id'], $row['crsid'],
                        $row['last_name'], $row['other_names'],
                        $row['primary_email'], $row['secondary_email'],
                        $row['institution_id'], $row['graduation_year'],
                        $row['membership_id'], $row['expiry']);
    return $member;
  }

  public static function memberFromCrsid($crsid) {
    $conn = Database::getInstance()->getConn();
    $stmt = $conn->prepare('SELECT * FROM ((members
                            INNER JOIN institutions
                            ON members.institution_id =
                                institutions.institution_id)
                            INNER JOIN membership
                            ON members.membership_id =
                                membership.membership_id)
                            WHERE crsid = ?');
    $stmt->bind_param('s', $crsid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      throw new Exception("Member does not exist.");
    }
    $row = $result->fetch_assoc();
    $member = new Member($row['member_id'], $row['camdram_id'], $row['crsid'],
                        $row['last_name'], $row['other_names'],
                        $row['primary_email'], $row['secondary_email'],
                        $row['institution_id'], $row['graduation_year'],
                        $row['membership_id'], $row['expiry']);
    return $member;
  }

  public function flushToDatabase() {
    $conn = Database::getInstance()->getConn();
    $stmt = $conn->prepare('UPDATE members SET camdram_id = ?,
                                               crsid = ?,
                                               last_name = ?,
                                               other_names = ?,
                                               primary_email = ?,
                                               secondary_email = ?,
                                               institution_id = ?,
                                               graduation_year = ?,
                                               membership_id = ?
                                               expiry = ?
                            WHERE member_id = ?');
    $stmt->bind_param('ssssssssss', $this->camdramId, $this->crsid,
                                   $this->lastName, $this->otherNames,
                                   $this->primaryEmail, $this->secondaryEmail,
                                   $this->institutionId, $this->graduationYear,
                                   $this->membershipType, $this->expiry);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
  }

  public function getMemberId() {
    return $this->memberId;
  }

  public function getCamdramId() {
    return $this->camdramId;
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

  public function getInstitutionId() {
    return $this->institutionId;
  }

  public function getGraduationYear() {
    return $this->graduationYear;
  }

  public function getMembershipId() {
    return $this->membershipId;
  }

  public function getExpiry() {
    if ($this->expiry === "01-01-1970") {
      return "LIFE";
    } else {
      return $this->expiry;
    }
  }

  public function setMemberId($memberId) {
    // member_id is the primary key
    throw new Exception('You cannot change a membership ID.');
  }

  public function setCamdramId($camdramId) {
    $this->camdramId = $camdramId;
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

  public function setInstitutionId($institutionId) {
    // Check ID is valid
    Institution::fromId($institutionId);
    $this->institutionId = $institutionId;
  }

  public function setGraduationYear($graduationYear) {
    // Alumni can adjust their graduation dates so don't validate beyond checking it's numeric
    if (is_numeric($graduationYear)) {
      $this->graduationYear = $graduationYear;
    } else {
      throw new Exception('Graduation date was not recognised as a valid year.');
    }
  }

  public function setMembershipId($membershipId) {
    // Check ID is valid
    MembershipType::fromId($membershipId);
    $this->membershipId = $membershipId;
  }

  public function setExpiry($expiry) {
    $time = strtotime($expiry);
    $format = date('d-m-Y', $time);
    $this->expiry = $format;
  }

}
