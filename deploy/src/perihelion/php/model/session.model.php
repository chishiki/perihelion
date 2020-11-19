<?php

class Session extends ORM {

	public $sessionID;
	public $userID;
	public $sessionDateTimeSet;
	public $sessionDateTimeExpire;
	public $sessionIP;
	public $sessionUserAgent;
	public $sessionData;

	public function __construct($sessionID) {

		$this->sessionID = $sessionID;
		$this->userID = 0;
		$this->sessionDateTimeSet = date('Y-m-d H:i:s');
		$this->sessionDateTimeExpire = date("Y-m-d H:i:s", strtotime("+1 month"));
		$this->sessionIP = $_SERVER['REMOTE_ADDR'];
		$this->sessionUserAgent = $_SERVER['HTTP_USER_AGENT'];
		$this->sessionData = '';

		if ($sessionID) {
			$nucleus = Nucleus::getInstance();
			$currentDateTime = date('Y-m-d H:i:s');
			$query = "SELECT * FROM perihelion_Session WHERE sessionID = :sessionID AND sessionDateTimeExpire > '$currentDateTime' LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':sessionID' => $sessionID));
			if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } } }
		}

	}
	
	public function isValid() {
		
		$currentDateTime = date('Y-m-d H:i:s');
		
		if (
			$this->sessionID != '' && $this->userID != 0
			&& $currentDateTime <= $this->sessionDateTimeExpire
			&& $_SERVER['HTTP_USER_AGENT'] == $this->sessionUserAgent
		) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public static function getSession($name) {
		return $_SESSION[$name];
	}
    
	public static function setSession($name, $value) {
		$_SESSION[$name] = $value;
	}
	
	public static function unsetSession($name) {
		unset($_SESSION[$name]);
	}
	
}

?>