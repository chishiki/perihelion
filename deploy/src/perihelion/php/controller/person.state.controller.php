<?php

/*

CREATE TABLE `perihelion_Person` (
  `personID` int NOT NULL AUTO_INCREMENT,
  `siteID` int NOT NULL,
  `creator` int NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NULL,
  `deleted` int NOT NULL,
  `personObject` varchar(50) NOT NULL,
  `personObjectID` int NULL,
  `personLastNameEnglish` varchar(255) NULL,
  `personFirstNameEnglish` varchar(255) NULL,
  `personLastNameJapanese` varchar(255) NULL,
  `personFirstNameJapanese` varchar(255) NULL,
  `personLastNameJapaneseReading` varchar(255) NULL,
  `personFirstNameJapaneseReading` varchar(255) NULL,
  `personJobTitle` varchar(100) NULL,
  `personDivision` varchar(100) NULL,
  `personOffice` varchar(100) NULL,
  `personHomepage` varchar(255) NULL,
  `personHomeTelephone` varchar(50) NULL,
  `personMobileTelephone` varchar(50) NULL,
  `personOfficeTelephone` varchar(50) NULL,
  `personFax` varchar(50) NULL,
  `personMemo` text NULL,
  `personEmail1` varchar(100) NULL,
  `personEmail2` varchar(100) NULL,
  `personEmail3` varchar(100) NULL,
  PRIMARY KEY (`personID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

*/

final class PerihelionPersonStateController {

	private array $loc;
	private array $input;
	private array $modules;
	private array $errors;
	private array $messages;

	public function __construct(array $loc = array(), array $input = array(), array $modules = array()) {

		$this->loc = $loc;
		$this->input = $input;
		$this->modules = $modules;
		$this->errors = array();
		$this->messages = array();

	}

	public function setState() {

		$loc = $this->loc;
		$input = $this->input;
		$modules = $this->modules;

		if ($loc[0] == 'perihelion' && $loc[1] == 'person') {

			// /perihelion/person/create/
			if ($loc[2] == 'create' && isset($input['perihelion-person-create'])) {

				// $this->errors = $this->validatePerihelionPersonCreate($input);

				if (empty($this->errors)) {

					$person = new Person();
					foreach ($input AS $property => $value) { if (property_exists($person, $property)) { $person->$property = $value; } }
					Person::insert($person, true, 'perihelion_');
					$successURL = '/' . Lang::prefix() . 'perihelion/person/';
					header("Location: $successURL");

				}

			}

			// /perihelion/person/update/<personID>/
			if ($loc[2] == 'update' && is_numeric($loc[3]) && isset($input['perihelion-person-update'])) {

				// $this->errors = $this->validatePerihelionPersonUpdate($personID, $input);

				if (empty($this->errors)) {

					$person = new Person($loc[3]);
					$person->updated = date('Y-m-d H:i:s');
					foreach ($input AS $property => $value) { if (property_exists($person, $property)) { $person->$property = $value; } }
					$conditions = array('personID' => $loc[3]);
					Person::update($person, $conditions, true, false, 'perihelion_');
					$this->messages[] = Lang::getLang('perihelionPersonSuccessfullyUpdated');

				}

			}

			// /perihelion/person/delete/<personID>/
			if ($loc[2] == 'delete' && is_numeric($loc[3]) && isset($input['perihelion-person-delete'])) {

				// $this->errors = $this->validatePerihelionPersonDelete($personID, $input);

				if (empty($this->errors)) {

					$person = new Person($loc[3]);
					$person->markAsDeleted();
					$successURL = '/' . Lang::prefix() . 'perihelion/person/';
					header("Location: $successURL");

				}

			}

			if (!isset($_SESSION['perihelion']['person']['filters']) || isset($input['filter-reset'])) {
				$_SESSION['perihelion']['person']['filters'] = array();
			}
			if (isset($input['filters']) && isset($input['filter'])) {
				foreach ($input['filters'] AS $filterKey => $filterValue) {
					$_SESSION['perihelion']['person']['filters'][$filterKey] = $filterValue;
				}
			}

		}

	}

	private function validatePerihelionPersonCreate($input) {
		// if () { $this->errors['errorKey'][] == Lang::getLang('errorDescription'); }
	}

	private function validatePerihelionPersonUpdate($personID, $input) {
		// if () { $this->errors['errorKey'][] == Lang::getLang('errorDescription'); }
	}

	private function validatePerihelionPersonDelete($personID, $input) {
		// if () { $this->errors['errorKey'][] == Lang::getLang('errorDescription'); }
	}

	public function getErrors() : array {
		return $this->errors;
	}

	public function getMessages() : array {
		return $this->messages;
	}

}

?>