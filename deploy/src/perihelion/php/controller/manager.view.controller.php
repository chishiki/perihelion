<?php

class ManagerViewController {

	private $loc;
	private $input;
	private $modules;
	private $errors;
	private $messages;
	
	public function __construct($loc, $input, $modules, $errors, $messages) {

		$this->loc = $loc;
		$this->input = $input;
		$this->modules = $modules;
		$this->errors = $errors;
		$this->messages = $messages;
		
		$authorizedRoles = array('siteAdmin','siteManager');
		$role = Auth::getUserRole();
		if (!in_array($role,$authorizedRoles)) { die("You do not have view permissions for the manager module."); }
		
	}
	
	public function getView() {

		$loc = $this->loc;
		$input = $this->input;
		$modules = $this->modules;
		$errors = $this->errors;
		$messages = $this->messages;

		$menu = new MenuView($this->loc,$this->input,$this->errors);
		$nav = $menu->siteSettingsNav();
		
		if ($this->loc[1] == 'settings') {
			$view = new SiteView($loc, $input, $modules, $errors, $messages);
			return $nav . $view->siteSettingsForm();
		}
		
		if ($this->loc[1] == 'google') {
			$view = new SiteView($loc, $input, $modules, $errors, $messages);
			return $nav . $view->siteGoogleForm();
		}
		
		if ($this->loc[1] == 'social') {
			$view = new SiteView($loc, $input, $modules, $errors, $messages);
			return $nav . $view->siteSocialForm();
		}
		
		if ($this->loc[1] == 'email') {
			$view = new SiteView($loc, $input, $modules, $errors, $messages);
			return $nav . $view->siteEmailForm();
		}
		
		if ($this->loc[1] == 'modules') {
			$view = new SiteView($loc, $input, $modules, $errors, $messages);
			return $nav . $view->siteModulesForm();
		}

		if ($this->loc[1] == 'users') {
			$view = new UserView($this->loc,$this->input,$this->errors);
			if ($this->loc[2] == 'create') {
				return $nav . $view->userForm();
			} elseif ($this->loc[2] == 'update' && ctype_digit($this->loc[3])) {
				return $nav . $view->userForm($this->loc[3]);
			} elseif ($this->loc[2] == 'revoke-access') {
				return $nav . $view->revokeAccessConfirmationForm($this->loc[3]);
			} elseif ($this->loc[2] == 'grant-access') {
				return $nav . $view->grantAccessForm($this->loc[3]);
			} else {
				return $nav . $view->userList($_SESSION['siteID']);
			}
		}

		if ($this->loc[1] == 'contacts') {
			
			$view = new ContactView($this->loc,$this->input,$this->errors);
			
			if ($this->loc[2] == 'view' && ctype_digit($this->loc[3])) {
				$contact = new Contact($this->loc[3]);
				if ($contact->siteID == $_SESSION['siteID']) {
					return $nav . $view->contactView($this->loc[3]);
				}
			} else {
				return $nav . $view->contactList();
			}
			
			
		}
		
		if ($this->loc[1] == 'audit') {
			
			$view = new AuditView($this->loc,$this->input,$this->errors);
			return $nav . $view->auditTrail('manager');
			
		}
		
		if ($this->loc[1] == 'uptime') {

			$view = new UptimeView($this->loc,$this->input,$this->errors);
			return $nav . $view->uptime('manager');

		}
		
		if ($this->loc[1] == 'newsletter') {
			
			$view = new NewsletterView($this->loc,$this->input,$this->errors,$this->messages);
			return $nav . $view->subscriberList();
			
		}

		
	}
	
}

?>