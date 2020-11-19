<?php

class SupportController {

	private $urlArray;
	private $inputArray;
	private $moduleArray;
	
	public function __construct($urlArray, $inputArray, $moduleArray) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->moduleArray = $moduleArray;
		$this->errorArray = array();
		$this->messageArray = array();
		
	}
	
	public function setState() {
		
		/*
		if ($this->urlArray[0] == 'manage-menus' && $this->urlArray[1] == 'menu-item' && $this->urlArray[2] == 'create') {
			if (!empty($this->inputArray)) {
				// $this->errorArray = MenuItem::validate($this->inputArray, 'create');
				if (empty($this->errorArray)) {
					$menuItem = new MenuItem();
					foreach ($this->inputArray AS $property => $value) { if (isset($menuItem->$property)) { $menuItem->$property = $value; } }
					
					// print_r($menuItem); die();
					
					$menuItemID = MenuItem::insert($menuItem);
					header("Location: /manage-menus/");
				}
			}
		} elseif ($this->urlArray[0] == 'manage-menus' && $this->urlArray[1] == 'menu-item' && $this->urlArray[2] == 'update' && ctype_digit($this->urlArray[3])) {
			$menuItemID = $this->urlArray[3];
			if (!empty($this->inputArray)) {

				// $this->errorArray = MenuItem::validate($this->inputArray, 'update', $menuItemID);
				if (empty($this->errorArray)) {
					$menuItem = new MenuItem($menuItemID);
					foreach ($this->inputArray AS $property => $value) { if (isset($menuItem->$property)) { $menuItem->$property = $value; } }
					
					
					if (!isset($this->inputArray['menuItemPublished'])) { $menuItem->menuItemPublished = 0; }
					if (!isset($this->inputArray['menuItemDisplayAuth'])) { $menuItem->menuItemDisplayAuth = 0; }
					if (!isset($this->inputArray['menuItemDisplayAnon'])) { $menuItem->menuItemDisplayAnon = 0; }
					
					$updateConditions = array('menuItemID' => $menuItemID);
					
					// print_r($menuItem); print_r($updateConditions); die();
					
					MenuItem::update($menuItem, $updateConditions);
					header("Location: /manage-menus/");
				}
			}
		}
		*/
	}
	
	public function getErrors() {
		return $this->errorArray;
	}
	
	public function getMessages() {
		return $this->messageArray;
	}
	
}

?>