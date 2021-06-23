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
	
	public static function update($object, $conditions, $audit = true, $verbose = false, $tablePrefix = 'perihelion_') {
		
		$objectName = get_class($object);

		$objectVariableArray = get_object_vars($object);
		$tableName = $tablePrefix . $objectName;
		if (!self::tableExists($tableName)) { die("ORM::update($objectName) => A '$tableName' table does not exist OR the table is empty."); }
		
		$scooby = array();
		foreach ($conditions AS $condition => $value) { $scooby[] = "$condition = :$condition"; }
		$scoobyString =  implode(' AND ', $scooby);
		
		$shaggy = array();
		foreach ($objectVariableArray AS $property => $value) { $shaggy[] = "$property = :$property"; }
		$shaggyString =  implode(', ', $shaggy);
		
		$query = "UPDATE $tableName SET $shaggyString WHERE $scoobyString LIMIT 1";

		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		
		foreach ($conditions AS $condition => $value) { $attribute = ':' . $condition; $statement->bindValue($attribute, $value); }
		foreach ($objectVariableArray AS $property => $value) {
			if ($property == 'updated') {
				$updatedDT = new DateTime();
				$attribute = ':' . $property; $statement->bindValue($attribute, $updatedDT->format('Y-m-d H:i:s'));
			} else {
				$attribute = ':' . $property; $statement->bindValue($attribute, $value);
			}
		}

		if (!$statement->execute()){
			print_r($statement->errorInfo());
			die("ORM::update($objectName) => There was a problem updating your $objectName.");
		}
		
		// ENABLE TOGGLE VERBOSE LOGGING HERE (EACH UPDATED VALUE LOGGED INDIVIDUALLY)
		
		if ($audit) {
			$ioa = new Audit();
			unset($ioa->auditID);
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
	
	public static function delete($object, $conditions, $audit = true , $tablePrefix = 'perihelion_') {
		
		// SAMPLE USAGE //
		// $content = new Content(123456);
		// $conditions = array('contentID' => $content->contentID);
		// Content::delete($content, $conditions);
		
		$objectName = get_class($object);
		
		$undeletableObjects = array('Site');
		if (in_array($objectName,$undeletableObjects)) { die("ORM::delete() => $objectName cannot be deleted."); }
		
		$tableName = $tablePrefix . $objectName;
		if (!self::tableExists($tableName)) { die('ORM::delete($object) => A table does not exist with that object name OR the table is empty.'); }
		
		// create delete query
		$scooby = array();
		foreach ($conditions AS $key => $value) { $scooby[] = "$key = '$value'"; }
		$scoobyString = implode(' AND ', $scooby);
		$query = "DELETE FROM $tableName WHERE $scoobyString LIMIT 1";

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

	private static function tableExists($tableName) {
	
		$nucleus = Nucleus::getInstance();
		$queryTableCheck = "SELECT 1 FROM `$tableName` LIMIT 1";
		$statement = $nucleus->database->prepare($queryTableCheck);
		$statement->execute();
		if ($row = $statement->fetch()){ return true; } else { return false; }
		
	}

}

?>