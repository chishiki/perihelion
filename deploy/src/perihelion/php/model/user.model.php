<?php

class User extends ORM {

	public $userID;
	public $createdDateTime; 
	public $lastUpdateDateTime; 
	public $deleted;
	public $deletionDateTime;
	public $username;
	public $userDisplayName;
	public $userEmail;
	public $userEmailVerified;
	public $userAcceptsEmail;
	public $userPassword;
	public $userRegistrationSiteID;
	public $userRegistrationDateTime;
	public $userLastVisitDateTime;
	public $userBlackList;
	public $userActive;

	public function __construct($userID = null) {

		$this->userID = 0;
		$this->createdDateTime = '0000-00-00 00:00:00';
		$this->lastUpdateDateTime = '0000-00-00 00:00:00';
		$this->deleted = 0;
		$this->deletionDateTime = '0000-00-00 00:00:00';
		$this->username = '';
		$this->userDisplayName = '';
		$this->userEmail = '';
		$this->userEmailVerified = 0;
		$this->userAcceptsEmail = 0;
		$this->userPassword = Utilities::generateUniqueKey();
		$this->userRegistrationSiteID = $_SESSION['siteID'];
		$this->userRegistrationDateTime = date("Y-m-d H:i:s");
		$this->userLastVisitDateTime = '0000-00-00 00:00:00';
		$this->userBlackList = 0;
		$this->userActive = 0;
	
		if ($userID) {
			
			$query = "SELECT * FROM perihelion_User WHERE userID = :userID LIMIT 1";
			$nucleus = Nucleus::getInstance();
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':userID' => $userID));
			if ($row = $statement->fetch()) {
			    foreach ($row AS $key => $value) { if (isset($this->$key)) { $this->$key = $value; } }
			}

		}

	}
	
	public function getUserDisplayName() {
		
		$userEmail = $this->userEmail;
		$userDisplayName = $this->userDisplayName;
		if ($userDisplayName != '') { return $userDisplayName; } else { return $userEmail; }
		
	}
	
	public function setUserLastVisitDateTime() {
		
		$userLastVisitDateTime = date('Y-m-d H:i:s');
		$nucleus = Nucleus::getInstance();
		$query = "UPDATE perihelion_User SET userLastVisitDateTime = '$userLastVisitDateTime' WHERE userID = :userID LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':userID' => $this->userID));
		
	}
	
	public function groupMembership() {
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT groupID FROM perihelion_Group WHERE userID = :userID ORDER BY groupID ASC";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':userID' => $this->userID));

		$groupArray = array();
		while ($row = $statement->fetch()) { $groupArray[] = $row['groupID']; }
		return $groupArray;
		
	}
	
	public function validate($validationType,$userEmail,$userPassword,$confirmPassword) {
		
		$errorArray = array();

		if (!isset($userEmail) || empty($userEmail)) {
			$errorArray['userEmail'][] = "Please provide an email address.";
		} else {
			if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) { $errorArray['userEmail'][] = "That email address appears to be formatted incorrectly."; }
			if (self::emailInUse($userEmail)) { $errorArray['userEmail'][] = "An account is already linked to this email.<br />Please try <a href=\"/login/\">logging in</a>."; }
		}

		return $errorArray;

	}

	public static function getUserList() {
		
		$query = "SELECT userID FROM perihelion_User ORDER BY userDisplayName ASC";
		
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->execute();
		
		$users = array();
		while ($row = $statement->fetch()) { $users[] = $row['userID']; }
		return $users;
		
	}
	
	public static function getUserID($userSelector) { // accepts username or userEmail

		$nucleus = Nucleus::getInstance();
		$query = "SELECT userID FROM perihelion_User WHERE username = :userSelector OR userEmail = :userSelector LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':userSelector' => $userSelector));
		
		$userID = 0;
		if ($row = $statement->fetch()) { $userID = $row['userID']; }
		return $userID;
	
	}

	public static function usernameInUse($username) {
	
		$nucleus = Nucleus::getInstance();
		$query = "SELECT username FROM perihelion_User WHERE username = :username LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':username' => $username));
		if ($row = $statement->fetch()) { return true; } else { return false; }
	
	}
	
	public static function emailInUse($userEmail) {
	
		$nucleus = Nucleus::getInstance();
		$query = "SELECT userEmail FROM perihelion_User WHERE userEmail = :userEmail LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':userEmail' => $userEmail));
		if ($row = $statement->fetch()) { return true; } else { return false; }
	
	}
	
	public static function getUserArray($siteID = null) {

		$userArray = array();
		
		if (!$siteID) { $siteID = $_SESSION['siteID']; }
		$userID = $_SESSION['userID'];
		$userRole = new UserRole($siteID,$userID);
		$role = $userRole->getUserRole();
		$userRolePermissions = array('siteManager','siteAdmin');
		
		if (in_array($role,$userRolePermissions)) {
			$nucleus = Nucleus::getInstance();
			$query = "
				SELECT perihelion_UserRole.userID AS userID 
				FROM perihelion_UserRole LEFT JOIN perihelion_User
				ON perihelion_UserRole.userID = perihelion_User.userID
				WHERE perihelion_UserRole.siteID = :siteID AND perihelion_User.userActive = 1
				ORDER BY perihelion_User.userDisplayName ASC
			";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':siteID' => $siteID));
			while ($row = $statement->fetch()) { $userArray[] = $row['userID']; }
		}
		return $userArray;
		
	}
	
	public static function grantAccessValidate($userEmail) {
		
		$errorArray = array();

		if (!isset($userEmail) || empty($userEmail)) {
			$errorArray['userEmail'][] = "Please provide an email address.";
		} else {
			if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) { $errorArray['userEmail'][] = "That email address appears to be formatted incorrectly."; }
		}

		if (empty($errorArray)) {
			$userID = User::getUserID($userEmail);
			$siteID = $_SESSION['siteID'];
			if ($userRole = UserRole::hasAccess($siteID, $userID)) {
				$errorArray['userEmail'][] = $userEmail . " already has " . Lang::getLang($userRole, 'en') . "-level access to this website.";
			}
		}
		
		return $errorArray;

	}

}

final class UserList {

	private $users;

	public function __construct() {

		$this->users = array();

		$nucleus = Nucleus::getInstance();

		$where = array();
		$where[] = 'perihelion_UserRole.siteID = :siteID';
		$where[] = 'perihelion_User.userActive = 1';

		$query = 'SELECT perihelion_UserRole.userID AS userID ';
		$query .= 'FROM perihelion_UserRole LEFT JOIN perihelion_User ';
		$query .= 'ON perihelion_UserRole.userID = perihelion_User.userID ';
		$query .= 'WHERE ' . implode(' AND ',$where) . ' ORDER BY perihelion_User.userDisplayName ASC';

		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
		$statement->execute();

		while ($row = $statement->fetch()) {
			$this->users[] = $row['userID'];
		}

	}

	public function getUsers() {

		return $this->users;

	}

}

?>