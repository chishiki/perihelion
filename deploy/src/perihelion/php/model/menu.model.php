<?php


class Menu extends ORM {

	public $menuID;
	public $siteID;
	public $menuName;
	public $menuAddedByUserID;
	public $menuAdditionDateTime;
	public $menuPublished;
	public $menuLayoutLocation;

	public function __construct($menuID = null) {
		
		$this->menuID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->menuName = 'menu';
		$this->menuAddedByUserID =  $_SESSION['userID'];
		$this->menuAdditionDateTime = date('Y-m-d H:i:s');
		$this->menuPublished = 1;
		$this->menuLayoutLocation = 'fixedTop';
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT * FROM perihelion_Menu WHERE menuID = :menuID LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':menuID' => $menuID)); 
		if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (isset($this->$key)) { $this->$key = $value; } } }

	}
	
	public function topLevelItems($publishedOnly = true) {
		
		$nucleus = Nucleus::getInstance();
		
		$query = "SELECT menuItemID FROM perihelion_MenuItem ";
		$query .= "WHERE siteID = :siteID AND menuID = :menuID AND menuItemParentID = 0 ";
		if ($publishedOnly) { $query .= "AND menuItemPublished = '1' "; }
		$query .= "ORDER BY menuItemOrder ASC";
		
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $this->siteID, ':menuID' => $this->menuID));
		
		$items = array();
		while ($row = $statement->fetch()) { $items[] = $row['menuItemID']; }
		return $items;
	}
	
}

?>