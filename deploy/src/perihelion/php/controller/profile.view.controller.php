<?php

class ProfileViewController {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;

		if (!Auth::isLoggedIn()) { die("ProfileViewController :: You are not logged in."); }
		
	}
	
	public function getView() {
		
		if ($this->urlArray[0] == 'profile') {
			$view = new ProfileView($this->urlArray,$this->inputArray,$this->errorArray);
			return $view->profileForm();
		}

	}
	
}

?>