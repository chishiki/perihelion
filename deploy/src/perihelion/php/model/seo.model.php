<?php

class SEO extends ORM {
	
	public $seoID;
	public $siteID;
	public $seoURL;
	public $systemURL;
	public $seoSetByUserID;
	public $seoSetDateTime;
	public $seoUrlArray0;
	public $seoUrlArray1;
	public $seoUrlArray2;
	public $seoUrlArray3;
	public $seoUrlArray4;
	public $seoUrlArray5;
	public $seoTitleEnglish;
	public $seoDescriptionEnglish;
	public $seoKeywordsEnglish;
	public $seoTitleJapanese;
	public $seoDescriptionJapanese;
	public $seoKeywordsJapanese;
	public $seoRobotsTxtIndex;
	public $seoRobotsTxtFollow;
	public $seoCanonicalUrl;
	
	public function __construct($seoID = null) {

		$this->seoID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->seoURL = '';
		$this->systemURL = '';
		$this->seoSetByUserID = $_SESSION['userID'];
		$this->seoSetDateTime = date('Y-m-d H:i:s');
		$this->seoUrlArray0 = '';
		$this->seoUrlArray1 = '';
		$this->seoUrlArray2 = '';
		$this->seoUrlArray3 = '';
		$this->seoUrlArray4 = '';
		$this->seoUrlArray5 = '';
		$this->seoTitleEnglish = '';
		$this->seoDescriptionEnglish = '';
		$this->seoKeywordsEnglish = '';
		$this->seoTitleJapanese = '';
		$this->seoDescriptionJapanese = '';
		$this->seoKeywordsJapanese = '';
		$this->seoRobotsTxtIndex = 0;
		$this->seoRobotsTxtFollow = 0;
		$this->seoCanonicalUrl = '';
			
		if ($seoID) {

			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_SEO WHERE seoID = :seoID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':seoID' => $seoID));
			if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } } }

		}
	}

	public function title() {
	
		if ($_SESSION['lang'] == 'ja' && $this->seoTitleJapanese != '') { $title = $this->seoTitleJapanese; } else { $title = $this->seoTitleEnglish; }
		return $title;
		
	}
	
	public function description() {
	
		if ($_SESSION['lang'] == 'ja' && $this->seoDescriptionJapanese != '') { $description = $this->seoDescriptionJapanese; } else { $description = $this->seoDescriptionEnglish; }
		return $description;
		
	}
	
	public function keywords() {
	
		if ($_SESSION['lang'] == 'ja' && $this->seoKeywordsJapanese != '') { $keywords = $this->seoKeywordsJapanese; } else { $keywords = $this->seoKeywordsEnglish; }
		return $keywords;
		
	}

	public static function seoArray() {

		$siteID = $_SESSION['siteID'];
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT seoID FROM perihelion_SEO WHERE siteID = :siteID ORDER BY seoUrlArray0 ASC, seoUrlArray1 ASC, seoUrlArray2 ASC, seoUrlArray3 ASC, seoUrlArray4 ASC, seoUrlArray5 ASC";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $siteID));

		$seoArray = array();
		while ($row = $statement->fetch()) { $seoArray[] = $row['seoID']; }
		return $seoArray;

	}
	
	public static function getSeoID($url) {
		
		$siteID = $_SESSION['siteID'];
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT seoID FROM perihelion_SEO WHERE siteID = :siteID AND (seoURL = :seoURL OR systemURL = :systemURL) LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $siteID, ':seoURL' => $url, ':systemURL' => $url));

		$seoID = 0;
		if ($row = $statement->fetch()) { $seoID = $row['seoID']; }
		return $seoID;
		
	}

	public static function seoThisUrlArray($urlArray = array()) {


		if (!empty($urlArray)) {
			
			$siteID = $_SESSION['siteID'];
			$seoURL = rtrim(join('/', $urlArray),'/');
			
			$nucleus = Nucleus::getInstance();
			$query = 'SELECT * FROM perihelion_SEO WHERE siteID = :siteID AND seoURL = :seoURL LIMIT 1';
			$statement = $nucleus->database->prepare($query);
			$statement->bindParam(':siteID', $siteID, PDO::PARAM_INT);
			$statement->bindParam(':seoURL', $seoURL, PDO::PARAM_STR);
			$statement->execute();
			
			if ($row = $statement->fetch()) {
				unset($urlArray);
				$urlArray[0] = $row['seoUrlArray0'];
				$urlArray[1] = $row['seoUrlArray1'];
				$urlArray[2] = $row['seoUrlArray2'];
				$urlArray[3] = $row['seoUrlArray3'];
				$urlArray[4] = $row['seoUrlArray4'];
				$urlArray[5] = $row['seoUrlArray5'];
			}
			
		}
		
		return $urlArray;

	}

}

?>