<?php

class LanguageException extends ORM {

	public $langKey;
	public $siteID;
	public $enLangException;
	public $jaLangException;


	public function __construct($langKey,$siteID) {
		
		$this->langKey = $langKey;
		$this->siteID = $siteID;
		$this->enLangException = '';
		$this->jaLangException = '';
			
		$nucleus = Nucleus::getInstance();
		$query = "SELECT * FROM perihelion_LanguageException WHERE langKey = :langKey AND siteID = :siteID LIMIT 1";

		$statement = $nucleus->database->prepare($query);
		$statement->bindparam(':langKey', $langKey, PDO::PARAM_STR);
		$statement->bindparam(':siteID', $siteID, PDO::PARAM_INT);
		$statement->execute(array(':langKey' => $langKey, ':siteID' => $siteID));
		if ($row = $statement->fetch()) {
		     foreach ($row AS $key => $value) {
		         if (isset($this->$key) && $value != '') { $this->$key = $value; }
		     }
		}

	}

	public function exception() {

		$exception = null;
		if ($_SESSION['lang'] == 'ja' && $this->jaLangException != '') { $exception = $this->jaLangException; }
		if ($_SESSION['lang'] == 'en' && $this->enLangException != '') { $exception = $this->enLangException; }
		return $exception;
		
	}
	
}

?>