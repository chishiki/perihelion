<?php

class ProfileController {

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
		$this->errorArray = array();
		
		if (!Auth::isLoggedIn()) { die("ProfileController :: You are not logged in."); }
		
	}
	
	public function setState() {
		
		if ($this->urlArray[0] == 'profile') {
			
			
			
			$successURL = "/" . Lang::languageUrlPrefix() . "profile/confirmation/";
			
			if (!empty($this->inputArray)) {

				$user = new User($_SESSION['userID']);
				
				if ($this->inputArray['userID'] != $_SESSION['userID']) {
					$this->errorArray['userID'][] = 'There is an ID mismatch.';
					$ioa = new Audit();
					$ioa->auditAction = 'update';
					$ioa->auditObject = 'User';
					$ioa->auditObjectID = $_SESSION['userID'];
					$ioa->auditResult = 'unsuccessful';
					$ioa->auditNote = '{"flag":"userID mismatch on profile update"}';
					Audit::createAuditEntry($ioa);
				}
				
				if ($this->inputArray['profileChangePassword']) {

					if ($this->inputArray['userPassword'] != $this->inputArray['confirmPassword']) { $this->errorArray['userPassword'][] = 'Passwords do not match.'; }
					if (empty($this->inputArray['userPassword']) || empty($this->inputArray['confirmPassword'])) {
						$this->errorArray['userPassword'][] = 'Please leave the "Change Password" checkbox unchecked if you do not want to update your password.';
					}
					
				}
				
				if (!Utilities::isValidEmail($this->inputArray['userEmail'])) { $this->errorArray['userEmail'][] = 'That does not appear to be a valid email address'; }
				if (!Utilities::isLettersNumbersHyphensOnly($this->inputArray['username'])) { $this->errorArray['username'][] = 'Usernames can contain only letters, numbers, and hyphens.'; }
				
				if (($this->inputArray['username'] != $user->username) && User::usernameInUse($this->inputArray['username'])) {
					$this->errorArray['username'][] = 'That username is already in use by another Perihelion account.';
				}
				
				if (($this->inputArray['userEmail'] != $user->userEmail) && User::emailInUse($this->inputArray['userEmail'])) {
					$this->errorArray['userEmail'][] = 'That email address is linked to a different Perihelion account.';
				}
				
				if (empty($this->inputArray['userDisplayName'])) { $this->errorArray['userDisplayName'][] = 'Please enter in your name as you would like it displayed in the system.'; }

				if (empty($this->errorArray)) {
					
					$user->userEmail = $this->inputArray['userEmail'];
					$user->username = $this->inputArray['username'];
					$user->userDisplayName = $this->inputArray['userDisplayName'];
					
					if ($this->inputArray['profileChangePassword']) {
						$user->userPassword = password_hash($this->inputArray['userPassword'], PASSWORD_DEFAULT);
					}
					
					$site = new Site($_SESSION['siteID']);
					$senderName = $site->siteAutomatedEmailSenderName;
					$senderEmail = $site->siteAutomatedEmailAddress;
					$siteEmail = $senderName . " <" . $senderEmail . ">";
					$admin = Config::read('admin.email');
					$mailRecipientArray = array($user->userEmail,$admin);
					$mailSender = $siteEmail;
					$mailSubject = "Your account details have been updated.";
					$mailMessage = $user->userEmail . "'s account details have been changed.\n\n";
					$mailMessage .= "If you did not change your account details, this may mean that an unauthorized user is accessing your Perihelion account. Please notify " . Config::read('support.email') . " immediately.\n\n";
					$mailMessage .= "If you forget your password you can always reset it: https://" . $site->siteURL . "/account-recovery/\n\n";
					foreach ($mailRecipientArray AS $mailRecipient) { Mail::sendEmail($mailRecipient, $mailSender, $mailSubject, $mailMessage); }

					$conditions = array('userID' => $_SESSION['userID']);
					User::update($user,$conditions);
					
					header("Location: $successURL");
					
				}
				
			}
			
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