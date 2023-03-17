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
  `personAcceptsEmail` int NOT NULL,
  `personGender` varchar(6) NOT NULL,
  `personBirthday` date DEFAULT NULL,
  `personAgeGroup` varchar(20) DEFAULT NULL,
  `personGuardian` varchar(255) DEFAULT NULL,
  `personActive` int NOT NULL,
  PRIMARY KEY (`personID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

*/

final class Person extends ORM {

	public ?int $personID;
	public int $siteID;
	public int $creator;
	public string $created;
	public ?string $updated;
	public int $deleted;
	public string $personObject;
	public ?int $personObjectID;
	public ?string $personLastNameEnglish;
	public ?string $personFirstNameEnglish;
	public ?string $personLastNameJapanese;
	public ?string $personFirstNameJapanese;
	public ?string $personLastNameJapaneseReading;
	public ?string $personFirstNameJapaneseReading;
	public ?string $personJobTitle;
	public ?string $personDivision;
	public ?string $personOffice;
	public ?string $personHomepage;
	public ?string $personHomeTelephone;
	public ?string $personMobileTelephone;
	public ?string $personOfficeTelephone;
	public ?string $personFax;
	public ?string $personMemo;
	public ?string $personEmail1;
	public ?string $personEmail2;
	public ?string $personEmail3;
	public ?int $personAcceptsEmail;
	public ?string $personGender;
	public ?string $personBirthday;
	public ?string $personAgeGroup;
	public ?string $personGuardian;
	public ?int $personActive;

	public function __construct($personID = null) {

		$dt = new DateTime();

		$this->personID = null;
		$this->siteID = $_SESSION['siteID'];
		$this->creator = $_SESSION['userID'];
		$this->created = $dt->format('Y-m-d H:i:s');
		$this->updated = null;
		$this->deleted = 0;
		$this->personObject = '';
		$this->personObjectID = null;
		$this->personLastNameEnglish = null;
		$this->personFirstNameEnglish = null;
		$this->personLastNameJapanese = null;
		$this->personFirstNameJapanese = null;
		$this->personLastNameJapaneseReading = null;
		$this->personFirstNameJapaneseReading = null;
		$this->personJobTitle = null;
		$this->personDivision = null;
		$this->personOffice = null;
		$this->personHomepage = null;
		$this->personHomeTelephone = null;
		$this->personMobileTelephone = null;
		$this->personOfficeTelephone = null;
		$this->personFax = null;
		$this->personMemo = null;
		$this->personEmail1 = null;
		$this->personEmail2 = null;
		$this->personEmail3 = null;
		$this->personAcceptsEmail = null;
		$this->personGender = null;
		$this->personBirthday = null;
		$this->personAgeGroup = null;
		$this->personGuardian = null;
		$this->personActive = null;

		if (!is_null($personID)) {

			$nucleus = Nucleus::getInstance();

			$whereClause = array();
			$whereClause[] = 'siteID = :siteID';
			$whereClause[] = 'deleted = 0';
			$whereClause[] = 'personID = :personID';

			$query = 'SELECT * FROM perihelion_Person WHERE ' . implode(' AND ', $whereClause) . ' LIMIT 1';
			$statement = $nucleus->database->prepare($query);
			$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
			$statement->bindParam(':personID', $personID, PDO::PARAM_INT);
			$statement->execute();

			if ($row = $statement->fetch()) {
				foreach ($row AS $key => $value) { if (property_exists($this, $key)) { $this->$key = $value; } }
			}

		}

	}

	public function name(string $lang = null) :?string {

		$japaneseNames = array();
		if (!is_null($this->personLastNameJapanese) && $this->personLastNameJapanese != '') { $japaneseNames[] = $this->personLastNameJapanese; }
		if (!is_null($this->personFirstNameJapanese) && $this->personFirstNameJapanese != '') { $japaneseNames[] = $this->personFirstNameJapanese; }

		$englishNames = array();
		if (!is_null($this->personLastNameEnglish) && $this->personLastNameEnglish != '') { $englishNames[] = $this->personLastNameEnglish; }
		if (!is_null($this->personFirstNameEnglish) && $this->personFirstNameEnglish != '') { $englishNames[] = $this->personFirstNameEnglish; }

		if (!is_null($lang)) {
			if ($lang == 'en') { return join(', ', $englishNames); }
			if ($lang == 'ja') { return join(' ', $japaneseNames); }
		} elseif ($_SESSION['lang'] == 'ja' && (!empty($this->personLastNameJapanese) || !empty($this->personFirstNameJapanese))) {
			return join(' ', $japaneseNames);
		} else {
			if (!empty($englishNames)) {
				return join(', ', $englishNames);
			} elseif (!empty($japaneseNames)) {
				return join(' ', $japaneseNames);
			}
		}

		return null;

	}

	public function firstName(string $lang = null) :?string {

		if (!is_null($lang)) {
			if ($lang == 'en') { return $this->personFirstNameEnglish; }
			if ($lang == 'ja') { return $this->personFirstNameJapanese; }
		} elseif ($_SESSION['lang'] == 'ja' && !empty($this->personFirstNameJapanese)) {
			return $this->personFirstNameJapanese;
		} else {
			if (!empty($this->personFirstNameEnglish)) {
				return $this->personFirstNameEnglish;
			} elseif (!empty($this->personFirstNameJapanese)) {
				return $this->personFirstNameJapanese;
			}
		}

		return null;

	}

	public function lastName(string $lang = null) :?string {

		if (!is_null($lang)) {
			if ($lang == 'en') { return $this->personLastNameEnglish; }
			if ($lang == 'ja') { return $this->personLastNameJapanese; }
		} elseif ($_SESSION['lang'] == 'ja' && !empty($this->personLastNameJapanese)) {
			return $this->personLastNameJapanese;
		} else {
			if (!empty($this->personLastNameEnglish)) {
				return $this->personLastNameEnglish;
			} elseif (!empty($this->personLastNameJapanese)) {
				return $this->personLastNameJapanese;
			}
		}

		return null;

	}

	public function markAsDeleted() {

		$dt = new DateTime();
		$this->updated = $dt->format('Y-m-d H:i:s');
		$this->deleted = 1;
		$conditions = array('personID' => $this->personID);
		self::update($this, $conditions, true, false, 'perihelion_');

	}

}

final class PerihelionPersonList {

	private array $results = array();

	public function __construct(PerihelionPersonListParameters $arg) {

		// WHERE
		$wheres = array();
		$wheres[] = 'perihelion_Person.deleted = 0';
		$wheres[] = 'perihelion_Person.siteID = :siteID';
		if (!is_null($arg->personID)) { $wheres[] = 'perihelion_Person.personID = :personID'; }
		if (!is_null($arg->creator)) { $wheres[] = 'perihelion_Person.creator = :creator'; }
		if (!is_null($arg->created)) { $wheres[] = 'perihelion_Person.created = :created'; }
		if (!is_null($arg->updated)) { $wheres[] = 'perihelion_Person.updated = :updated'; }
		if (!is_null($arg->personObject)) { $wheres[] = 'perihelion_Person.personObject = :personObject'; }
		if (!is_null($arg->personObjectID)) { $wheres[] = 'perihelion_Person.personObjectID = :personObjectID'; }
		if (!is_null($arg->personLastNameEnglish)) { $wheres[] = 'perihelion_Person.personLastNameEnglish = :personLastNameEnglish'; }
		if (!is_null($arg->personFirstNameEnglish)) { $wheres[] = 'perihelion_Person.personFirstNameEnglish = :personFirstNameEnglish'; }
		if (!is_null($arg->personLastNameJapanese)) { $wheres[] = 'perihelion_Person.personLastNameJapanese = :personLastNameJapanese'; }
		if (!is_null($arg->personFirstNameJapanese)) { $wheres[] = 'perihelion_Person.personFirstNameJapanese = :personFirstNameJapanese'; }
		if (!is_null($arg->personLastNameJapaneseReading)) { $wheres[] = 'perihelion_Person.personLastNameJapaneseReading = :personLastNameJapaneseReading'; }
		if (!is_null($arg->personFirstNameJapaneseReading)) { $wheres[] = 'perihelion_Person.personFirstNameJapaneseReading = :personFirstNameJapaneseReading'; }
		if (!is_null($arg->personJobTitle)) { $wheres[] = 'perihelion_Person.personJobTitle = :personJobTitle'; }
		if (!is_null($arg->personDivision)) { $wheres[] = 'perihelion_Person.personDivision = :personDivision'; }
		if (!is_null($arg->personOffice)) { $wheres[] = 'perihelion_Person.personOffice = :personOffice'; }
		if (!is_null($arg->personHomepage)) { $wheres[] = 'perihelion_Person.personHomepage = :personHomepage'; }
		if (!is_null($arg->personHomeTelephone)) { $wheres[] = 'perihelion_Person.personHomeTelephone = :personHomeTelephone'; }
		if (!is_null($arg->personMobileTelephone)) { $wheres[] = 'perihelion_Person.personMobileTelephone = :personMobileTelephone'; }
		if (!is_null($arg->personOfficeTelephone)) { $wheres[] = 'perihelion_Person.personOfficeTelephone = :personOfficeTelephone'; }
		if (!is_null($arg->personFax)) { $wheres[] = 'perihelion_Person.personFax = :personFax'; }
		if (!is_null($arg->personMemo)) { $wheres[] = 'perihelion_Person.personMemo = :personMemo'; }
		if (!is_null($arg->personEmail1)) { $wheres[] = 'perihelion_Person.personEmail1 = :personEmail1'; }
		if (!is_null($arg->personEmail2)) { $wheres[] = 'perihelion_Person.personEmail2 = :personEmail2'; }
		if (!is_null($arg->personEmail3)) { $wheres[] = 'perihelion_Person.personEmail3 = :personEmail3'; }
		if (!is_null($arg->personAcceptsEmail)) { $wheres[] = 'perihelion_Person.personAcceptsEmail = :personAcceptsEmail'; }
		if (!is_null($arg->personGender)) { $wheres[] = 'perihelion_Person.personGender = :personGender'; }
		if (!is_null($arg->personBirthday)) { $wheres[] = 'perihelion_Person.personBirthday = :personBirthday'; }
		if (!is_null($arg->personAgeGroup)) { $wheres[] = 'perihelion_Person.personAgeGroup = :personAgeGroup'; }
		if (!is_null($arg->personGuardian)) { $wheres[] = 'perihelion_Person.personGuardian = :personGuardian'; }
		if (!is_null($arg->personActive)) { $wheres[] = 'perihelion_Person.personActive = :personActive'; }
		$where = ' WHERE ' . implode(' AND ', $wheres);

		// SELECTOR
		$selectorArray = array();
		foreach ($arg->resultSet AS $fieldAlias) { $selectorArray[] = $fieldAlias['field'] . ' AS ' . $fieldAlias['alias']; }
		$selector = implode(', ', $selectorArray);

		// ORDER BY
		$orderBys = array();
		foreach ($arg->orderBy AS $fieldSort) { $orderBys[] = $fieldSort['field'] . ' ' . $fieldSort['sort']; }

		$orderBy = '';
		if (!empty($orderBys)) { $orderBy = ' ORDER BY ' . implode(', ', $orderBys); }

		// BUILD QUERY
		$query = 'SELECT ' . $selector . ' FROM perihelion_Person' . $where . $orderBy;
		if ($arg->limit) { $query .= ' LIMIT ' . ($arg->offset?$arg->offset.', ':'') . $arg->limit; }

		// PREPARE QUERY, BIND PARAMS, EXECUTE QUERY
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
		if (!is_null($arg->personID)) { $statement->bindParam(':personID', $arg->personID, PDO::PARAM_INT); }
		if (!is_null($arg->creator)) { $statement->bindParam(':creator', $arg->creator, PDO::PARAM_INT); }
		if (!is_null($arg->created)) { $statement->bindParam(':created', $arg->created, PDO::PARAM_STR); }
		if (!is_null($arg->updated)) { $statement->bindParam(':updated', $arg->updated, PDO::PARAM_STR); }
		if (!is_null($arg->personObject)) { $statement->bindParam(':personObject', $arg->personObject, PDO::PARAM_STR); }
		if (!is_null($arg->personObjectID)) { $statement->bindParam(':personObjectID', $arg->personObjectID, PDO::PARAM_INT); }
		if (!is_null($arg->personLastNameEnglish)) { $statement->bindParam(':personLastNameEnglish', $arg->personLastNameEnglish, PDO::PARAM_STR); }
		if (!is_null($arg->personFirstNameEnglish)) { $statement->bindParam(':personFirstNameEnglish', $arg->personFirstNameEnglish, PDO::PARAM_STR); }
		if (!is_null($arg->personLastNameJapanese)) { $statement->bindParam(':personLastNameJapanese', $arg->personLastNameJapanese, PDO::PARAM_STR); }
		if (!is_null($arg->personFirstNameJapanese)) { $statement->bindParam(':personFirstNameJapanese', $arg->personFirstNameJapanese, PDO::PARAM_STR); }
		if (!is_null($arg->personLastNameJapaneseReading)) { $statement->bindParam(':personLastNameJapaneseReading', $arg->personLastNameJapaneseReading, PDO::PARAM_STR); }
		if (!is_null($arg->personFirstNameJapaneseReading)) { $statement->bindParam(':personFirstNameJapaneseReading', $arg->personFirstNameJapaneseReading, PDO::PARAM_STR); }
		if (!is_null($arg->personJobTitle)) { $statement->bindParam(':personJobTitle', $arg->personJobTitle, PDO::PARAM_STR); }
		if (!is_null($arg->personDivision)) { $statement->bindParam(':personDivision', $arg->personDivision, PDO::PARAM_STR); }
		if (!is_null($arg->personOffice)) { $statement->bindParam(':personOffice', $arg->personOffice, PDO::PARAM_STR); }
		if (!is_null($arg->personHomepage)) { $statement->bindParam(':personHomepage', $arg->personHomepage, PDO::PARAM_STR); }
		if (!is_null($arg->personHomeTelephone)) { $statement->bindParam(':personHomeTelephone', $arg->personHomeTelephone, PDO::PARAM_STR); }
		if (!is_null($arg->personMobileTelephone)) { $statement->bindParam(':personMobileTelephone', $arg->personMobileTelephone, PDO::PARAM_STR); }
		if (!is_null($arg->personOfficeTelephone)) { $statement->bindParam(':personOfficeTelephone', $arg->personOfficeTelephone, PDO::PARAM_STR); }
		if (!is_null($arg->personFax)) { $statement->bindParam(':personFax', $arg->personFax, PDO::PARAM_STR); }
		if (!is_null($arg->personMemo)) { $statement->bindParam(':personMemo', $arg->personMemo, PDO::PARAM_STR); }
		if (!is_null($arg->personEmail1)) { $statement->bindParam(':personEmail1', $arg->personEmail1, PDO::PARAM_STR); }
		if (!is_null($arg->personEmail2)) { $statement->bindParam(':personEmail2', $arg->personEmail2, PDO::PARAM_STR); }
		if (!is_null($arg->personEmail3)) { $statement->bindParam(':personEmail3', $arg->personEmail3, PDO::PARAM_STR); }
		if (!is_null($arg->personAcceptsEmail)) { $statement->bindParam(':personAcceptsEmail', $arg->personAcceptsEmail, PDO::PARAM_INT); }
		if (!is_null($arg->personGender)) { $statement->bindParam(':personGender', $arg->personGender, PDO::PARAM_STR); }
		if (!is_null($arg->personBirthday)) { $statement->bindParam(':personBirthday', $arg->personBirthday, PDO::PARAM_STR); }
		if (!is_null($arg->personAgeGroup)) { $statement->bindParam(':personAgeGroup', $arg->personAgeGroup, PDO::PARAM_STR); }
		if (!is_null($arg->personGuardian)) { $statement->bindParam(':personGuardian', $arg->personGuardian, PDO::PARAM_STR); }
		if (!is_null($arg->personActive)) { $statement->bindParam(':personActive', $arg->personActive, PDO::PARAM_INT); }

		$statement->setFetchMode(PDO::FETCH_ASSOC);
		$statement->execute();

		// WRITE QUERY RESULTS TO ARRAY
		while ($row = $statement->fetch()) { $this->results[] = $row; }

	}

	public function results() : array {

		return $this->results;

	}

	public function resultCount() : int {

		return count($this->results);

	}

}

final class PerihelionPersonListParameters {

	// list filters
	public ?int $personID;
	public ?int $creator;
	public ?string $created;
	public ?string $updated;
	public ?string $personObject;
	public ?int $personObjectID;
	public ?string $personLastNameEnglish;
	public ?string $personFirstNameEnglish;
	public ?string $personLastNameJapanese;
	public ?string $personFirstNameJapanese;
	public ?string $personLastNameJapaneseReading;
	public ?string $personFirstNameJapaneseReading;
	public ?string $personJobTitle;
	public ?string $personDivision;
	public ?string $personOffice;
	public ?string $personHomepage;
	public ?string $personHomeTelephone;
	public ?string $personMobileTelephone;
	public ?string $personOfficeTelephone;
	public ?string $personFax;
	public ?string $personMemo;
	public ?string $personEmail1;
	public ?string $personEmail2;
	public ?string $personEmail3;
	public ?int $personAcceptsEmail;
	public ?string $personGender;
	public ?string $personBirthday;
	public ?string $personAgeGroup;
	public ?string $personGuardian;
	public ?int $personActive;

	// view parameters
	public string $baseURL;
	public ?int $currentPage;
	public ?int $numberOfPages;

	// results, order, limit, offset
	public array $resultSet;
	public array $orderBy;
	public ?int $limit;
	public ?int $offset;

	public function __construct() {

		// list filters
		$this->personID = null;
		$this->creator = null;
		$this->created = null;
		$this->updated = null;
		$this->personObject = null;
		$this->personObjectID = null;
		$this->personLastNameEnglish = null;
		$this->personFirstNameEnglish = null;
		$this->personLastNameJapanese = null;
		$this->personFirstNameJapanese = null;
		$this->personLastNameJapaneseReading = null;
		$this->personFirstNameJapaneseReading = null;
		$this->personJobTitle = null;
		$this->personDivision = null;
		$this->personOffice = null;
		$this->personHomepage = null;
		$this->personHomeTelephone = null;
		$this->personMobileTelephone = null;
		$this->personOfficeTelephone = null;
		$this->personFax = null;
		$this->personMemo = null;
		$this->personEmail1 = null;
		$this->personEmail2 = null;
		$this->personEmail3 = null;
		$this->personAcceptsEmail = null;
		$this->personGender = null;
		$this->personBirthday = null;
		$this->personAgeGroup = null;
		$this->personGuardian = null;
		$this->personActive = null;

		// view parameters
		$this->baseURL = '/' . Lang::prefix() . 'perihelion/person/';
		$this->currentPage = null;
		$this->numberOfPages = null;
		$this->card = true;

		// results, order, limit, offset
		$this->resultSet = array();
		$object = new Person();
		foreach ($object AS $key => $value) {
			$this->resultSet[] = array('field' => 'perihelion_Person.'.$key, 'alias' => $key);
		}
		$this->orderBy = array(
			array('field' => 'perihelion_Person.created', 'sort' => 'DESC')
		);
		$this->limit = null;
		$this->offset = null;

	}

}

?>