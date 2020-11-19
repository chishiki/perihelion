<?php

class Link extends ORM {

	public $linkID;
	public $siteID;
	public $linkCreatedByUserID;
	public $linkCreationDateTime;
	public $linkUrlEnglish;
	public $linkUrlJapanese;
	public $linkAnchorTextEnglish;
	public $linkAnchorTextJapanese;
	public $linkObject;
	public $linkObjectID;
	public $linkPublished;
	public $linkDisplayOrder;
	public $linkClickCount;
	public $linkShorty;
	public $linkPortalPrimary;

	public function __construct($linkID = 0) {
	
		$this->linkID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->linkCreatedByUserID = $_SESSION['userID'];
		$this->linkCreationDateTime = date('Y-m-d H:i:s');
		$this->linkUrlEnglish = '';
		$this->linkUrlJapanese = '';
		$this->linkAnchorTextEnglish = '';
		$this->linkAnchorTextJapanese = '';
		$this->linkObject = '';
		$this->linkObjectID = 0;
		$this->linkPublished = 0;
		$this->linkDisplayOrder = 0;
		$this->linkClickCount = 0;
		$this->linkShorty = sprintf('%06x', mt_rand(0, 0xFFFFFF));
		$this->linkPortalPrimary = 0;
		
		if ($linkID) {
		
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Link WHERE linkID = :linkID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':linkID' => $linkID));
			if ($row = $statement->fetch()) { 
				foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } }
			}
			
		}
		
	}

	public function url() {
		
		if ($_SESSION['lang'] == 'ja' && $this->linkUrlJapanese != '') { $url = $this->linkUrlJapanese; } else { $url = $this->linkUrlEnglish; }
		return $url;
		
	}
	
	public function anchor() {
		
		if ($_SESSION['lang'] == 'ja' && $this->linkAnchorTextJapanese != '') { $anchor = $this->linkAnchorTextJapanese; } else { $anchor = $this->linkAnchorTextEnglish; }
		return $anchor;
		
	}

	public function setLinkPortalPrimary() {
		
		$this->linkPortalPrimary = 1;
		$conditions = array('linkID' => $this->linkID);
		self::update($this,$conditions);
		
		$links = self::getObjectLinkArray($this->linkObject, $this->linkObjectID);
		foreach ($links AS $linkID) {
			
			if ($this->linkID != $linkID) {
				
				$link = new self($linkID);
				$link->linkPortalPrimary = 0;
				$conditions = array('linkID' => $linkID);
				self::update($link,$conditions);
				
			}
			
		}
		
		
	}
	
	public static function getObjectLinkArray($linkObject, $linkObjectID, $limit = null) {
		
		// don't trust limit
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT linkID FROM perihelion_Link WHERE siteID = :siteID AND linkObject = :linkObject AND linkObjectID = :linkObjectID ORDER BY linkDisplayOrder" . ($limit?" LIMIT $limit":"");
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $_SESSION['siteID'], ':linkObject' => $linkObject, ':linkObjectID' => $linkObjectID));
		$objectLinkArray = array();
		while ($row = $statement->fetch()) { $objectLinkArray[] = $row['linkID']; }
		return $objectLinkArray;
		
	}
	
	public static function getLinkPortalPrimary($linkObject, $linkObjectID) {
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT linkID FROM perihelion_Link WHERE linkObject = :linkObject AND linkObjectID = :linkObjectID AND linkPortalPrimary = 1 LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':linkObject' => $linkObject, ':linkObjectID' => $linkObjectID));
		
		$linkPortalProperty = null;
		if ($row = $statement->fetch()) {
			$link = new Link($row['linkID']);
			$linkPortalProperty = $link->url();
		}
		return $linkPortalProperty;
		
	}
	
	
}

?>