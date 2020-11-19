<?php

class AccountRecovery extends ORM {

	public $accountRecoveryID;
	public $accountRecoveryEmail;
	public $accountRecoveryUserID;
	public $accountRecoveryRequestDateTime;
	public $accountRecoveryRequestedFromIP;
	public $accountRecoveryMash;
	public $accountRecoveryVisited;
	
	public function __construct($accountRecoveryID) {

		if ($accountRecoveryID) {

			$query = "SELECT * FROM perihelion_AccountRecovery WHERE accountRecoveryID = :accountRecoveryID LIMIT 1";
			$nucleus = Nucleus::getInstance();
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':accountRecoveryID' => $accountRecoveryID));
			$row = $statement->fetch();
			foreach ($row AS $property => $value) { if (!is_int($property)) { $this->$property = $value; } }
			
		} else {
			
			$this->accountRecoveryID = 0;
			$this->accountRecoveryEmail = '';
			$this->accountRecoveryUserID = 0;
			$this->accountRecoveryRequestDateTime = '0000-00-00 00:00:00';
			$this->accountRecoveryRequestedFromIP = $_SERVER['REMOTE_ADDR'];
			$this->accountRecoveryMash = Utilities::generateMash();
			$this->accountRecoveryVisited = 0;
			
		}

	}

	public static function getAccountRecoveryID($accountRecoveryMash) {

		$accountRecoveryID = 0;
		$nucleus = Nucleus::getInstance();
		$query = "SELECT accountRecoveryID FROM perihelion_AccountRecovery WHERE accountRecoveryMash = :accountRecoveryMash ORDER BY accountRecoveryRequestDateTime DESC LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':accountRecoveryMash' => $accountRecoveryMash));
		if ($row = $statement->fetch()) { $accountRecoveryID = $row['accountRecoveryID']; }
		return $accountRecoveryID;
	
	}
	
	public static function accountRecoveryValidation($userEmail) {
		
		$errorArray = array();

		$userID = User::getUserID($userEmail);
		$user = new User($userID);
		$userRole = new UserRole($_SESSION['siteID'],$userID);
		
		if ($userEmail == '') {
			$errorArray['userEmail'][] = "The 'email' field is required.";
		} elseif (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
			$errorArray['userEmail'][] = "That email address appears to be formatted incorrectly.";
		} elseif (!$userID) {
			$errorArray['userEmail'][] = "We do not seem to have an account for that email address.";
		} elseif ($user->userBlackList) {
			$errorArray['userEmail'][] = "There seems to be a problem with your account. Please contact support@zenidev.com.";
		} elseif (!$userRole->getUserRole() && $_SESSION['siteID'] != 1) {
			$errorArray['userEmail'][] = 'Please contact support@zenidev.com for access to this domain.';
		}
		
		return $errorArray;
	}

	public static function resetPasswordRequestValidation($accountRecoveryMash, $confirmMash, $userEmail, $password, $confirmPassword) {

		$errorArray = array();
		
		$userID = User::getUserID($userEmail);
		$accountRecoveryID = self::getAccountRecoveryID($accountRecoveryMash);
		$accountRecovery = new AccountRecovery($accountRecoveryID);
		$currentDateTime = date('Y-m-d H:i:s');

		// email
		if ($userEmail == '') { $errorArray['userEmail'][] = "Please enter your email address."; }
		if (!User::emailInUse($userEmail)) { $errorArray['userEmail'][] = "That email address is not associated with a Perihelion account."; }
		if ($userID != 0 && $userID != $accountRecovery->accountRecoveryUserID) { $errorArray['userEmail'][] = "This password reset URL is not associated with that email address."; }
		
		
		// password
		if ($password == '') { $errorArray['password'][] = "Please enter a password."; }
		if ($confirmPassword == '') { $errorArray['password'][] = "You must enter your password twice."; }
		if ($password != $confirmPassword) { $errorArray['password'][] = "Your passwords did not match."; }
		
		// mash
		if ($accountRecoveryMash != $confirmMash) { $errorArray['mash'][] = 'You seem to have encountered an error while resetting your password.'; }
		if ($currentDateTime >= date('Y-m-d H:i:s', strtotime($accountRecovery->accountRecoveryRequestDateTime . " +1 day"))) { $errorArray['mash'][] = "This password reset URL has expired."; }
		if ($userID != 0 && !self::isMostRecentAccountRecoveryMash($accountRecoveryMash, $userID)) { $errorArray['mash'][] = "This is not your most recent account recovery request."; }
		
		return $errorArray;
	}
	
	public static function isMostRecentAccountRecoveryMash($accountRecoveryMash, $userID) {
		
		$nucleus = Nucleus::getInstance();
		$query = "
			SELECT accountRecoveryMash FROM perihelion_AccountRecovery 
			WHERE accountRecoveryUserID = :userID
			ORDER BY accountRecoveryRequestDateTime DESC
			LIMIT 1
		";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':userID' => $userID));
		if ($row = $statement->fetch()) { $armash = $row['accountRecoveryMash']; } else { $armash = ''; }
		if ($armash == $accountRecoveryMash) { return true; } else { return false; }
		
	}
	
}

?>