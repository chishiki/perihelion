<?php

class Audit {

	// NO UPDATE OR DELETE DUNCTIONALITY REQUIRED (DOES NOT EXTEND ORM)
	// ORM utilizes this class to log all CRUD operations
	
	public $auditID;
	public $siteID;
	public $auditDateTime;
	public $auditUserID;
	public $auditIP;
	public $auditAction;
	public $auditObject;
	public $auditObjectID;
	public $auditProperty;
	public $auditValue;
	public $auditResult;
	public $auditNote;
	
	public function __construct($auditID = 0) {
	
		if ($auditID != 0) {
		
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Audit WHERE auditID = :auditID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':auditID' => $auditID));
			if (!$row = $statement->fetch()) { die('Audit entry does not exist.'); }
			foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } }
			
		} else {

			$this->auditID = 0;
			if (isset($_SESSION['siteID'])) { $this->siteID = $_SESSION['siteID']; } else { $this->siteID = 0; }
			$this->auditDateTime = date('Y-m-d H:i:s');
			if (isset($_SESSION['userID'])) { $this->auditUserID = $_SESSION['userID']; } else { $this->auditUserID = 0; }
			$this->auditIP = $_SERVER['REMOTE_ADDR'];
			$this->auditAction = '';
			$this->auditObject = '';
			$this->auditObjectID = 0;
			$this->auditProperty = '';
			$this->auditValue = '';
			$this->auditResult = '';
			$this->auditNote = '';

		}
	}
	
	public static function createAuditEntry($ioa) { // Instance of Audit Object

		$auditVariableArray = get_object_vars($ioa);
		$auditPropertyArray = array_keys($auditVariableArray);
		
		$nucleus = Nucleus::getInstance();
		$query = "INSERT INTO `perihelion_Audit` (" . implode(', ', $auditPropertyArray) . ") VALUES (:" . implode(', :', $auditPropertyArray) . ")";
		$statement = $nucleus->database->prepare($query);
		foreach ($auditVariableArray AS $property => $value) { $attribute = ':' . $property; $statement->bindValue($attribute, $value); }
		if (!$statement->execute()){ die("Audit::createAuditEntry(\$ioa) => There was a problem saving to the audit trail."); }
		
		$auditID = $nucleus->database->lastInsertId();
		return $auditID;
		
	}

	public static function getAuditTrailArray($siteID, $auditUserID, $auditObject, $startDate, $endDate, $limit = '100') { // admin|manager

		$auditTrailArray = array();
		
		$nucleus = Nucleus::getInstance();

		$where = array();
		if ($siteID) { $where[] = "siteID = :siteID"; }
		if ($auditUserID) { $where[] = "auditUserID = :auditUserID"; }
		if ($auditObject) { $where[] = "auditObject = :auditObject"; }
		if ($startDate) { $where[] = "auditDateTime >= :startDate"; $startDate = $startDate . ' 00:00:00'; }
		if ($endDate) { $where[] = "auditDateTime <= :endDate"; $endDate = $endDate . ' 23:59:59'; }

		$whereClause = '';
		if (!empty($where)) { $whereClause = 'WHERE ' . implode(' AND ',$where); }

		$query = 'SELECT auditID FROM perihelion_Audit ' . $whereClause . ' ORDER BY auditID DESC LIMIT ' . $limit;

		$statement = $nucleus->database->prepare($query);

		if ($siteID) { $statement->bindParam(':siteID', $siteID, PDO::PARAM_INT); }
		if ($auditUserID) { $statement->bindParam(':auditUserID', $auditUserID, PDO::PARAM_INT); }
		if ($auditObject) { $statement->bindParam(':auditObject', $auditObject, PDO::PARAM_STR); }
		if ($startDate) { $statement->bindParam(':startDate', $startDate, PDO::PARAM_STR); }
		if ($endDate) { $statement->bindParam(':endDate', $endDate, PDO::PARAM_STR); }

		$statement->execute();

		while ($row = $statement->fetch()) { $auditTrailArray[] = $row['auditID']; }
		return $auditTrailArray;

	}

	public static function getAuditObjectArray() {
		
		$query = "SELECT DISTINCT(auditObject) FROM perihelion_Audit ORDER BY auditObject ASC";
		
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->execute();
		
		$auditObjects = array();
		while ($row = $statement->fetch()) {
			if ($row['auditObject'] != '') { $auditObjects[] = $row['auditObject']; }
		}
		return $auditObjects;
		
	}
	
}

final class AuditTrail {

	public function getAuditTrail($auditObject = null, $auditObjectID = null) {

		$where = array();
		$where[] = 'siteID = :siteID';
		if ($auditObject) { $where[] = 'auditObject = :auditObject'; }
		if ($auditObject && $auditObjectID) { $where[] = 'auditObjectID = :auditObjectID'; }

		$query = 'SELECT auditID, auditDateTime, auditUserID, auditIP, auditAction, auditNote ';
		$query .= 'FROM perihelion_Audit WHERE ' . implode(' AND ',$where) . ' ORDER BY auditID DESC';

		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
		if ($auditObject) { $statement->bindParam(':auditObject', $auditObject, PDO::PARAM_STR); }
		if ($auditObject && $auditObjectID) { $statement->bindParam(':auditObjectID', $auditObjectID, PDO::PARAM_INT); }
		$statement->execute();

		$auditTrail = array();
		while ($row = $statement->fetch()) { $auditTrail[] = $row; }
		return $auditTrail;

	}

}

?>