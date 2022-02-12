<?php

final class NavBar {

	private int $menuID;
	private array $urlArray;
	private array $inputArray;
	private array $moduleArray;

	public function __construct(array $urlArray, array $inputArray, array $moduleArray, int $menuID) {
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
		$statement->execute(array(':siteID' => $_SESSION['siteID'], ':menuID' => $this->menuID));

		$menuItems = array();
		while ($row = $statement->fetch()) { $menuItems[] = $row['menuItemID']; }
		$navBarItems = $this->navBarArray($menuItems);

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

			if ($urlArray == $menuItemUrlArray) { $parentIsActive = true; }

			if ($menuItem->hasChildren()) {

				$navBarItem['classes'][] = 'dropdown';
				$navBarItem['children'] = array();

				$children = $menuItem->getChildren();

				foreach ($children AS $childMenuItemID) {

					$childMenuItem = new MenuItem($childMenuItemID);
					$childMenuItemUrlArray = $childMenuItem->getUrlAsArray();
					if ($urlArray == $childMenuItemUrlArray && $childMenuItem->menuItemParentID == $menuItemID) { $parentIsActive = true; }

					if (
						($childMenuItem->menuItemDisplayAuth && Auth::isLoggedIn())
						|| ($childMenuItem->menuItemDisplayAnon && !Auth::isLoggedIn())
					) {
						$navBarItem['children'][] = self::navBarItem($childMenuItemID);
					}

				}

			}

			if ($parentIsActive) { $navBarItem['classes'][] = 'active'; }

		}

		return $navBarItem;

	}

}

?>