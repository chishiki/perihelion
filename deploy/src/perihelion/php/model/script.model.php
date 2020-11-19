<?php

class Script extends ORM {

	public $scriptID;
	public $siteID;
	public $scriptCreationDateTime;
	public $scriptCreatorUserID;
	public $scriptName;
	public $scriptCode;
	public $scriptPosition; // header|footer
	public $scriptOrder;
	public $scriptEnabled;

	public function __construct($scriptID = null) {

		$this->scriptID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->scriptCreationDateTime = date('Y-m-d H:i:s');
		$this->scriptCreatorUserID = $_SESSION['userID'];
		$this->scriptName = '';
		$this->scriptCode = '';
		$this->scriptPosition = 'footer'; // header|footer
		$this->scriptOrder = 0;
		$this->scriptEnabled = 0;

		if ($scriptID) {
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Script WHERE scriptID = :scriptID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':scriptID' => $scriptID));
			if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (isset($this->$key)) { $this->$key = $value; } } }
		}

	}

	public static function scriptArray($scriptEnabled = null, $scriptPosition = null) {

		$nucleus = Nucleus::getInstance();
		$query = "SELECT scriptID FROM perihelion_Script WHERE ";
		$query .= "siteID = :siteID ";
		if (!is_null($scriptEnabled)) { $query .= "AND scriptEnabled = :scriptEnabled "; }
		if (!is_null($scriptPosition)) { $query .= "AND scriptPosition = :scriptPosition "; }
		$query .= "ORDER BY scriptOrder ASC";
		
		
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':siteID', $_SESSION['siteID']);
		if (!is_null($scriptEnabled)) { $statement->bindParam(':scriptEnabled', $scriptEnabled); }
		if (!is_null($scriptPosition)) { $statement->bindParam(':scriptPosition', $scriptPosition); }
		$statement->execute();

		$scriptArray = array();
		while ($row = $statement->fetch()) { $scriptArray[] = $row['scriptID']; }
		return $scriptArray;

	}

}

?>