<?php

/*
CREATE TABLE IF NOT EXISTS `perihelion_Contact` (
  `contactID` int(12) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `contactUserID` int(12) NOT NULL,
  `contactDateTime` datetime NOT NULL,
  `contactIP` varchar(40) NOT NULL,
  `contactName` varchar(255) NOT NULL,
  `contactEmail` varchar(255) NOT NULL,
  `contactContent` text NOT NULL,
  PRIMARY KEY (`contactID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/

class Contact extends ORM {
	
	public $contactID;
	public $siteID;
	public $contactUserID; // if logged in
	public $contactDateTime;
	public $contactIP;
	public $contactName;
	public $contactNameLast;
	public $contactEmail;
	public $contactContent;
	
	public function __construct($contactID = null) {

		$this->contactID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->contactUserID = $_SESSION['userID'];
		$this->contactDateTime = date("Y-m-d H:i:s");
		$this->contactIP = $_SERVER['REMOTE_ADDR'];
		$this->contactName = '';
		$this->contactNameLast = '';
		$this->contactEmail = '';
		$this->contactContent = '';
		
		if ($contactID) {

			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Contact WHERE contactID = :contactID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':contactID' => $contactID));
			if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } } }

		}

	}
	
	public static function contactFormValidate($contact) {
		
	}

	public static function contactArray() {

		$siteID = $_SESSION['siteID'];
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT contactID FROM perihelion_Contact WHERE siteID = :siteID ORDER BY contactID DESC ";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $siteID));

		$contactArray = array();
		while ($row = $statement->fetch()) { $contactArray[] = $row['contactID']; }
		return $contactArray;

	}
	
}

?>