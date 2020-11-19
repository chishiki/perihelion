<?php

class UserController {

	private $urlArray;
	private $inputArray;
	private $moduleArray;
	private $errorArray;
	private $messageArray;
	
	public function __construct($urlArray, $inputArray, $moduleArray) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->moduleArray = $moduleArray;
		$this->errorArray = array();
		$this->messageArray = array();
		
	}
	
	public function setState() {
		
		if (!Auth::isSiteManager()) { header("Location: /"); }
		
		if ($this->urlArray[1] == 'create') {
			
			if (!empty($this->inputArray)) {
				
				$user = new User();
				foreach ($this->inputArray AS $property => $value) { if (isset($user->$property)) { $user->$property = $value; } }
				$this->errorArray = User::validate('create',$this->inputArray['userEmail'],$this->inputArray['userPassword'],$this->inputArray['confirmPassword']);
				
				if (empty($this->errorArray)) {
					// $userID = User::insert($user);
					header("Location: /user-manager/");
				}
				
			}
		} elseif ($this->urlArray[1] == 'update' && is_numeric($this->urlArray[2])) {
			
			$userID = $this->urlArray[2];

			if (!empty($this->inputArray)) {

				$user = new User($userID);
				foreach ($this->inputArray AS $property => $value) { if (isset($user->$property)) { $user->$property = $value; } }
				if (!isset($this->inputArray['userIsPublic'])) { $user->userIsPublic = 0; }
				$this->errorArray = User::validate('update',$this->inputArray['userEmail'],$this->inputArray['userPassword'],$this->inputArray['confirmPassword']);
				
				if (empty($this->errorArray)) {
					$updateConditions = array('userID' => $userID);
					// User::update($user, $updateConditions);
					header("Location: /user-manager/");
				}
				
			}
			
		} elseif ($this->urlArray[1] == 'grant-access' && !empty($this->inputArray['userEmail'])) {

			$this->errorArray = User::grantAccessValidate($this->inputArray['userEmail']);

			if (empty($this->errorArray)) { // email is OK; user may exist or not but does not already have site access

			
				$userEmail = $this->inputArray['userEmail'];
				$role = $this->inputArray['userRole'];
				
				$userID = User::getUserID($userEmail);
				if (!$userID) { // IF user does not exist THEN create one
					$newUser = true;
					$user = new User();
					$user->userEmail = $userEmail;
					$user->username = $userEmail;
					$user->userDisplayName = $userEmail;
					$userPassword = $user->userPassword;
					$user->userPassword = password_hash($userPassword, PASSWORD_DEFAULT);
					$user->userActive = 1;
					$userID = User::insert($user);
				}
				
				$userRole = new UserRole($_SESSION['siteID'], $userID);
				$userRole->setUserRole($role);
				UserRole::insert($userRole, false);

				$site = new Site($_SESSION['siteID']);
				$senderName = $site->siteAutomatedEmailSenderName;
				$senderEmail = $site->siteAutomatedEmailAddress;
				$siteEmail = $senderName . " <" . $senderEmail . ">";

				$siteTitle = $site->siteTitleEnglish;
				$siteURL = "http://" . $site->siteURL . "/";
				
				$admin = Config::read('admin.email');
				$mailRecipientArray = array($userEmail,$siteEmail,$admin);
				
				$mailSender = $siteEmail;
				$mailSubject = 'Welcome to the ' . $siteTitle . " website.";
				
				$mailMessage = "Hello and welcome to " . $siteTitle . ",\n\n";
				$mailMessage .= $userEmail . " has been granted " .  Lang::getLang($role, 'en') . "-level access to " . $siteURL . "login/.\n\n";
				$mailMessage .= "First time Perihelion users will need to use the account recovery feature to securely set their password: " . $siteURL . "account-recovery/\n\n";
				
				foreach ($mailRecipientArray AS $mailRecipient) {
					Mail::sendEmail($mailRecipient, $mailSender, $mailSubject, $mailMessage);
				}

			}

			
			
			
			
			
			
			if (empty($this->errorArray)) { header("Location: /user-manager/"); }

		}
		
	}
	
	public function getErrors() {
		return $this->errorArray;
	}

	public function getMessages() {
		return $this->messageArray;
	}
	
}

?>