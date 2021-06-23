<?php

/*

CREATE TABLE `perihelion_ContentCategory` (
  `contentCategoryID` int(12) NOT NULL AUTO_INCREMENT,
  `siteID` int(12) NOT NULL,
  `creator` int(12) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `deleted` int(1) NOT NULL,
  `contentCategoryParentID` int(12) NOT NULL,
  `contentCategoryURL` varchar(100) NOT NULL,
  `contentCategoryEnglish` varchar(100) NOT NULL,
  `contentCategoryJapanese` varchar(100) NOT NULL,
  PRIMARY KEY (`contentCategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

*/

final class ContentCategory extends ORM {

	public $contentCategoryID;
	public $siteID;
	public $creator;
	public $created;
	public $updated;
	public $deleted;
	public $contentCategoryParentID;
	public $contentCategoryURL;
	public $contentCategoryEnglish;
	public $contentCategoryJapanese;

	public function __construct($contentCategoryID = null) {

		$dt = new DateTime();

		$this->contentCategoryID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->creator = $_SESSION['userID'];
		$this->created = $dt->format('Y-m-d H:i:s');
		$this->updated = $dt->format('Y-m-d H:i:s');
		$this->deleted = 0;
		$this->contentCategoryParentID = 0;
		$this->contentCategoryURL = '';
		$this->contentCategoryEnglish = '';
		$this->contentCategoryJapanese = '';

		if ($contentCategoryID) {

			$nucleus = Nucleus::getInstance();

			$whereClause = array();

			$whereClause[] = 'siteID = :siteID';
			$whereClause[] = 'deleted = 0';
			$whereClause[] = 'contentCategoryID = :contentCategoryID';

			$query = 'SELECT * FROM perihelion_ContentCategory WHERE ' . implode(' AND ', $whereClause) . ' LIMIT 1';

			$statement = $nucleus->database->prepare($query);
			$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
			$statement->bindParam(':contentCategoryID', $contentCategoryID, PDO::PARAM_INT);
			$statement->execute();

			if ($row = $statement->fetch()) {
				foreach ($row AS $key => $value) { if (isset($this->$key)) { $this->$key = $value; } }
			}

		}

	}

	public function markAsDeleted() {

		$dt = new DateTime();
		$this->updated = $dt->format('Y-m-d H:i:s');
		$this->deleted = 1;
		$conditions = array('contentCategoryID' => $this->contentCategoryID);
		self::update($this, $conditions);

	}

}

final class ContentCategoryList {

	private $categories;

	public function __construct(ContentCategoryListParameter $arg) {

		$this->categories = array();

		$where = array();
		$where[] = 'siteID = :siteID';
		$where[] = 'deleted = 0';

		$orderBy = array();
		foreach ($arg->orderBy AS $field => $sort) { $orderBy[] = $field . ' ' . $sort; }

		switch ($arg->resultSet) {
			case 'robust':
				$selector = '*';
				break;
			default:
				$selector = 'contentCategoryID';
		}

		$query = 'SELECT ' . $selector . ' FROM perihelion_ContentCategory WHERE ' . implode(' AND ',$where) . ' ORDER BY ' . implode(', ',$orderBy);
		if ($arg->limit) { $query .= ' LIMIT ' . $arg->limit . ($arg->offset?', '.$arg->offset:''); }

		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);

		$statement->execute();

		while ($row = $statement->fetch()) {
			if ($arg->resultSet == 'robust') {
				$this->categories[] = $row;
			} else {
				$this->categories[] = $row['contentCategoryID'];
			}
		}

	}

	public function categories() {

		return $this->categories;

	}

	public function categoryCount() {

		return count($this->categories);

	}

}

final class ContentCategoryListParameter {

	public $resultSet;
	public $orderBy;
	public $limit;
	public $offset;

	public function __construct() {

		$this->resultSet = 'id'; // [id|robust]
		if ($_SESSION['lang'] == 'ja') {
			$this->orderBy = array('contentCategoryJapanese' => 'ASC');
		} else {
			$this->orderBy = array('contentCategoryEnglish' => 'ASC');
		}
		$this->limit = null;
		$this->offset = null;

	}

}

?>