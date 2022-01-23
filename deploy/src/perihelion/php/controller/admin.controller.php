<?php

final class AdminController implements StateControllerInterface {

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
		
		$role = Auth::getUserRole();
		if ($role != 'siteAdmin') { die(); }
		
	}
	
	public function setState() {

		$cronKey = Config::read('cron.key');

		if ($this->urlArray[0] == 'admin' && $this->urlArray[1] == 'cron' && isset($this->inputArray['cronKey'])) {

			if ($this->inputArray['cronKey'] != $cronKey) { $this->errorArray['cron'][] = 'The cron key is not correct.'; }

		}
		
		if ($this->urlArray[0] == 'admin' && $this->urlArray[1] == 'audit' && !empty($this->inputArray)) {

			if (isset($this->inputArray['siteID'])) { $_SESSION['admin']['audit']['siteID'] = $this->inputArray['siteID']; }
			if (isset($this->inputArray['userID'])) { $_SESSION['admin']['audit']['userID'] = $this->inputArray['userID']; }
			if (isset($this->inputArray['auditObject'])) { $_SESSION['admin']['audit']['auditObject'] = $this->inputArray['auditObject']; }
			if (isset($this->inputArray['startDate'])) { $_SESSION['admin']['audit']['startDate'] = $this->inputArray['startDate']; }
			if (isset($this->inputArray['endDate'])) { $_SESSION['admin']['audit']['endDate'] = $this->inputArray['endDate']; }

		}

		if ($this->urlArray[0] == 'admin' && $this->urlArray[1] == 'dev' && !empty($this->inputArray)) {

			if (empty($this->inputArray['keys'])) { $this->errorArray['codeGenerator'][] = 'no keys'; }

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