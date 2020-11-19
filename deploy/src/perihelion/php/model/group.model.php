<?php

class Group extends ORM {

	public $groupID;
	public $siteID;
	public $groupNameEnglish;
	public $groupNameJapanese;
	public $groupCreatedByUserID;
	public $groupCreationDateTime;
	public $groupActive;
	
	public function __construct($groupID = 0) {
		
		if ($groupID) {
		
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Group WHERE groupID = :groupID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':groupID' => $groupID));
			if (!$row = $statement->fetch()) { die("Group [$groupID] does not exist."); }
			foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } }
			
		} else {

			$this->groupID = 0;
			$this->siteID = $_SESSION['siteID'];
			$this->groupNameEnglish = '';
			$this->groupNameJapanese = '';
			$this->groupCreatedByUserID = $_SESSION['userID'];
			$this->groupCreationDateTime = date('Y-m-d H:i:s');
			$this->groupActive = 0;

		}
		
	}
	
	public function groupName() {

		if ($_SESSION['lang'] == 'ja') { $projectName = $this->groupNameJapanese; } else { $projectName = $this->groupNameEnglish; }
		if ($this->groupNameJapanese && !$this->groupNameEnglish) { $projectName = $this->groupNameJapanese; }
		if ($this->groupNameEnglish && !$this->groupNameJapanese) { $projectName = $this->groupNameEnglish; }
		return $projectName;

	}
	
	public static function getGroupArray() {
		
		$siteID = $_SESSION['siteID'];
		$nucleus = Nucleus::getInstance();
		$query = "SELECT groupID FROM perihelion_Group WHERE siteID = $siteID AND groupActive != 0 ORDER BY groupNameEnglish DESC";
		$statement = $nucleus->database->query($query);
		
		$groupArray = array();
		while ($row = $statement->fetch()) { $groupArray[] = $row['groupID']; }
		return $groupArray;
		
	}
	
	public static function validate($inputArray) {
		
		$errorArray = array();
		// if ($inputArray['xxxxxxx'] == 0) { $errorArray['xxxxxxx'][] = 'xxxxxxx'; }
		return $errorArray;
		
	}
	
}

?>