<?php

class BlacklistDomain extends ORM {

	public $domain;
	public $siteID;
	public $blockedByUserID;
	public $dateTimeBlocked;
	public $dateTimeOfBlockExpiration;
	public $attemptsSinceBlocked;
	
	public function __construct($domain = '') {
	
		if ($domain) {
		
			$core = Core::getInstance();
			$query = "SELECT * FROM perihelion_BlacklistDomain WHERE domain = :domain LIMIT 1";
			$statement = $core->database->prepare($query);
			$statement->execute(array(':domain' => $domain));
			if (!$row = $statement->fetch()) { die('BlacklistDomain entry does not exist.'); }
			foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } }
			
		} else {

			$this->domain = '';
			if (isset($_SESSION['siteID'])) { $this->siteID = $_SESSION['siteID']; } else { $this->siteID = 0; }
			$this->blockedByUserID = $_SESSION['userID'];
			$this->dateTimeBlocked = date('Y-m-d H:i:s');
			$this->dateTimeOfBlockExpiration =  date('Y-m-d H:i:s', strtotime('+1 year'));
			$this->attemptsSinceBlocked = 0;

		}
		
	}
	
	public static function getDomainBlacklist() {
		
		$core = Core::getInstance();
		$query = "SELECT domain FROM perihelion_BlacklistDomain";
		$statement = $core->database->query($query);

		$domainBlacklist = array();
		while ($row = $statement->fetch()) { $domainBlacklist[] = str_replace('\.','.',$row['domain']); }
		return $domainBlacklist;
		
	}
	
	public static function validate($inputArray) {
		
		$errorArray = array();
		// if ($inputArray['xxxxxxx'] == 0) { $errorArray['xxxxxxx'][] = 'xxxxxxx blah blah blah.'; }
		return $errorArray;
		
	}
	
	public static function plusone($domain) {
		
		$core = Core::getInstance();
		$query = "UPDATE perihelion_BlacklistDomain SET attemptsSinceBlocked = attemptsSinceBlocked + 1 WHERE domain = :domain LIMIT 1";
		$statement = $core->database->prepare($query);
		$statement->execute(array(':domain' => $domain));

	}
	
}

?>