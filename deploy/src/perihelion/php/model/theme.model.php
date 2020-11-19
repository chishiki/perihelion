<?php

class Theme extends ORM {
	
	public $themeID;
	public $siteID;
	public $themeCreationDateTime;
	public $themeCreatorUserID;
	public $themeName;
	public $themeCss;
	public $body_color;
	public $body_backgroundcolor;

	public function __construct($themeID = null) {

		$this->themeID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->themeCreationDateTime = date('Y-m-d H:i:s');
		$this->themeCreatorUserID = $_SESSION['userID'];
		$this->themeName = '';
		$this->themeCss = '';
		$this->body_color = '#000000';
		$this->body_backgroundcolor = '#ffffff';

		if ($themeID) {
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Theme WHERE themeID = :themeID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':themeID' => $themeID));
			if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } } }
		}

	}

	public static function themeArray() {

		$siteID = $_SESSION['siteID'];
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT themeID FROM perihelion_Theme WHERE siteID = :siteID ORDER BY themeCreationDateTime ASC ";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $siteID));

		$themeArray = array();
		while ($row = $statement->fetch()) { $themeArray[] = $row['themeID']; }
		return $themeArray;

	}
	
	public static function themeID($themeName) {

		$siteID = $_SESSION['siteID'];
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT themeID FROM perihelion_Theme WHERE siteID = :siteID AND themeName = :themeName LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $siteID, ':themeName' => $themeName));

		$themeID = 0;
		if ($row = $statement->fetch()) { $themeID = $row['themeID']; }
		return $themeID;

	}
	

}

?>