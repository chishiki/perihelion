<?php

/*

CREATE TABLE `perihelion_PersonMap` (
  `personMapID` int NOT NULL AUTO_INCREMENT,
  `siteID` int NOT NULL,
  `creator` int NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` int NOT NULL,
  `personID` int NOT NULL,
  `personObject` varchar(50) NOT NULL,
  `personObjectID` int NOT NULL,
  PRIMARY KEY (`personMapID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

*/

final class PersonMap extends ORM {

	public ?int $personMapID;
	public int $siteID;
	public int $creator;
	public string $created;
	public ?string $updated;
	public int $deleted;
	public int $personID;
	public string $personObject;
	public int $personObjectID;

	public function __construct($personMapID = null) {

		$dt = new DateTime();

		$this->personMapID = null;
		$this->siteID = $_SESSION['siteID'];
		$this->creator = $_SESSION['userID'];
		$this->created = $dt->format('Y-m-d H:i:s');
		$this->updated = null;
		$this->deleted = 0;
		$this->personID = 0;
		$this->personObject = '';
		$this->personObjectID = 0;

		if (!is_null($personMapID)) {

			$nucleus = Nucleus::getInstance();

			$whereClause = array();
			$whereClause[] = 'siteID = :siteID';
			$whereClause[] = 'deleted = 0';
			$whereClause[] = 'personMapID = :personMapID';

			$query = 'SELECT * FROM perihelion_PersonMap WHERE ' . implode(' AND ', $whereClause) . ' LIMIT 1';
			$statement = $nucleus->database->prepare($query);
			$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
			$statement->bindParam(':personMapID', $personMapID, PDO::PARAM_INT);
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
		$conditions = array('personMapID' => $this->personMapID);
		self::update($this, $conditions, true, false, 'perihelion_');

	}

}

final class PersonMapList {

	private array $results = array();

	public function __construct(PersonMapParameters $arg) {

		// WHERE
		$wheres = array();
		$wheres[] = 'perihelion_PersonMap.deleted = 0';
		$wheres[] = 'perihelion_PersonMap.siteID = :siteID';
		if (!is_null($arg->personMapID)) { $wheres[] = 'perihelion_PersonMap.personMapID = :personMapID'; }
		if (!is_null($arg->creator)) { $wheres[] = 'perihelion_PersonMap.creator = :creator'; }
		if (!is_null($arg->created)) { $wheres[] = 'perihelion_PersonMap.created = :created'; }
		if (!is_null($arg->updated)) { $wheres[] = 'perihelion_PersonMap.updated = :updated'; }
		if (!is_null($arg->personID)) { $wheres[] = 'perihelion_PersonMap.personID = :personID'; }
		if (!is_null($arg->personObject)) { $wheres[] = 'perihelion_PersonMap.personObject = :personObject'; }
		if (!is_null($arg->personObjectID)) { $wheres[] = 'perihelion_PersonMap.personObjectID = :personObjectID'; }

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
		$query = 'SELECT ' . $selector . ' FROM perihelion_PersonMap' . $where . $orderBy;
		if ($arg->limit) { $query .= ' LIMIT ' . ($arg->offset?$arg->offset.', ':'') . $arg->limit; }

		// PREPARE QUERY, BIND PARAMS, EXECUTE QUERY
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
		if (!is_null($arg->personMapID)) { $statement->bindParam(':personMapID', $arg->personMapID, PDO::PARAM_INT); }
		if (!is_null($arg->creator)) { $statement->bindParam(':creator', $arg->creator, PDO::PARAM_INT); }
		if (!is_null($arg->created)) { $statement->bindParam(':created', $arg->created, PDO::PARAM_STR); }
		if (!is_null($arg->updated)) { $statement->bindParam(':updated', $arg->updated, PDO::PARAM_STR); }
		if (!is_null($arg->personID)) { $statement->bindParam(':personID', $arg->personID, PDO::PARAM_INT); }
		if (!is_null($arg->personObject)) { $statement->bindParam(':personObject', $arg->personObject, PDO::PARAM_STR); }
		if (!is_null($arg->personObjectID)) { $statement->bindParam(':personObjectID', $arg->personObjectID, PDO::PARAM_INT); }

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

final class PersonMapParameters {

	// search filters
	public ?int $personMapID;
	public ?int $creator;
	public ?string $created;
	public ?string $updated;
	public ?int $personID;
	public ?string $personObject;
	public ?int $personObjectID;

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
		$this->personMapID = null;
		$this->creator = null;
		$this->created = null;
		$this->updated = null;
		$this->personID = null;
		$this->personObject = null;
		$this->personObjectID = null;

		// results, order, limit, offset
		$this->resultSet = array();
		$object = new PersonMap();
		foreach ($object AS $key => $value) {
			$this->resultSet[] = array('field' => 'perihelion_PersonMap.'.$key, 'alias' => $key);
		}
		$this->orderBy = array(
			array('field' => 'perihelion_PersonMap.personMapID', 'sort' => 'DESC')
		);
		$this->limit = null;
		$this->offset = null;

	}

}

final class PersonMapUtilities {

	public static function getPersonMaps($personID) {

		$personMaps = array();

		// BUILD QUERY
		$query = "
			SELECT * FROM perihelion_PersonMap
			WHERE siteID = :siteID AND deleted = 0 AND personID = :personID
			ORDER BY personObject ASC
		";

		// PREPARE QUERY, BIND PARAMS, EXECUTE QUERY
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
		$statement->bindParam(':personID', $personID, PDO::PARAM_INT);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		$statement->execute();

		// WRITE QUERY RESULTS TO ARRAY
		while ($row = $statement->fetch()) { $personMaps[] = $row; }

		return $personMaps;

	}

}

?>
