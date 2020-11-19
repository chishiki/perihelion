<?php

class Carousel extends ORM {
	
	public $carouselID;
	public $siteID;
	public $carouselTitleEnglish;
	public $carouselSubtitleEnglish;
	public $carouselTitleJapanese;
	public $carouselSubtitleJapanese;
	public $carouselCreatedByUserID;
	public $carouselCreationDateTime;
	public $carouselObject;
	public $carouselObjectID;
	public $carouselPublished;
	public $carouselDisplayXs;
	public $carouselDisplaySm;
	public $carouselDisplayMd;
	public $carouselDisplayLg;
	public $carouselDisplayCaption;

	public function __construct($carouselID = null) {

		$this->carouselID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->carouselTitleEnglish = '';
		$this->carouselSubtitleEnglish = '';
		$this->carouselTitleJapanese = '';
		$this->carouselSubtitleJapanese = '';
		$this->carouselCreatedByUserID = $_SESSION['userID'];
		$this->carouselCreationDateTime = date('Y-m-d H:i:s');
		$this->carouselObject = '';
		$this->carouselObjectID = 0;
		$this->carouselPublished = 0;
		$this->carouselDisplayXs = 1;
		$this->carouselDisplaySm = 1;
		$this->carouselDisplayMd = 1;
		$this->carouselDisplayLg = 1;
		$this->carouselDisplayCaption = 0;
			
		if ($carouselID) {

			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Carousel WHERE carouselID = :carouselID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':carouselID' => $carouselID));
			if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } } }

		}

	}

	public function title() {
	
		$title = '';
		if ($_SESSION['lang'] == 'ja' && $this->carouselTitleJapanese != '') { $title = $this->carouselTitleJapanese; } else { $title = $this->carouselTitleEnglish; }
		return $title;
		
	}
	
	public function subtitle() {
	
		$subtitle = '';
		if ($_SESSION['lang'] == 'ja' && $this->carouselSubtitleJapanese != '') { $subtitle = $this->carouselSubtitleJapanese; } else { $subtitle = $this->carouselSubtitleEnglish; }
		return $subtitle;
		
	}

	public static function getCarouselID($carouselObject = 'index',$carouselObjectID = 0) {
	
		$siteID = $_SESSION['siteID'];
		
		$nucleus = Nucleus::getInstance();
		$query = "
			SELECT carouselID FROM perihelion_Carousel 
			WHERE siteID = :siteID
			AND carouselObject = :carouselObject
			AND carouselObjectID = :carouselObjectID
			AND carouselPublished = '1'
			LIMIT 1
		";
		$statement = $nucleus->database->prepare($query);
		$parameters = array(
			':siteID' => $siteID,
			':carouselObject' => $carouselObject,
			':carouselObjectID' => $carouselObjectID
		);
		$statement->execute($parameters);

		$carouselID = 0;
		if ($row = $statement->fetch()) { $carouselID = $row['carouselID']; }
		return $carouselID;

	}

	public static function carouselArray() {

		$siteID = $_SESSION['siteID'];
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT carouselID FROM perihelion_Carousel WHERE siteID = :siteID ORDER BY carouselCreationDateTime ASC ";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $siteID));

		$carouselArray = array();
		while ($row = $statement->fetch()) { $carouselArray[] = $row['carouselID']; }
		return $carouselArray;

	}
	

}

?>