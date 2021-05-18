<?php

class CarouselPanel extends ORM {
	
	public $carouselPanelID;
	public $siteID;
	public $carouselID;
	public $imageID;
	public $submittedByUserID;
	public $submissionDateTime;
	public $carouselPanelAltEnglish;
	public $carouselPanelTitleEnglish;
	public $carouselPanelSubtitleEnglish;
	public $carouselPanelAltJapanese;
	public $carouselPanelTitleJapanese;
	public $carouselPanelSubtitleJapanese;
	public $carouselPanelPublished;
	public $carouselPanelDisplayOrder;
	public $carouselPanelUrlEnglish;
	public $carouselPanelUrlJapanese;

	public function __construct($carouselPanelID = null) {

		$this->carouselPanelID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->carouselID = 0;
		$this->imageID = 0;
		$this->submittedByUserID = $_SESSION['userID'];
		$this->submissionDateTime = date('Y-m-d H:i:s');
		$this->carouselPanelAltEnglish = '';
		$this->carouselPanelTitleEnglish = '';
		$this->carouselPanelSubtitleEnglish = '';
		$this->carouselPanelAltJapanese = '';
		$this->carouselPanelTitleJapanese = '';
		$this->carouselPanelSubtitleJapanese = '';
		$this->carouselPanelPublished = 0;
		$this->carouselPanelDisplayOrder = 0;
		$this->carouselPanelUrlEnglish = '';
		$this->carouselPanelUrlJapanese = '';
			
		if ($carouselPanelID) {

			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_CarouselPanel WHERE carouselPanelID = :carouselPanelID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':carouselPanelID' => $carouselPanelID));
			if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } } }

		}

	}

	public function alt() {
	
		if ($_SESSION['lang'] == 'ja' && $this->carouselPanelAltJapanese != '') { $alt = $this->carouselPanelAltJapanese; } else { $alt = $this->carouselPanelAltEnglish; }
		return $alt;
		
	}
	
	public function title() {
	
		if ($_SESSION['lang'] == 'ja' && $this->carouselPanelTitleJapanese != '') { $title = $this->carouselPanelTitleJapanese; } else { $title = $this->carouselPanelTitleEnglish; }
		return $title;
		
	}
	
	public function subtitle() {
	
		if ($_SESSION['lang'] == 'ja' && $this->carouselPanelSubtitleJapanese != '') { $subtitle = $this->carouselPanelSubtitleJapanese; } else { $subtitle = $this->carouselPanelSubtitleEnglish; }
		return $subtitle;
		
	}
	
	public function url() {
	
		if ($_SESSION['lang'] == 'ja' && $this->carouselPanelUrlJapanese != '') { $url = $this->carouselPanelUrlJapanese; } else { $url = $this->carouselPanelUrlEnglish; }
		return $url;
		
	}

	public static function carouselPanelArray($carouselID, $publishedOnly = false) {

		$carouselPanelArray = array();

		if (ctype_digit($carouselID)) {
			$nucleus = Nucleus::getInstance();
			$query = "SELECT carouselPanelID FROM perihelion_CarouselPanel WHERE siteID = :siteID AND carouselID = :carouselID" . ($publishedOnly?" AND carouselPanelPublished = 1 ":" ") . "ORDER BY carouselPanelDisplayOrder ASC ";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':siteID' => $_SESSION['siteID'], ':carouselID' => $carouselID));
			while ($row = $statement->fetch()) { $carouselPanelArray[] = $row['carouselPanelID']; }
		}

		return $carouselPanelArray;

	}
	

}

?>