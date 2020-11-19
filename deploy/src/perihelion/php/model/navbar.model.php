<?php

class NavBar {

	private $siteID;
	private $menuID;
	private $urlArray;
	private $inputArray;
	private $moduleArray;

	public function __construct($urlArray, $inputArray, $moduleArray, $menuID) {
		$this->siteID = $_SESSION['siteID'];
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->moduleArray = $moduleArray;
		$this->menuID = $menuID;
	}
	
	public function getNavBarItems() {	
		
		$nucleus = Nucleus::getInstance();
		$query = "
			SELECT menuItemID FROM perihelion_MenuItem
			WHERE siteID = :siteID
			AND menuID = :menuID
			AND menuItemPublished = '1'
            AND menuItemParentID = '0'
			ORDER BY menuItemOrder ASC
		";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $this->siteID, ':menuID' => $this->menuID));
		
		$menuItems = array();
		while ($row = $statement->fetch()) { $menuItems[] = $row['menuItemID']; }
		$navBarItems = $this->navBarArray($menuItems);

		foreach ($this->moduleArray as $moduleName) {
		    $class = $moduleName . '_module';
		    $module = new $class($this->urlArray, $this->inputArray, $this->inputArray);
    		$moduleNavBarItems = $module->model();
    		$navBarItems = array_merge($navBarItems, $moduleNavBarItems['navbar']);
	    }

		return $navBarItems;
			
	}
	
	private function navBarArray($navBarItems) {
	    
	    
	    $navBarArray = array();

	    foreach ($navBarItems AS $key => $navBarItemID) {
	        $menuItem = self::navBarItem($navBarItemID);
	        if (!empty($menuItem)) { $navBarArray[] = $menuItem; }
	    }

	    return $navBarArray;

	}
	
	private function navBarItem($menuItemID) {

		$urlArray = array_filter($this->urlArray);
	    $navBarItem = array();

	    $menuItem = new MenuItem($menuItemID);
	    $menuItemUrlArray = $menuItem->getUrlAsArray();

	    if (($menuItem->menuItemDisplayAuth && Auth::isLoggedIn()) || ($menuItem->menuItemDisplayAnon && !Auth::isLoggedIn())) {

	        $navBarItem['id'] = $menuItemID;
	        $navBarItem['url'] = $menuItem->getURL();
	        $navBarItem['anchor'] = $menuItem->getAnchorText();
	        $navBarItem['disabled'] = $menuItem->menuItemDisabled;
			$navBarItem['classes'] = array();
			$navBarItem['classes'][] = $menuItem->menuItemClasses;
			$parentIsActive = false;

			// IF $urlArray matches $menuItemUrlArray array THEN make active
	        $diff = array_diff($urlArray, $menuItemUrlArray);
	        if (empty($diff) && !empty($urlArray)) { $parentIsActive = true; }

	        if ($menuItem->hasChildren()) {

	        	$navBarItem['classes'][] = 'dropdown';
				$navBarItem['children'] = array();

				$children = $menuItem->getChildren();

				foreach ($children AS $childMenuItemID) {

					$childMenuItem = new MenuItem($childMenuItemID);
				    $childMenuItemUrlArray = $childMenuItem->getUrlAsArray();

					// IF $urlArray matches $childMenuItemUrlArray THEN make parent active
					$diff = array_diff($urlArray, $childMenuItemUrlArray);
					if (empty($diff) && !empty($urlArray)) { $parentIsActive = true; }

	           		if (
	           			($childMenuItem->menuItemDisplayAuth && Auth::isLoggedIn())
						|| ($childMenuItem->menuItemDisplayAnon && !Auth::isLoggedIn())
					) { $navBarItem['children'][] = self::navBarItem($childMenuItemID); }

				}

	        }

			if ($parentIsActive) { $navBarItem['classes'][] = 'active'; }

	    }
	    
	    return $navBarItem;
	    
	}
	
}

?>