<?php

class Project extends ORM {

	public $projectID;
	public $projectURL;
	public $projectNameEnglish;
	public $projectNameJapanese;
	public $projectNameJapaneseReading;
	public $projectDescriptionEnglish;
	public $projectDescriptionJapanese;
	public $projectCreatedByUserID;
	public $projectCreationDateTime;
	public $projectIsPublic;
	public $siteID;
	public $groupID;
	
	public function __construct($projectID = 0) {
		
		if ($projectID) {
		
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Project WHERE projectID = :projectID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':projectID' => $projectID));
			if (!$row = $statement->fetch()) { die("Project [$projectID] does not exist."); }
			foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } }
			
		} else {

			$this->projectID = 0;
			$this->projectURL = '';
			$this->projectNameEnglish = '';
			$this->projectNameJapanese = '';
			$this->projectNameJapaneseReading = '';
			$this->projectDescriptionEnglish = '';
			$this->projectDescriptionJapanese = '';
			$this->projectCreatedByUserID = $_SESSION['userID'];
			$this->projectCreationDateTime = date('Y-m-d H:i:s');
			$this->projectIsPublic = 0;
			$this->siteID = $_SESSION['siteID'];
			$this->groupID = 0;
		
		}
		
	}
	
	public function projectName() {

		if ($_SESSION['lang'] == 'ja') { $projectName = $this->projectNameJapanese; } else { $projectName = $this->projectNameEnglish; }
		if ($this->projectNameJapanese && !$this->projectNameEnglish) { $projectName = $this->projectNameJapanese; }
		if ($this->projectNameEnglish && !$this->projectNameJapanese) { $projectName = $this->projectNameEnglish; }
		return $projectName;

	}
	
	public function projectDescription() {

		if ($_SESSION['lang'] == 'ja') { $projectDescription = $this->projectDescriptionJapanese; } else { $projectDescription = $this->projectDescriptionEnglish; }
		if ($this->projectDescriptionJapanese && !$this->projectDescriptionEnglish) { $projectDescription = $this->projectDescriptionJapanese; }
		if ($this->projectDescriptionEnglish && !$this->projectDescriptionJapanese) { $projectDescription = $this->projectDescriptionEnglish; }
		return $projectDescription;

	}
	
	public static function getProjectID($projectURL) {
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT projectID FROM perihelion_Project WHERE projectURL = :projectURL LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':projectURL' => $projectURL));
		if ($row = $statement->fetch()) { return $row['projectID']; } else { die('error => project::getProjectID() // please notify support@zenidev.com'); }
		
	}
	
	public static function getProjectArray($groups = array()) {
		
		$siteID = $_SESSION['siteID'];
		$nucleus = Nucleus::getInstance();
		
		if (empty($groups)) {
			$query = "SELECT projectID FROM perihelion_Project WHERE siteID = $siteID ORDER BY projectID DESC";
		} else {
			$inClause = join(",",$groups);
			$query = "SELECT projectID FROM perihelion_Project WHERE groupID IN ($inClause) AND siteID = $siteID ORDER BY projectID DESC";
		}
		
		$statement = $nucleus->database->query($query);
		
		$projectArray = array();
		while ($row = $statement->fetch()) { $projectArray[] = $row['projectID']; }
		return $projectArray;
		
	}
	
	public static function validate($inputArray) {
		
		$errorArray = array();
		// if ($inputArray['xxxxxxx'] == 0) { $errorArray['xxxxxxx'][] = 'xxxxxxx'; }
		return $errorArray;
		
	}
	
	public static function projectExists($projectIdentifier) {
		
		$siteID = $_SESSION['siteID'];
		$nucleus = Nucleus::getInstance();
		$query = "SELECT projectID FROM perihelion_Project WHERE siteID = $siteID AND (projectID = :projectIdentifier OR projectURL = :projectIdentifier) LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':projectIdentifier' => $projectIdentifier));
		if ($row = $statement->fetch()) { return true; } else { return false; }

	}

}

?>