<?php

class BlacklistWord extends ORM {

	public $word;
	public $siteID;
	public $blockedByUserID;
	public $dateTimeBlocked;
	public $timesBlocked;

	private $isBlocked;
	
	public function __construct($word = '') {
		
		$this->word = $word;
		$this->siteID = $_SESSION['siteID'];
		$this->blockedByUserID = $_SESSION['userID'];
		$this->dateTimeBlocked = date('Y-m-d H:i:s');
		$this->timesBlocked = 0;
		
		$this->isBlocked = false;
		
		if ($word != '') {
		
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_BlacklistWord WHERE word = :word LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':word' => $word));
			if ($row = $statement->fetch()) {
				foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } }
				$this->isBlocked = true;
			}

		}
		
	}
	
	public function isBlocked() {
		
		if ($this->isBlocked) { return true; } else { return false; }
		
	}
	
	public static function words() {

		$nucleus = Nucleus::getInstance();
		$query = "SELECT word FROM perihelion_BlacklistWord";
		$statement = $nucleus->database->prepare($query);
		$statement->execute();
		
		$words = array();
		while ($row = $statement->fetch()) { $words[] = $row['word']; }
		return $words;

	}
	
}

?>