<?php

class UserRole extends ORM {

	public $siteID;
	public $userID;
	public $userRole;
	public $lastVisit;

	public function __construct($siteID, $userID) {

		$this->siteID = $siteID;
		$this->userID = $userID;
		$this->userRole = null;
		$this->lastVisit = '0000-00-00 00:00:00';
		
		$query = "SELECT * FROM perihelion_UserRole WHERE siteID = :siteID AND userID = :userID LIMIT 1";
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $siteID,':userID' => $userID));

		if ($row = $statement->fetch()) {
			$this->userRole = $row['userRole'];
			$this->lastVisit = $row['lastVisit'];
		}
		
		if (in_array($userID,Config::read('admin.userIdArray'))) {
			$this->userRole = 'siteAdmin';
		}

	}
	
	public function getUserRole() {
		return $this->userRole;
	}
	
	public function setUserRole($role) {
		$this->userRole = $role;
	}
	
	public function setLastVisit() {
		
		$datetime = date('Y-m-d H:i:s');
		$nucleus = Nucleus::getInstance();
		$query = "UPDATE perihelion_UserRole SET lastVisit = '$datetime' WHERE userID = :userID AND siteID = :siteID LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':userID', $this->userID);
		$statement->bindParam(':siteID', $this->siteID);
		$statement->execute();
		
	}
	
	public static function hasAccess($siteID, $userID) {
		
		$hasAccess = null;
		$userRole = new self($siteID, $userID);
		if ($userRole->userRole) { $hasAccess = $userRole->userRole; }
		return $hasAccess;
		
	}
	
	public static function validUserRoles() {
		
		$roles = array(
			'siteAdmin',
			'siteManager',
		    'siteDesigner',
		    'siteUser'
		);
		return $roles;
		
	}
	
}

final class UserRoleUtilities {

	private $modules;
	private $roles;

	public function __construct($modules) {

		$this->modules = $modules;
		$this->roles = array('siteAdmin','siteManager','siteDesigner','siteUser');

		foreach ($this->modules AS $moduleName) {

			$userRoleClass = ucfirst($moduleName) . 'UserRoles';
			if (class_exists($userRoleClass)) {
				$moduleUR = new $userRoleClass();
				$moduleUserRoles = $moduleUR->roles();
				foreach ($moduleUserRoles AS $userRole) {
					$this->roles[] = $userRole;
				}
			}
		}

	}

	public function getUserRoles() {
		return $this->roles;
	}

}

?>