<?php

/*

CREATE TABLE `perihelion_Note` (
  `noteID` int NOT NULL AUTO_INCREMENT,
  `siteID` int NOT NULL,
  `creator` int NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `deleted` int NOT NULL,
  `noteObject` varchar(20) NOT NULL,
  `noteObjectID` int NOT NULL,
  `noteContent` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`noteID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

*/

final class Note extends ORM {

	public $noteID;
	public $siteID;
	public $creator;
	public $created;
	public $updated;
	public $deleted;
	public $noteObject;
	public $noteObjectID;
	public $noteContent;
	
	public function __construct($noteID = null) {

		$dt = new DateTime();

		$this->noteID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->creator = $_SESSION['userID'];
		$this->created = $dt->format('Y-m-d H:i:s');
		$this->updated = $dt->format('Y-m-d H:i:s');
		$this->deleted = 0;
		$this->noteObject = '';
		$this->noteObjectID = 0;
		$this->noteContent = '';
			
		if ($noteID) {
		
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Note WHERE noteID = :noteID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':noteID' => $noteID));
			if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (property_exists($this, $key)) { $this->$key = $value; } } }
			
		}
		
	}

	public function deleteNote() {

		$this->deleted = 1;
		$cond = array('noteID' => $this->noteID);
		self::update($this, $cond);

	}

}

final class NoteList {

	private $notes;

	public function __construct(NoteListArguments $arg) {

		$this->notes = array();

		// WHERE
		$wheres = array();
		$wheres[] = 'deleted = 0';
		$wheres[] = 'siteID = :siteID';
		if (!is_null($arg->creator)) { $wheres[] = 'creator = :creator'; }
		if (!is_null($arg->searchString)) { $wheres[] = 'noteContent LIKE concat("%",:searchString,"%")'; }
		if (!is_null($arg->noteObject)) { $wheres[] = 'noteObject = :noteObject'; }
		if (!is_null($arg->noteObjectID)) { $wheres[] = 'noteObjectID = :noteObjectID'; }
		$where = ' WHERE ' . implode(' AND ',$wheres);

		// SELECTOR
		$selectorArray = array();
		foreach ($arg->resultSet AS $fieldAlias) { $selectorArray[] = $fieldAlias['field'] . ' AS ' . $fieldAlias['alias']; }
		$selector = implode(', ', $selectorArray);

		// ORDER BY
		$orderBys = array();
		foreach ($arg->orderBy AS $fieldSort) { $orderBys[] = $fieldSort['field'] . ' ' . $fieldSort['sort']; }
		$orderBy = '';
		if (!empty($orderBys)) { $orderBy = ' ORDER BY ' . implode(', ',$orderBys); }

		// BUILD QUERY
		$query = 'SELECT ' . $selector . ' FROM perihelion_Note' . $where . $orderBy;
		if ($arg->limit) { $query .= ' LIMIT ' . ($arg->offset?$arg->offset.', ':'') . $arg->limit; }

		// PREPARE QUERY, BIND PARAMS, EXECUTE QUERY
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
		if (!is_null($arg->creator)) { $statement->bindParam(':creator', $arg->creator, PDO::PARAM_INT); }
		if (!is_null($arg->searchString)) { $statement->bindParam(':searchString', $arg->searchString); }
		if (!is_null($arg->noteObject)) { $statement->bindParam(':noteObject', $arg->noteObject, PDO::PARAM_STR); }
		if (!is_null($arg->noteObjectID)) { $statement->bindParam(':noteObjectID', $arg->noteObjectID, PDO::PARAM_INT); }
		$statement->execute();

		// ADD QUERY RESULTS TO ARRAY
		while ($row = $statement->fetch()) {
			$this->notes[] = $row;
		}

	}

	public function getNotes() {

		return $this->notes;

	}

}

final class NoteListArguments {

	public $creator;
	public $searchString;
	public $noteObject;
	public $noteObjectID;

	public $resultSet;
	public $orderBy;
	public $limit;
	public $offset;

	public function __construct() {

		$this->creator = null;
		$this->searchString = null;
		$this->noteObject = null;
		$this->noteObjectID = null;

		$this->resultSet = array(
			array('field' => 'noteID', 'alias' => 'noteID'),
			array('field' => 'creator', 'alias' => 'creator'),
			array('field' => 'created', 'alias' => 'created'),
			array('field' => 'updated', 'alias' => 'updated'),
			array('field' => 'noteObject', 'alias' => 'noteObject'),
			array('field' => 'noteObjectID', 'alias' => 'noteObjectID'),
			array('field' => 'noteContent', 'alias' => 'noteContent'),
		);
		$this->orderBy = array(
			array('field' => 'noteID', 'sort' => 'ASC')
		);

		$this->limit = null;
		$this->offset = null;

	}

}


?>