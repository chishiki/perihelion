<?php

class GeoArea extends ORM {
	
	public $geoAreaKey;
	public $geoAreaEnglishName;
	public $geoAreaEnglishDescription;
	public $geoAreaJapaneseName;
	public $geoAreaJapaneseNameReading;
	public $geoAreaJapaneseDescription;
	public $geoQueryAddress;
	public $geoAreaCoordinates;
	public $geoAreaCenter;
	public $geoAreaParentAreaKey;
	
	public function __construct($geoAreaKey = null) {

		$this->geoAreaKey = '';
		$this->geoAreaEnglishName = '';
		$this->geoAreaEnglishDescription = '';
		$this->geoAreaJapaneseName = '';
		$this->geoAreaJapaneseNameReading = '';
		$this->geoAreaJapaneseDescription = '';
		$this->geoQueryAddress = '';
		$this->geoAreaCoordinates = '';
		$this->geoAreaCenter = '';
		$this->geoAreaParentAreaKey = '';
			
		if ($geoAreaKey) {
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_GeoArea WHERE geoAreaKey = :geoAreaKey LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':geoAreaKey' => $geoAreaKey));
			if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (isset($this->$key)) { $this->$key = $value; } } }
		}
		
	}

	public function name() {

		$name = $this->geoAreaEnglishName;
		if ($_SESSION['lang'] == 'ja' && !empty($this->geoAreaJapaneseName)) { $name = $this->geoAreaJapaneseName; };
		return $name;

	}

	public function description() {

		$description = $this->geoAreaEnglishDescription;
		if ($_SESSION['lang'] == 'ja' && !empty($this->geoAreaJapaneseDescription)) { $description = $this->geoAreaJapaneseDescription; };
		return $description;

	}

	public static function geoAreas($geoAreaParentAreaKey = null) {

		$nucleus = Nucleus::getInstance();
		$query = "SELECT geoAreaKey FROM perihelion_GeoArea" . ($geoAreaParentAreaKey?" WHERE geoAreaParentAreaKey = :geoAreaParentAreaKey":"") . " ORDER BY geoAreaEnglishName ASC";
		$statement = $nucleus->database->prepare($query);
		if ($geoAreaParentAreaKey) { $statement->bindParam(':geoAreaParentAreaKey',$geoAreaParentAreaKey); }
		$statement->execute();

		$geoAreas = array();
		while ($row = $statement->fetch()) { $geoAreas[] = $row['geoAreaKey']; }
		return $geoAreas;

	}

}

?>