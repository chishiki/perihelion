<?php

class UserViewController {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
	}
	
	public function getView() {

		if (!Auth::isSiteManager()) { die('You must be a site manager to access this page.'); }

		$menu = new MenuView($this->urlArray,$this->inputArray,$this->errorArray);
		$h = $menu->siteSettingsNav();
		
		$view = new UserView($this->urlArray,$this->inputArray,$this->errorArray);
		if ($this->urlArray[1] == 'create') {
			$h .= $view->userForm();
		} elseif ($this->urlArray[1] == 'update' && ctype_digit($this->urlArray[2])) {
			$h .= $view->userForm($this->urlArray[2]);
		} elseif ($this->urlArray[1] == 'grant-access') {
			$h .= $view->grantAccessForm($this->urlArray[2]);
		} else {
			$h .= $view->userList($_SESSION['siteID']);
		}
		
		return $h;
		
	}
	
}

?>