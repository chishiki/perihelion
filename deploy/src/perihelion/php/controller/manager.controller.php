<?php

class ManagerController {

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
		
		if (!Auth::isLoggedIn()) {
			$_SESSION['forward_url'] = $_SERVER['REQUEST_URI'];
			$login = "/" . Lang::prefix() . "login/";
			header("Location: $login");
		}
		
		$authorizedRoles = array('siteAdmin','siteManager');
		$role = Auth::getUserRole();
		if (!in_array($role,$authorizedRoles)) { die("You do not have view permissions for the manager module."); }
		
	}
	
	public function setState() {
		
		if ($_SESSION['lang'] == 'ja') { $lang = "/ja"; } else { $lang = ""; }

		if ($this->urlArray[0] == 'manager' && $this->urlArray[1] == 'settings' && !empty($this->inputArray)) {

			// $this->errorArray = Site::validate($this->inputArray, 'update');

			if (isset($_FILES['imageUploads']) && $_FILES['imageUploads']['error'][0] != 4) {
				$this->errorArray = Image::uploadImages($_FILES['imageUploads'],'Logo',$_SESSION['siteID']);
			}
				
			if (empty($this->errorArray)) {
				
				$siteID = $_SESSION['siteID'];
				$site = new Site($siteID);
				$conditions = array('siteID' => $siteID);
				
				if (isset($this->inputArray['siteTitleEnglish'])) { $site->siteTitleEnglish = $this->inputArray['siteTitleEnglish']; }
				if (isset($this->inputArray['siteKeywordsEnglish'])) { $site->siteKeywordsEnglish = $this->inputArray['siteKeywordsEnglish']; }
				if (isset($this->inputArray['siteDescriptionEnglish'])) { $site->siteDescriptionEnglish = $this->inputArray['siteDescriptionEnglish']; }
				if (isset($this->inputArray['siteTitleJapanese'])) { $site->siteTitleJapanese = $this->inputArray['siteTitleJapanese']; }
				if (isset($this->inputArray['siteKeywordsJapanese'])) { $site->siteKeywordsJapanese = $this->inputArray['siteKeywordsJapanese']; }
				if (isset($this->inputArray['siteDescriptionJapanese'])) { $site->siteDescriptionJapanese = $this->inputArray['siteDescriptionJapanese']; }
				// if (!isset($this->inputArray['siteIndexable'])) { $site->siteIndexable = 0; }

				Site::update($site,$conditions);
				$this->messageArray[] = Lang::getLang('siteSettingsUpdateSuccessful');

			}
		
		}

		if ($this->urlArray[0] == 'manager' && $this->urlArray[1] == 'google' && !empty($this->inputArray)) {

			$this->errorArray = Site::validate($this->inputArray, 'update');
			
			if (empty($this->errorArray)) {
				
				$siteID = $_SESSION['siteID'];
				$site = new Site($siteID);
				$conditions = array('siteID' => $siteID);
				
				if (isset($this->inputArray['siteGoogleAnalyticsID'])) { $site->siteGoogleAnalyticsID = $this->inputArray['siteGoogleAnalyticsID']; }
				if (isset($this->inputArray['siteGoogleAdSenseID'])) { $site->siteGoogleAdSenseID = $this->inputArray['siteGoogleAdSenseID']; }
				if (isset($this->inputArray['siteGoogleApiKey'])) { $site->siteGoogleApiKey = $this->inputArray['siteGoogleApiKey']; }
				if (isset($this->inputArray['siteUsesGoogleMaps'])) {
					$site->siteUsesGoogleMaps = 1;
				} else {
					$site->siteUsesGoogleMaps = 0;
				}
				if (isset($this->inputArray['siteUsesLocationPicker'])) {
					$site->siteUsesLocationPicker = 1;
				} else {
					$site->siteUsesLocationPicker = 0;
				}

				Site::update($site,$conditions);
				$this->messageArray[] = Lang::getLang('siteGoogleUpdateSuccessful');

			}
		
		}
	
		if ($this->urlArray[0] == 'manager' && $this->urlArray[1] == 'social' && !empty($this->inputArray)) {

			$this->errorArray = Site::validate($this->inputArray, 'update');
			
			if (empty($this->errorArray)) {
				
				$siteID = $_SESSION['siteID'];
				$site = new Site($siteID);
				$conditions = array('siteID' => $siteID);
				
				if (isset($this->inputArray['siteTwitter'])) { $site->siteTwitter = $this->inputArray['siteTwitter']; }
				if (isset($this->inputArray['siteFacebook'])) { $site->siteFacebook = $this->inputArray['siteFacebook']; }
				if (isset($this->inputArray['siteLinkedIn'])) { $site->siteLinkedIn = $this->inputArray['siteLinkedIn']; }
				if (isset($this->inputArray['sitePinterest'])) { $site->sitePinterest = $this->inputArray['sitePinterest']; }
				if (isset($this->inputArray['siteInstagram'])) { $site->siteInstagram = $this->inputArray['siteInstagram']; }
				if (isset($this->inputArray['siteSkype'])) { $site->siteSkype = $this->inputArray['siteSkype']; }

				Site::update($site,$conditions);
				$this->messageArray[] = Lang::getLang('siteSocialUpdateSuccessful');

			}
		
		}
	
		if ($this->urlArray[0] == 'manager' && $this->urlArray[1] == 'email' && !empty($this->inputArray)) {

			$this->errorArray = Site::validate($this->inputArray, 'update');
			
			if (empty($this->errorArray)) {
				
				$siteID = $_SESSION['siteID'];
				$site = new Site($siteID);
				$conditions = array('siteID' => $siteID);
				
				if (isset($this->inputArray['siteContactFormToAddress'])) { $site->siteContactFormToAddress = $this->inputArray['siteContactFormToAddress']; }
				if (isset($this->inputArray['siteAutomatedEmailSenderName'])) { $site->siteAutomatedEmailSenderName = $this->inputArray['siteAutomatedEmailSenderName']; }
				if (isset($this->inputArray['siteAutomatedEmailAddress'])) { $site->siteAutomatedEmailAddress = $this->inputArray['siteAutomatedEmailAddress']; }

				Site::update($site,$conditions);
				$this->messageArray[] = Lang::getLang('siteEmailUpdateSuccessful');

			}
		
		}
	
		if ($this->urlArray[0] == 'manager' && $this->urlArray[1] == 'modules' && !empty($this->inputArray)) {

			$this->errorArray = Site::validate($this->inputArray, 'update');
			
			if (empty($this->errorArray)) {
				
				$siteID = $_SESSION['siteID'];
				$site = new Site($siteID);
				$conditions = array('siteID' => $siteID);

				// if (isset($this->inputArray['siteUsesRealEstate'])) { $site->siteUsesRealEstate = 1; } else { $site->siteUsesRealEstate = 0; }
				// if (isset($this->inputArray['indexDisplayPropertySearchWidget'])) { $site->indexDisplayPropertySearchWidget = 1; } else { $site->indexDisplayPropertySearchWidget = 0; }
				// if (isset($this->inputArray['indexDisplayFeaturedPropertyWidget'])) { $site->indexDisplayFeaturedPropertyWidget = 1; } else { $site->indexDisplayFeaturedPropertyWidget = 0; }
				// if (isset($this->inputArray['siteUsesAccommodation'])) { $site->siteUsesAccommodation = 1; } else { $site->siteUsesAccommodation = 0; }
				// if (isset($this->inputArray['siteUsesPropertyManagement'])) { $site->siteUsesPropertyManagement = 1; } else { $site->siteUsesPropertyManagement = 0; }
				// if (isset($this->inputArray['siteUsesProjectManagement'])) { $site->siteUsesProjectManagement = 1; } else { $site->siteUsesProjectManagement = 0; }
				if (isset($this->inputArray['indexDisplayPropertySearchWidgetTitleEnglish'])) { $site->indexDisplayPropertySearchWidgetTitleEnglish = $this->inputArray['indexDisplayPropertySearchWidgetTitleEnglish']; }
				if (isset($this->inputArray['indexDisplayPropertySearchWidgetTitleJapanese'])) { $site->indexDisplayPropertySearchWidgetTitleJapanese = $this->inputArray['indexDisplayPropertySearchWidgetTitleJapanese']; }
				if (isset($this->inputArray['indexDisplayFeaturedPropertyWidgetTitleEnglish'])) { $site->indexDisplayFeaturedPropertyWidgetTitleEnglish = $this->inputArray['indexDisplayFeaturedPropertyWidgetTitleEnglish']; }
				if (isset($this->inputArray['indexDisplayFeaturedPropertyWidgetTitleJapanese'])) { $site->indexDisplayFeaturedPropertyWidgetTitleJapanese = $this->inputArray['indexDisplayFeaturedPropertyWidgetTitleJapanese']; }
				
				Site::update($site,$conditions);
				$this->messageArray[] = Lang::getLang('siteModulesUpdateSuccessful');

			}
		
		}
		
		if ($this->urlArray[1] == 'users' && $this->urlArray[2] == 'update' && ctype_digit($this->urlArray[3])) {
			
			if (!empty($this->inputArray)) {
				
				$siteID = $_SESSION['siteID'];
				$userID = $this->urlArray[3];

				$currentUserRole = new UserRole($siteID,$userID);
				$currentRole = $currentUserRole->userRole;
				$selectedRole = $this->inputArray['userRole'];
				
				$validUserRoles = UserRole::validUserRoles();
				if (in_array($selectedRole,$validUserRoles) && $selectedRole != 'siteAdmin') {
				
					if ($currentRole != $selectedRole) { // if role exists AND is different from selected role then delete it and add new role
						$conditions = array('siteID' => $siteID,'userID' => $userID);
						UserRole::delete($currentUserRole,$conditions);
					}
						
					if (!$currentRole || $currentRole != $selectedRole) {
						$currentUserRole->userRole = $selectedRole;
						UserRole::insert($currentUserRole, false);
					}

					$successURL = $lang . "/manager/users/update/" . $userID . "/";
					header("Location: $successURL");
					
				} else {
					
					$this->errorArray['userRole'][] = Lang::getLang('invalidUserRole');
					
				}
				
			}
		}
		
		if ($this->urlArray[1] == 'users' && $this->urlArray[2] == 'grant-access') {

			if (!empty($this->inputArray)) {
				
				$inputUserEmail = $this->inputArray['userEmail'];
				$inputUserRole = $this->inputArray['userRole'];
					
				$validUserRoles = UserRole::validUserRoles();
				if (!in_array($inputUserRole,$validUserRoles) || $inputUserRole == 'siteAdmin') {
					$this->errorArray['userRole'][] = Lang::getLang('invalidUserRole');
				}
				
				if (empty($this->errorArray)) {

					$perihelionNewbie = false;
					
					// does User exist?
					$userID = User::getUserID($inputUserEmail);
					if ($userID) { // instantiate user
						$user = new User($userID);
					} else{ // if user does not exist then create
						$user = new User();
						$user->userID = 0;
						$user->createdDateTime = date('Y-m-d H:i:s');
						$user->username = $inputUserEmail;
						$user->userDisplayName = $inputUserEmail;
						$user->userEmail = $inputUserEmail;
						$password = $user->userPassword;
						$user->userPassword = password_hash($password, PASSWORD_DEFAULT);
						$user->userEmailVerified = 1;
						$user->userAcceptsEmail = 1;
						$user->userBlackList = 0;
						$user->userActive = 1;
						$userID = User::insert($user);
						$perihelionNewbie = true;
					}
					
					// does UserRole exist?
					$existingUserRole = new UserRole($_SESSION['siteID'],$userID);
					if ($existingUserRole->getUserRole()) { // if user role exists then delete it\
						$userRoleDeleteConditions = array('siteID' => $_SESSION['siteID'],'userID' => $userID);
						UserRole::delete($existingUserRole, $userRoleDeleteConditions);
					}

					// add user role for this user
					$newUserRole = new UserRole($_SESSION['siteID'],$userID);
					$newUserRole->userRole = $inputUserRole;
					UserRole::insert($newUserRole, false);
					
					// send notification email
					$site = new Site($_SESSION['siteID']);
					$toAddress = $inputUserEmail;
					$fromAddress = $site->siteAutomatedEmailSenderName . ' <' . $site->siteAutomatedEmailAddress . '>';
					$mailSubject = '[ you can now login to ' . $site->siteURL . ' ]';

					if ($site->siteUsesPropertyManagement && $perihelionNewbie && $inputUserRole == 'siteOwner') {
						
						$message = "<p>Here are your details for logging in to the " . $site->getTitle() . " system:</p>";
						$message .= "<p>Login page: http://" . $site->siteURL . "/login/<br />Registered email address: " . $user->userEmail . "<br />Password: " . $password . "</p>";
						$message .= "<p>You can access your recent invoices, cashflow statements, bank information, and basic property information from the drop down menu at the top of the page. ";
						$message .= "Please note that we are continuously adding to the system so there may be changes in the future.</p>";
						$message .= "<p>You can log in to the system using your email address or your username. You can create a username and change your password within the system under the Profile tab. Please keep your password safe.</p>";
						$message .= "<p>Thanks for using " . $site->getTitle() . ".</p>";
						
					} elseif ($perihelionNewbie) {
						
						$message = "<p>Welcome to " . $site->siteTitleEnglish . ". A new account has been established for you.</p>";
						$message .= "<p>You can login via http://" . $site->siteURL . "/login/ =&gt; ( " . $inputUserEmail . " | " . $password . " )</p>";
						
					} else {
						
						$message = "<p>You have been granted access to " . $site->siteTitleEnglish . ".</p>";
						$message .= "<p>You can now login via http://" . $site->siteURL . "/login/ using your existing Perihelion login details.</p>";
						
					}

					$mailContent = Mail::htmlMailContentWrapper($message);
					
					Mail::sendEmail($toAddress, $fromAddress, $mailSubject, $mailContent, $_SESSION['siteID'], $_SESSION['userID'], 'html');
					Mail::sendEmail(Config::read('support.email'), $fromAddress, $mailSubject, $mailContent, $_SESSION['siteID'], $_SESSION['userID'], 'html');
					
					$successURL = $lang . "/manager/users/";
					header("Location: $successURL");
					
				}
				
			}
			
		}
		
		if ($this->urlArray[1] == 'users' && $this->urlArray[2] == 'revoke-access' && ctype_digit($this->urlArray[3])) {
			
			if (!empty($this->inputArray[$this->urlArray[3]])) {
				
				$siteID = $_SESSION['siteID'];
				$userID = $this->urlArray[3];
				
				$userRole = new UserRole($siteID,$userID);
				$conditions = array('siteID' => $siteID,'userID' => $userID);
				UserRole::delete($userRole,$conditions);
				
				// consider also deleting sessions
				
				$successURL = $lang . "/manager/users/";
				header("Location: $successURL");
				
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