<?php

class ORM {

	public static function insert($object, $returnID = true, $tablePrefix = 'perihelion_') {

		$objectName = get_class($object);
		$objectVariableArray = get_object_vars($object);
		$objectPropertyArray = array_keys($objectVariableArray);
		$tableName = $tablePrefix . $objectName;

		// create insert query
		$query = "INSERT INTO `$tableName` (" . implode(', ', $objectPropertyArray) . ") VALUES (:" . implode(', :', $objectPropertyArray) . ")";

		// build prepared statement
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		foreach ($objectVariableArray AS $property => $value) {
			$attribute = ':' . $property;
			$statement->bindValue($attribute, $value);
		}

		// execute
		if (!$statement->execute()){
			print_r($statement->errorInfo());
			die("ORM::insert($objectName) => There was a problem saving your new $objectName.");
		}

		// get new objectID
		if($returnID) { $auditObjectID = $nucleus->database->lastInsertId(); }
		
		// ENABLE TOGGLE VERBOSE LOGGING HERE?
		// ADD TO AUDIT TRAIL (for each value?)
		$ioa = new Audit();
		unset($ioa->auditID);
		$ioa->auditAction = 'create';
		$ioa->auditObject = $objectName;
		if($returnID) { $ioa->auditObjectID = $auditObjectID; }
		$ioa->auditResult = 'success';
		if ($objectName == 'User' && isset($object->userPassword)) { $object->userPassword = 'RemovedByORM'; }
		$ioa->auditNote = json_encode($object);
		Audit::createAuditEntry($ioa);
		
		if($returnID) { return $auditObjectID; }
		
	}

	// public static function insertMulti() { }

	public static function update($object, $conditions, $audit = true, $verbose = false, $tablePrefix = 'perihelion_') {

		$objectName = get_class($object);
		$objectVariableArray = get_object_vars($object);
		$tableName = $tablePrefix . $objectName;
		
		$conditionArray = array();
		foreach ($conditions AS $condition => $value) {
			$conditionArray[] = "$condition = :" . (is_null($value)?"NULL":$condition);
		}
		$whereConditions =  implode(" AND ", $conditionArray);
		
		$assignmentArray = array();
		foreach ($objectVariableArray AS $property => $value) {
			if (is_null($value)) {
				$assignmentArray[] = "$property = NULL";
			} else {
				$assignmentArray[] = "$property = :$property";
			}
		}
		$assignmentList =  implode(", ", $assignmentArray);
		
		$query = "
			UPDATE
				$tableName
			SET
				$assignmentList
			WHERE
				$whereConditions
			LIMIT 1
		";

		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);

		foreach ($conditions AS $condition => $value) {
			if (!is_null($value)) {
				$attribute = ':' . $condition;
				$statement->bindValue($attribute, $value);
			}
		}
		foreach ($objectVariableArray AS $property => $value) {
			if ($property == 'updated') {
				$updatedDT = new DateTime();
				$attribute = ':updated';
				$statement->bindValue($attribute, $updatedDT->format('Y-m-d H:i:s'));
			} elseif (!is_null($value)) {
				$attribute = ':' . $property;
				$statement->bindValue($attribute, $value);
			}
		}

		if (!$statement->execute()){
			print_r($statement->errorInfo());
			die("ORM::update($objectName) => There was a problem updating your $objectName.");
		}
		
		// ENABLE TOGGLE VERBOSE LOGGING HERE (EACH UPDATED VALUE LOGGED INDIVIDUALLY)
		
		if ($audit) {

			$ioa = new Audit();
			$ioa->auditAction = 'update';
			$ioa->auditObject = $objectName;
			$conditionsTemp = array_values($conditions);
			$ioa->auditObjectID = array_shift($conditionsTemp);
			$ioa->auditResult = 'success';
			if ($objectName == 'User' && isset($object->userPassword)) { $object->userPassword = 'RemovedByORM'; }
			$ioa->auditNote = json_encode($object);
			Audit::createAuditEntry($ioa);

		}
		
	}

	// public static function updateMulti() { }

	public static function delete($object, $conditions, $audit = true , $tablePrefix = 'perihelion_', $limit = 1) { // $limit = null (no limit)
		
		// SAMPLE USAGE //
		// $content = new Content(123456);
		// $conditions = array('contentID' => $content->contentID);
		// Content::delete($content, $conditions);
		
		$objectName = get_class($object);
		
		$undeletableObjects = array('Site');
		if (in_array($objectName,$undeletableObjects)) { die("ORM::delete() => $objectName cannot be deleted."); }
		
		$tableName = $tablePrefix . $objectName;
		// if (!self::tableExists($tableName)) { die('ORM::delete($object) => A table does not exist with that object name OR the table is empty.'); }
		
		// create delete query
		$scooby = array();
		foreach ($conditions AS $key => $value) { $scooby[] = "$key = '$value'"; }
		$scoobyString = implode(' AND ', $scooby);

		$query = "DELETE FROM $tableName WHERE $scoobyString";
		if (!is_null($limit)) { $query .= " LIMIT " . $limit; }

		// build prepared statement
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		
		// execute
		if (!$statement->execute()){ die("ORM::delete($objectName) => There was a problem deleting your $objectName."); }
		
		// ADD TO AUDIT TRAIL (for each revised value?)
		if ($audit) {
			$ioa = new Audit();
			unset($ioa->auditID);
			$ioa->auditAction = 'delete';
			$ioa->auditObject = $objectName;
			$conditionsTemp = array_values($conditions);
			$ioa->auditObjectID = array_shift($conditionsTemp);
			$ioa->auditResult = 'success';
			if ($objectName == 'User' && isset($object->userPassword)) { $object->userPassword = 'RemovedByORM'; }
			$ioa->auditNote = json_encode($object);
			Audit::createAuditEntry($ioa);
		}
		
	}

}

?>