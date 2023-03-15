<?php

/*

CREATE TABLE `perihelion_Person` (
  `personID` int NOT NULL AUTO_INCREMENT,
  `siteID` int NOT NULL,
  `creator` int NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NULL,
  `deleted` int NOT NULL,
  `personObject` varchar NOT NULL,
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

	// view parameters
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

		// view parameters
		$this->currentPage = null;
		$this->numberOfPages = null;

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