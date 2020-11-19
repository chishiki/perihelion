<?php

class Note extends ORM {

	public $noteID;
	public $siteID;
	public $noteSubmittedByUserID;
	public $noteSubmissionDateTime;
	public $noteObject;
	public $noteObjectID;
	public $noteContent;
	public $displayToOwner;
	
	public function __construct($noteID = null) {
		
		$this->noteID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->noteSubmittedByUserID = $_SESSION['userID'];
		$this->noteSubmissionDateTime = date('Y-m-d H:i:s');
		$this->noteObject = '';
		$this->noteObjectID = 0;
		$this->noteContent = '';
		$this->displayToOwner = 0;
			
		if ($noteID) {
		
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Note WHERE noteID = :noteID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':noteID' => $noteID));
			if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (isset($this->$key)) { $this->$key = $value; } } }
			
		}
		
	}
	
	public static function notes($noteObject, $noteObjectID, $displayToOwnerOnly = false) {

		$nucleus = Nucleus::getInstance();
		$query = "SELECT noteID FROM perihelion_Note WHERE siteID = :siteID AND noteObject = :noteObject AND noteObjectID = :noteObjectID" . ($displayToOwnerOnly?" AND displayToOwner = 1":"") . " ORDER BY noteSubmissionDateTime DESC";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $_SESSION['siteID'], ':noteObject' => $noteObject, ':noteObjectID' => $noteObjectID));
		$notes = array();
		while ($row = $statement->fetch()) { $notes[] = $row['noteID']; }
		return $notes;
		
	}
	
}

?>