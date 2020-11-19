<?php

/*
CREATE TABLE IF NOT EXISTS `perihelion_ContractSignature` (
  `contractID` int(8) NOT NULL,
  `objectClass` varchar(50) NOT NULL,
  `objectID` int(8) NOT NULL,
  `signatoryUserID` int(8) NOT NULL,
  `signatureDateTime` datetime NOT NULL,
  `signatureIPAddress` varchar(39) NOT NULL,
  `signatureNameSigned` varchar(255) NOT NULL,
  PRIMARY KEY (`contractID`,`signatoryUserID`,`objectID`,`objectClass`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/

class ContractSignature extends ORM {
	
	public $contractID;
	public $objectClass;
	public $objectID;
	public $signatoryUserID;
	public $signatureDateTime;
	public $signatureIPAddress;
	public $signatureNameSigned;
	
	public function __construct($contractID = 0, $objectClass = '', $objectID = 0, $signatoryUserID = null) {

		$this->contractID = $contractID;
		$this->objectClass = $objectClass;
		$this->objectID = $objectID;
		if ($signatoryUserID) { $this->signatoryUserID = $signatoryUserID; } else { $this->signatoryUserID = $_SESSION['userID']; }
		$this->signatureDateTime = date("Y-m-d H:i:s");
		$this->signatureIPAddress = $_SERVER['REMOTE_ADDR'];
		$this->signatureNameSigned = '';
		
		if ($contractID && $clientID && $objectClass && $objectID && $signatoryUserID) {

			$nucleus = Nucleus::getInstance();
			
			$query = "
				SELECT * FROM perihelion_ContractSignature
				WHERE contractID = :contractID 
				AND objectClass = :objectClass 
				AND objectID = :objectID 
				AND signatoryUserID = :signatoryUserID
				LIMIT 1
			";
			
			$statement = $nucleus->database->prepare($query);
			$statement->bindParam(':contractID', $contractID, PDO::PARAM_INT);
			$statement->bindParam(':objectClass', $objectClass, PDO::PARAM_STR, 50);
			$statement->bindParam(':objectID', $objectID, PDO::PARAM_INT);
			$statement->bindParam(':signatoryUserID', $signatoryUserID, PDO::PARAM_INT);
			$statement->execute();
			if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } } }

		}

	}

}

?>