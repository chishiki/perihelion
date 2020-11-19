<?php

/*
CREATE TABLE IF NOT EXISTS `perihelion_Contract` (
  `contractID` int(8) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `contractCreatedByUserID` int(8) NOT NULL,
  `contractCreationDateTime` datetime NOT NULL,
  `contractTitleEnglish` varchar(255) NOT NULL,
  `contractTitleJapanese` varchar(255) NOT NULL,
  `contractTitleJapaneseReading` varchar(255) NOT NULL,
  `contractContentEnglish` text NOT NULL,
  `contractContentJapanese` text NOT NULL,
  PRIMARY KEY (`contractID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/

class Contract extends ORM {
	
	public $contractID;
	public $siteID;
	public $contractCreatedByUserID;
	public $contractCreationDateTime;
	public $contractTitleEnglish;
	public $contractTitleJapanese;
	public $contractTitleJapaneseReading;
	public $contractContentEnglish;
	public $contractContentJapanese;
	
	public function __construct($contractID = null) {

		$this->contractID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->contractCreatedByUserID = $_SESSION['userID'];
		$this->contractCreationDateTime = date("Y-m-d H:i:s");
		$this->contractTitleEnglish = '';
		$this->contractTitleJapanese = '';
		$this->contractTitleJapaneseReading = '';
		$this->contractContentEnglish = '';
		$this->contractContentJapanese = '';
		
		if ($contractID) {

			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Contract WHERE contractID = :contractID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':contractID' => $contractID));
			if ($row = $statement->fetch()) {
				foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } }
			}

		}

	}

	public function title() {
	
		$title = '';
		if ($_SESSION['lang'] == 'ja' && $this->contractTitleJapanese != '') { $title = $this->contractTitleJapanese; } else { $title = $this->contractTitleEnglish; }
		return $title;
		
	}
	
	public function content() {
	
		$content = '';
		if ($_SESSION['lang'] == 'ja' && $this->contractContentJapanese != '') { $content = $this->contractContentJapanese; } else { $content = $this->contractContentEnglish; }
		return $content;
		
	}
	
	public function isSigned($contractID, $objectClass, $objectID) {
	
		$nucleus = Nucleus::getInstance();
		
		$query = "
			SELECT * FROM perihelion_ContractSignature 
			WHERE contractID = :contractID
			AND objectClass = :objectClass
			AND objectID = :objectID
		";
		
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':contractID', $contractID, PDO::PARAM_INT);
		$statement->bindParam(':objectClass', $objectClass, PDO::PARAM_STR, 50);
		$statement->bindParam(':objectID', $objectID, PDO::PARAM_INT);
		$statement->execute();

		$isSigned = false;
		if ($row = $statement->fetch()) { $isSigned = true; }
		return $isSigned;
		
	}
	
	public function siggy($objectClass, $objectID) {
	
		$nucleus = Nucleus::getInstance();
		
		$query = "
			SELECT * FROM perihelion_ContractSignature 
			WHERE contractID = :contractID
			AND objectClass = :objectClass
			AND objectID = :objectID
		";
		
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':contractID', $this->contractID, PDO::PARAM_INT);
		$statement->bindParam(':objectClass', $objectClass, PDO::PARAM_STR, 50);
		$statement->bindParam(':objectID', $objectID, PDO::PARAM_INT);
		$statement->execute();

		$siggy = array();
		if ($row = $statement->fetch()) { $siggy = $row; }
		return $siggy;
		
	}
	
	public function inUse() {

		$nucleus = Nucleus::getInstance();
		
		$query = "SELECT * FROM perihelion_ContractSignature WHERE contractID = :contractID";
		
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':contractID', $this->contractID, PDO::PARAM_INT);
		$statement->execute();

		$inUse = false;
		if ($row = $statement->fetch()) { $inUse = true; }
		return $inUse;
		
	}
	
	public function signatories() {

		$nucleus = Nucleus::getInstance();
		
		$query = "SELECT * FROM perihelion_ContractSignature WHERE contractID = :contractID";
		
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':contractID', $this->contractID, PDO::PARAM_INT);
		$statement->execute();

		$signatories = array();
		while ($row = $statement->fetch()) { $signatories[] = $row; }
		return $signatories;
		
	}
	
	public static function contracts() {

		$nucleus = Nucleus::getInstance();
		$query = "SELECT contractID FROM perihelion_Contract WHERE siteID = :siteID ORDER BY contractCreationDateTime DESC";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $_SESSION['siteID']));

		$contracts = array();
		while ($row = $statement->fetch()) { $contracts[] = $row['contractID']; }
		return $contracts;

	}

	/*
	public function clientContracts($clientID) {

		$contracts = array();
		
		$properties = Property::ownerProperties($clientID);
		foreach ($properties AS $propertyID) {
			$property = new Property($propertyID);
			if ($property->contractID) { $contracts[] = $property->contractID; }
		}
		
		return $contracts;

	}
	*/
	
}

?>