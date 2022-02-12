<?php


class MenuItem extends ORM {

	public $menuItemID;
	public $siteID;
	public $menuID;
	public $menuItemParentID;
	public $menuItemAddedByUserID;
	public $menuItemAdditionDateTime;
	public $menuItemURL;
	public $menuItemAnchorTextEnglish;
	public $menuItemAnchorTextJapanese;
	public $menuItemPublished;
	public $menuItemOrder;
	public $menuItemDisplayAuth;
	public $menuItemDisplayAnon;
	public $menuItemDisabled;
	public $menuItemClasses;

	public function __construct($menuItemID = 0) {
		
		if ($menuItemID) {
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_MenuItem WHERE menuItemID = :menuItemID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':menuItemID' => $menuItemID));
			if (!$row = $statement->fetch()) { 
				die("MenuItem [$menuItemID] does not exist.");
			}
			foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } }
		} else {
			$site = new Site($_SESSION['siteID']);
			$this->menuItemID = 0;
			$this->siteID = $site->siteID;
			$this->menuID = $site->siteNavMenuID;
			$this->menuItemParentID = 0;
			$this->menuItemAddedByUserID = $_SESSION['userID'];
			$this->menuItemAdditionDateTime = date('Y-m-d H:i:s');
			$this->menuItemURL = '';
			$this->menuItemAnchorTextEnglish = '';
			$this->menuItemAnchorTextJapanese = '';
			$this->menuItemPublished = 0;
			$this->menuItemOrder = 0;
			$this->menuItemDisplayAuth = 0;
			$this->menuItemDisplayAnon = 0;
			$this->menuItemDisabled = 0;
			$this->menuItemClasses = '';
		}

	}
	
	public function getURL() {

	    if ($this->menuItemURL == ''||$this->menuItemURL == '/') {
			$url = '/';
			if ($_SESSION['lang']!='en') { $url = "/" . $_SESSION['lang'] . $url; }
		} elseif (strpos($this->menuItemURL, 'http') === 0) {
			$url = $this->menuItemURL;
		} elseif ($this->hasChildren() || $this->menuItemURL == '#') {
			$url = '#';
		} else {
			$url = '/' . $this->menuItemURL . '/';
			if ($_SESSION['lang']!='en') { $url = "/" . $_SESSION['lang'] . $url; }
		}
		return $url;	
	
	}

	public function getUrlAsArray() {

		$thisUrlArray = array();
		if (
			!in_array($this->menuItemURL,array('', '/'))
			&& strpos($this->menuItemURL, 'http') !== 0
		) {
			$thisUrlArray = explode('/', $this->menuItemURL);
		}
		return $thisUrlArray;

	}

	public function getAnchorText() {
		
		if ($_SESSION['lang'] == 'ja') { $anchorText = $this->menuItemAnchorTextJapanese; } else { $anchorText = $this->menuItemAnchorTextEnglish; }
		if ($this->menuItemAnchorTextJapanese && !$this->menuItemAnchorTextEnglish) { $anchorText = $this->menuItemAnchorTextJapanese; }
		if ($this->menuItemAnchorTextEnglish && !$this->menuItemAnchorTextJapanese) { $anchorText = $this->menuItemAnchorTextEnglish; }
		return $anchorText;
		
	}
	
	public function getChildren($publishedOnly = true) {	

		$nucleus = Nucleus::getInstance();
		$query = "SELECT menuItemID FROM perihelion_MenuItem ";
		$query .= "WHERE siteID = :siteID ";
		$query .= "AND menuID = :menuID ";
		$query .= "AND menuItemParentID = :menuItemID ";
		if ($publishedOnly) { $query .= "AND menuItemPublished = '1' "; }
		$query .= "ORDER BY menuItemOrder ASC";

		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $this->siteID, ':menuID' => $this->menuID, ':menuItemID' => $this->menuItemID));
		
		$children = array();
		while ($row = $statement->fetch()) { $children[] = $row['menuItemID']; }
		return $children;
			
	}
	
	public function hasChildren($publishedOnly = true) {
		
	    $children = $this->getChildren($publishedOnly);
		if (!empty($children)) { return true; } else { return false; }

	}
	
}

?>