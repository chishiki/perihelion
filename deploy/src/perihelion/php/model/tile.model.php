<?php

class Tile {

	public $tileID;
	public $siteID;
	public $tileKey;
	public $tileKeyID;
	public $tilePublished;
	public $tileContainer;
	public $tileRow;
	public $tileCol;
	public $tileColSM;
	public $tileColMD;
	public $tileColLG;
	public $tileColXL;
	public $tileProminence;
	public $tileClass;
	public $tileAuthDisplay;
	
	public function __construct($tileID = null) {

	    $this->tileID = 0;
	    $this->siteID = $_SESSION['siteID'];
	    $this->tileKey = '';
	    $this->tileKeyID = 0;
	    $this->tilePublished = 0;
	    $this->tileContainer = '';
	    $this->tileRow = 0;
	    $this->tileCol = 0;
	    $this->tileColSM = 0;
	    $this->tileColMD = 0;
	    $this->tileColLG = 0;
	    $this->tileColXL = 0;
	    $this->tileProminence = 0;
	    $this->tileClass = '';
	    $this->tileAuthDisplay = '';
	    
	    if ($tileID) {

			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Tile WHERE tileID = :tileID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':tileID' => $tileID));
			if ($row = $statement->fetch()) {
			    foreach ($row AS $key => $value) { if (isset($this->$key)) { $this->$key = $value; } }
			}

		}

	}

	public static function tileArray() {

		$nucleus = Nucleus::getInstance();
		$query = "SELECT tileID FROM perihelion_Tile WHERE siteID = :siteID ORDER BY tileID DESC ";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $_SESSION['siteID']));

		$tileArray = array();
		while ($row = $statement->fetch()) { $tileArray[] = $row['tileID']; }
		return $tileArray;

	}
	

}

?>