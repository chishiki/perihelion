<?php

class Lang extends ORM {

	public $langKey;
	public $enLang;
	public $enCount;
	public $jaLang;
	public $jaCount;
	public $langTimeStamp;

	public function __construct($langKey) {
		
		if ($langKey != '') {
		
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Lang WHERE langKey = :langKey LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':langKey' => $langKey));
			if (!$row = $statement->fetch()) { die("a language key is required: __construct('$langKey')"); }
			foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } }
			
		} else {

			$this->langKey = 0;
			$this->enLang = '';
			$this->enCount = 0;
			$this->jaLang = '';
			$this->jaCount = 0;
			$this->langTimeStamp = date('Y-m-d H:i:s');
			
		}
		
	}

	public static function getLang($langKey, $langSelector = 'session') {

		switch ($langSelector) {
			case 'en':
				$lang = 'en';
				break;
			case 'ja':
				$lang = 'ja';
				break;
			default:
				$lang = $_SESSION['lang'];
		}


		$m = new Memcached();
		$m->addServer('localhost', 11211);
		$cacheLangKey = 'lang_' . $_SESSION['siteID'] . '_' . $langKey . '_' . $lang; // 'lang_<siteID>_<langKey>_<lang>'

		$resource = $m->get($cacheLangKey);

		if($resource === false && $m->getResultCode() == Memcached::RES_NOTFOUND) {

			if ($lang == 'ja') { $lang = 'ja'; $langAttribute = "jaLang"; } else { $lang = 'en'; $langAttribute = "enLang"; }
			$nucleus = Nucleus::getInstance();
			$query = "SELECT $langAttribute AS resource FROM perihelion_Lang WHERE langKey = :langKey LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':langKey' => $langKey));
			if ($row = $statement->fetch()) { $resource = $row['resource']; } else { $resource = $langKey; }

			// check for exceptions
			$ex = new LanguageException($langKey,$_SESSION['siteID']);
			$exception = $ex->exception();
			if ($exception) { $resource = $exception; }

			$m->set($cacheLangKey, $resource);
			self::langCounterPlusOne($lang, $langKey);

		}

		return $resource;
		
	}
	
	public static function languageUrlPrefix() {

		if ($_SESSION['lang'] == 'ja') { $urlPrefix = 'ja/'; } else { $urlPrefix = ''; }
		return $urlPrefix;
		
	}
	
	public static function prefix() {
		return self::languageUrlPrefix();
	}
	
	public static function langCounterPlusOne($lang, $langKey) {
		
		$plusOneAttribute = $lang . "Count";
		$nucleus = Nucleus::getInstance();
		$query = "UPDATE perihelion_Lang SET $plusOneAttribute = $plusOneAttribute + 1 WHERE langKey = :langKey LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':langKey' => $langKey));

	}
	
	public static function getBrowserDefaultLanguage() {
		if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$lang = 'en';
		} else {
			$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		}
		if ($lang != 'ja') { $lang = 'en'; }
		return $lang;
	}
	
	public static function words() {
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT langKey FROM perihelion_Lang ORDER BY langKey ASC";
		$statement = $nucleus->database->prepare($query);
		$statement->execute();
		
		$words = array();
		while ($row = $statement->fetch()) { $words[] = $row['langKey']; }
		return $words;

	}
	
	public static function setLanguage($lang) {

		if($lang == 'ja') { $_SESSION['lang'] = 'ja'; } else { $_SESSION['lang'] = 'en'; }
		
	}
	
	public static function switchLanguageURL() {
		$currentURL = urldecode(preg_replace('/^%2F(en%2F|ja%2F)?/', '', urlencode($_SERVER['REQUEST_URI']), 1));
		$url = '/' . ($_SESSION['lang']!='ja'?'ja/':'') . $currentURL;
		return $url;
	}

	public static function switchLanguageAnchor() {
		if ($_SESSION['lang'] == 'en') {
			return '日本語';
		} else {
			return 'English';
		}
	}
	
}

?>