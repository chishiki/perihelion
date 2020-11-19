<?php

class BlacklistIP extends ORM {

	public $ip;
	public $siteID;
	public $blockedByUserID;
	public $dateTimeBlocked;
	public $dateTimeOfBlockExpiration;
	public $attemptsSinceBlocked;
	
	public function __construct($ip = '') {
	
		if ($ip) {
		
			$core = Core::getInstance();
			$query = "SELECT * FROM perihelion_BlacklistIP WHERE ip = :ip LIMIT 1";
			$statement = $core->database->prepare($query);
			$statement->execute(array(':ip' => $ip));
			if (!$row = $statement->fetch()) { die('BlacklistIP entry does not exist.'); }
			foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } }
			
		} else {

			$this->ip = '';
			if (isset($_SESSION['siteID'])) { $this->siteID = $_SESSION['siteID']; } else { $this->siteID = 0; }
			$this->blockedByUserID = $_SESSION['userID'];
			$this->dateTimeBlocked = date('Y-m-d H:i:s');
			$this->dateTimeOfBlockExpiration =  date('Y-m-d H:i:s', strtotime('+1 year'));
			$this->attemptsSinceBlocked = 0;

		}
		
	}
	
	public static function getIpBlacklist() {
		
		$core = Core::getInstance();
		$query = "SELECT ip FROM perihelion_BlacklistIP";
		$statement = $core->database->query($query);

		$ipBlacklist = array();
		while ($row = $statement->fetch()) { $ipBlacklist[] = $row['ip']; }
		return $ipBlacklist;
		
	}

	public static function plusone($ip) {
		
		$core = Core::getInstance();
		$query = "UPDATE perihelion_BlacklistIP SET attemptsSinceBlocked = attemptsSinceBlocked + 1 WHERE ip = :ip LIMIT 1";
		$statement = $core->database->prepare($query);
		$statement->execute(array(':ip' => $ip));

	}
	
	public static function isBlacklisted($ip) {

		$core = Core::getInstance();
		$query = "SELECT ip FROM perihelion_BlacklistIP WHERE ip = :ip LIMIT 1";
		$statement = $core->database->prepare($query);
		$statement->execute(array(':ip' => $ip));
		
		$flags = array();
		while ($row = $statement->fetch()) { $flags[] = $row['ip']; }
		if (!empty($flags)) { return true; } else { return false; }
		
	}
	
}

?>