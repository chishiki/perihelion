<?php

final class AuthViewController {

	private $loc;
	private $input;
	private $errors;
	private $modules;
	
	public function __construct($loc, $input, $errors, $modules) {
		
		$this->loc = $loc;
		$this->input = $input;
		$this->errors = $errors;
		$this->modules = $modules;
		
	}
	
	public function getView() {

		switch ($this->loc[0]) {

			case 'account-recovery':
			
				if (Auth::isLoggedIn()) { die('Logged in users don\'t need to recover their account.'); }
				$view = new AuthView($this->loc,$this->input,$this->errors);
				return $view->accountRecovery();
				break;
			
			case 'account-recovery-mail-sent':
		
				if (Auth::isLoggedIn()) { die('Logged in users don\'t need to recover their account.'); }
				$view = new AuthView($this->loc,$this->input,$this->errors);
				return $view->accountRecoveryMailSent();
				break;
				
			case 'login':

				$view = new AuthView($this->loc,$this->input,$this->modules,$this->errors);
				if (Auth::isLoggedIn()) { return $view->loginSuccessful(); } else { return $view->login(); }
				break;

			case 'reset-password':
			
				if (Auth::isLoggedIn()) { die('Logged in users can change their passwords .'); }
				$view = new AuthView($this->loc,$this->input,$this->errors);
				return $view->resetPasswordForm();
				break;

			default:
			
				header("Location: /");

		}
		
		
		
	}
	
}

?>