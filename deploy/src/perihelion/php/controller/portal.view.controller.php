<?php

class PortalViewController {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	private $messageArray;
	
	public function __construct($urlArray, $inputArray, $errorArray, $messageArray) {

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		$this->messageArray = $messageArray;

	}
	
	public function getView() {
		
		if ($this->urlArray[1] == 'portal') {

		
			$menu = new MenuView($this->urlArray,$this->inputArray,$this->errorArray);
			$view = new PortalView($this->urlArray,$this->inputArray,$this->errorArray);
			
			switch ($this->urlArray[2]) {
				
				case ('properties'):
					return $menu->portalSubMenu() . $view->portalAvailableProperties();
					break;
				
				case ('areas'):
					return $menu->portalSubMenu() . $view->portalAvailableAreas();
					break;
					
				case ('types'):
					return $menu->portalSubMenu() . $view->portalAvailableTypes();
					break;
					
				default:
					return $menu->portalSubMenu() . $view->portalAvailableProperties();
					
			}

		}
		
	}
	
}

?>