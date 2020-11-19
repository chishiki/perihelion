<?php

class Cookie {
	
	public $sessionID;
	
	public function __construct() {
		$this->sessionID = Utilities::generateMash();
		$sessionExpiry = strtotime("+1 month", time());
		setcookie('perihelion', $this->sessionID, $sessionExpiry, '/', '', FALSE);
	}
	
	public static function killCookie() {
		setcookie('perhihelion', 'loggedout', 1, '/', '', FALSE);
		unset($_COOKIE['perihelion']);
	}
	
}

?>