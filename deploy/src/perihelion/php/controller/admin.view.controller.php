<?php

class AdminViewController {

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
		if ($role != 'siteAdmin') { die("You do not have view permissions for the admin module."); }

		$menu = new MenuView($this->urlArray,$this->inputArray,$this->errorArray);
		
		if ($this->urlArray[1] == 'audit') {

			$view = new AuditView($this->urlArray,$this->inputArray,$this->errorArray);

			if (!empty($_SESSION['admin']['audit']['siteID'])) { $siteID = $_SESSION['admin']['audit']['siteID']; } else { $siteID = null; }
			if (!empty($_SESSION['admin']['audit']['userID'])) { $userID = $_SESSION['admin']['audit']['userID']; } else { $userID = null; }
			if (!empty($_SESSION['admin']['audit']['auditObject'])) { $auditObject = $_SESSION['admin']['audit']['auditObject']; } else { $auditObject = null; }
			if (!empty($_SESSION['admin']['audit']['startDate'])) { $startDate = $_SESSION['admin']['audit']['startDate']; } else { $startDate = null; }
			if (!empty($_SESSION['admin']['audit']['endDate'])) { $endDate = $_SESSION['admin']['audit']['endDate']; } else { $endDate = null; }

			return $menu->adminSubMenu() . $view->auditTrail('admin', $siteID, $userID, $auditObject, $startDate, $endDate);

		}
		
		if ($this->urlArray[1] == 'uptime') {

			$view = new UptimeView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu() . $view->uptime('admin');

		}
		
		if ($this->urlArray[1] == 'not-found') {

			$view = new AdminView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu(); // . $view->audit();

		}
		
		if ($this->urlArray[1] == 'server') {

			$view = new AdminView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu() . $view->server(); // . $view->audit();

		}
		
		if ($this->urlArray[1] == 'language') {

			$view = new AdminView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu() . $view->lang();

		}
		
		if ($this->urlArray[1] == 'geography') {

			$view = new AdminView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu(); // . $view->audit();

		}
		
		if ($this->urlArray[1] == 'currency') {
	
			$view = new AdminView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu(); // . $view->audit();

		}
		
		if ($this->urlArray[1] == 'blacklist') {

			$view = new AdminView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu(); // . $view->audit();

		}

		if ($this->urlArray[1] == 'cron') {

			$view = new AdminView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu() . $view->cron();

		}

		if ($this->urlArray[1] == 'dev') {

			$view = new DevView($this->urlArray,$this->inputArray,$this->errorArray);
			return $menu->adminSubMenu() . $view->compileFileView();

		}

	}
	
}

?>