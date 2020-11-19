<?php

class ManagerViewController {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	private $messageArray;
	
	public function __construct($urlArray, $inputArray, $errorArray, $messageArray) {

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		$this->messageArray = $messageArray;
		
		$authorizedRoles = array('siteAdmin','siteManager');
		$role = Auth::getUserRole();
		if (!in_array($role,$authorizedRoles)) { die("You do not have view permissions for the manager module."); }
		
	}
	
	public function getView() {
		
		$menu = new MenuView($this->urlArray,$this->inputArray,$this->errorArray);
		$nav = $menu->siteSettingsNav();
		
		if ($this->urlArray[1] == 'settings') {
			$view = new SiteView($this->urlArray,$this->inputArray,$this->errorArray,$this->messageArray);
			return $nav . $view->siteSettingsForm();
		}
		
		if ($this->urlArray[1] == 'google') {
			$view = new SiteView($this->urlArray,$this->inputArray,$this->errorArray,$this->messageArray);
			return $nav . $view->siteGoogleForm();
		}
		
		if ($this->urlArray[1] == 'social') {
			$view = new SiteView($this->urlArray,$this->inputArray,$this->errorArray,$this->messageArray);
			return $nav . $view->siteSocialForm();
		}
		
		if ($this->urlArray[1] == 'email') {
			$view = new SiteView($this->urlArray,$this->inputArray,$this->errorArray,$this->messageArray);
			return $nav . $view->siteEmailForm();
		}
		
		if ($this->urlArray[1] == 'modules') {
			$view = new SiteView($this->urlArray,$this->inputArray,$this->errorArray,$this->messageArray);
			return $nav . $view->siteModulesForm();
		}

		if ($this->urlArray[1] == 'users') {
			$view = new UserView($this->urlArray,$this->inputArray,$this->errorArray);
			if ($this->urlArray[2] == 'create') {
				return $nav . $view->userForm();
			} elseif ($this->urlArray[2] == 'update' && ctype_digit($this->urlArray[3])) {
				return $nav . $view->userForm($this->urlArray[3]);
			} elseif ($this->urlArray[2] == 'revoke-access') {
				return $nav . $view->revokeAccessConfirmationForm($this->urlArray[3]);
			} elseif ($this->urlArray[2] == 'grant-access') {
				return $nav . $view->grantAccessForm($this->urlArray[3]);
			} else {
				return $nav . $view->userList($_SESSION['siteID']);
			}
		}

		if ($this->urlArray[1] == 'contacts') {
			
			$view = new ContactView($this->urlArray,$this->inputArray,$this->errorArray);
			
			if ($this->urlArray[2] == 'view' && ctype_digit($this->urlArray[3])) {
				$contact = new Contact($this->urlArray[3]);
				if ($contact->siteID == $_SESSION['siteID']) {
					return $nav . $view->contactView($this->urlArray[3]);
				}
			} else {
				return $nav . $view->contactList();
			}
			
			
		}
		
		if ($this->urlArray[1] == 'audit') {
			
			$view = new AuditView($this->urlArray,$this->inputArray,$this->errorArray);
			return $nav . $view->auditTrail('manager');
			
		}
		
		if ($this->urlArray[1] == 'uptime') {

			$view = new UptimeView($this->urlArray,$this->inputArray,$this->errorArray);
			return $nav . $view->uptime('manager');

		}
		
		if ($this->urlArray[1] == 'newsletter') {
			
			$view = new NewsletterView($this->urlArray,$this->inputArray,$this->errorArray,$this->messageArray);
			return $nav . $view->subscriberList();
			
		}

		
	}
	
}

?>