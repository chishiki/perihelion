<?php

class AuthViewController {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	private $modules;
	
	public function __construct($urlArray, $inputArray, $errorArray, $modules) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		$this->modules = $modules;
		
	}
	
	public function getView() {

		switch ($this->urlArray[0]) {

				
			case 'account-recovery':
			
				if (Auth::isLoggedIn()) { die('Logged in users don\'t need to recover their account.'); }
				$view = new AuthView($this->urlArray,$this->inputArray,$this->errorArray);
				return $view->accountRecovery();
				break;
			
			case 'account-recovery-mail-sent':
		
				if (Auth::isLoggedIn()) { die('Logged in users don\'t need to recover their account.'); }
				$view = new AuthView($this->urlArray,$this->inputArray,$this->errorArray);
				return $view->accountRecoveryMailSent();
				break;
				
			case 'login':

				$view = new AuthView($this->urlArray,$this->inputArray,$this->modules,$this->errorArray);
				if (Auth::isLoggedIn()) { return $view->loginSuccessful(); } else { return $view->login(); }
				break;

			case 'reset-password':
			
				if (Auth::isLoggedIn()) { die('Logged in users can change their passwords .'); }
				$view = new AuthView($this->urlArray,$this->inputArray,$this->errorArray);
				return $view->resetPasswordForm();
				break;

			default:
			
				header("Location: /");

		}
		
		
		
	}
	
}

?>