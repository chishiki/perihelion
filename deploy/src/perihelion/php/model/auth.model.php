<?php

class Auth {

	public static function checkAuth($userSelector, $password) {

		$errorArray = array();
		
		$userID = User::getUserID($userSelector);
		$user = new User($userID);
		
		$userRole = new UserRole($_SESSION['siteID'],$userID);

		if (!$userID || (!password_verify($password, $user->userPassword))) {
			$errorArray['login'][] = 'Authentication failed. Please try again or <a href="/account-recovery/">recover your account details</a>.';
		} elseif (!$userRole->getUserRole() && $_SESSION['siteID'] != 1) {
			$errorArray['login'][] = 'Please contact ' . Config::read('support.email') . ' for access to this domain.';
		} elseif ($user->userBlackList) {
			$errorArray['login'][] = 'There seems to be a problem with your account. Please contact ' . Config::read('support.email') . '.';
		}

		return $errorArray;

	}
	
	public static function login($userID, $newSession = true) {
		
		$siteID = Site::getCurrentSiteID();
		$userRole = new UserRole($siteID,$userID);
		
		$ioa = new Audit();
		$ioa->auditUserID = $userID;
		$ioa->auditObject = 'auth';
		$ioa->auditResult = 'successful';
		$ioa->auditProperty = 'sessionID';
		
		if ($newSession) {
			
			$cookie = new Cookie();
			$sessionID = $cookie->sessionID;
			$session = new Session($sessionID);
			$session->userID = $userID;
			Session::insert($session);
			
			$ioa->auditAction = 'login';
			$ioa->auditValue = $sessionID;
			
		} else {
			
			$ioa->auditAction = 'cookieLogin';
			$ioa->auditValue = $_COOKIE['perihelion'];
			
		}

		Session::setSession('userID', $userID);
		Session::setSession('userRoleForCurrentSite', $userRole->getUserRole()); // => change to userRole
			
		$user = new User($userID);
		$user->setUserLastVisitDateTime();
		$userRole->setLastVisit();

		Audit::createAuditEntry($ioa);
		
	}
	
	public static function logout() {
	
		// add to audit trail
		$ioa = new Audit();
		$lang = $_SESSION['lang'];
	
		// kill session
		session_unset();
		session_destroy();
		
		// kill perihelion_session
		$sessionID = $_COOKIE['perihelion'];
		$currentDateTime = date('Y-m-d H:i:s');
		$nucleus = Nucleus::getInstance();
		$query = "UPDATE perihelion_Session SET sessionDateTimeExpire = :currentDateTime WHERE sessionID = :sessionID LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':currentDateTime' => $currentDateTime, ':sessionID' => $sessionID));
		
		// kill cookie
		Cookie::killCookie();
		
		$ioa->auditAction = 'logout';
		$ioa->auditObject = 'auth';
		$ioa->auditResult = 'successful';
		Audit::createAuditEntry($ioa);

		session_start();
		$_SESSION['userID'] = 0;
		$_SESSION['siteID'] = Site::siteID();
		$_SESSION['lang'] = $lang;

	}
	
	public static function register($username, $userEmail, $password, $confirmPassword, $obFussyCat) {
	
		$errorArray = array();
		
		if ($username == '') {
			$errorArray['username'][] = "The username field is required.";
		} else {
			if (User::usernameExists($username)) { $errorArray['username'][] = "That username is already taken."; }
			if (!preg_match('/^[A-Za-z0-9_-]+$/',$username)) {
				$errorArray['username'][] = "Your username can contain only letters, numbers, hyphens, and underscores.";
			}
		}
		
		if ($userEmail == '') {
			$errorArray['userEmail'][] = "The 'email' field is required.";
		} else {
			if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) { $errorArray['userEmail'][] = "That email address appears to be formatted incorrectly."; }
			if (User::emailInUse($userEmail)) { $errorArray['userEmail'][] = "That email address is already in use."; }		
		}

		if ($password == '') { $errorArray['password'][] = "The password field is required."; }
		if ($confirmPassword == '') { $errorArray['confirmPassword'][] = "The confirm password field is required."; }
		if ($password != '' && $confirmPassword != '' && $password != $confirmPassword) {
			$errorArray['passwords'][] = "The passwords you entered did not match.";
		}
		
		if (!$obFussyCat) { $errorArray['obFussyCat'][] = "Fussy cat is fussy."; }

		return $errorArray;
		
	}
	
	public static function isLoggedIn() {
		
		if (isset($_SESSION['userID']) && $_SESSION['userID'] != 0) { return true; } else { return false; }
		
	}

	public static function isSiteManager() {
		
		$userRole = new UserRole($_SESSION['siteID'],$_SESSION['userID']);
		if (self::isAdmin() || $userRole->getUserRole() == 'siteManager') { return true; } else { return false; }
	}
	
	public static function isAdmin() {
		if (in_array($_SESSION['userID'], Config::read('admin.userIdArray'))) { return true; } else { return false; }
	}
	
	public static function getUserRole($siteID = null, $userID = null) {

		if (!$siteID) { $siteID = $_SESSION['siteID']; }
		if (!$userID) { $userID = $_SESSION['userID']; }
		$userRole = new UserRole($siteID,$userID);
		return $userRole->getUserRole();
		
	}
	
}

?>