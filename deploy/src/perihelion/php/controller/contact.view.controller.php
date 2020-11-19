<?php

class ContactViewController {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
	}
	
	public function getView() {
		

		$role = Auth::getUserRole();
		$staffUserRoleArray = array('siteStaff','siteAccountant','siteManager','siteAdmin');
		
		if ($this->urlArray[1] == 'thank-you') { // check role explicitly here!
			
			$view = new ContactView($this->urlArray,$this->inputArray,$this->errorArray);
			return $view->contactThankYou();
			
		} else {

			$view = new ContactView($this->urlArray,$this->inputArray,$this->errorArray);
			return $view->contactForm();
			
		}
		
	}
	
}

?>