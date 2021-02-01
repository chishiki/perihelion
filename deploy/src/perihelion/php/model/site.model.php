<?php

class Site extends ORM {

	public $siteID;
	public $siteKey;
	public $siteURL;
	public $siteManagerUserID;						
	public $siteIndexable;
	public $siteTitleEnglish;
	public $siteKeywordsEnglish;
	public $siteDescriptionEnglish;
	public $siteTitleJapanese;
	public $siteKeywordsJapanese;
	public $siteDescriptionJapanese;
	public $siteGoogleAnalyticsID;
	public $siteGoogleAdSenseID;
	public $siteGoogleApiKey;
	public $siteUsesGoogleMaps;
	public $themeID;
	public $siteTwitter;
	public $siteFacebook;
	public $siteLinkedIn;
	public $sitePinterest;
	public $siteInstagram;
	public $siteSkype;
	public $siteAutomatedEmailAddress;
	public $siteAutomatedEmailSenderName;
	public $siteContactFormToAddress;
	public $siteNavMenuID;
	public $siteIndexContentID;
	public $siteHeaderContentID;
	public $siteFooterContentID;
	public $siteIso639;
	public $siteIso4217;
	public $siteDeploymentDate;
	public $sitePagesServed;
	public $siteLangJapanese;
	public $siteIsDevInstance;
	public $taxRate;
	public $pingdomStatus;
	public $pingdomReport;
	public $siteDefaultTimeZone;

	public function __construct($siteID = 0) {

		$this->siteID = 0;
		$this->siteKey = '';
		$this->siteURL = '';
		$this->siteManagerUserID = 0;						
		$this->siteIndexable = 0;
		$this->siteTitleEnglish = '';
		$this->siteKeywordsEnglish = '';
		$this->siteDescriptionEnglish = '';
		$this->siteTitleJapanese = '';
		$this->siteKeywordsJapanese = '';
		$this->siteDescriptionJapanese = '';
		$this->siteGoogleAnalyticsID = '';
		$this->siteGoogleAdSenseID = '';
		$this->siteGoogleApiKey = '';
		$this->siteUsesGoogleMaps = 0;
		$this->themeID = 0;
		$this->siteTwitter = '';
		$this->siteFacebook = '';
		$this->siteLinkedIn = '';
		$this->sitePinterest = '';
		$this->siteInstagram = '';
		$this->siteSkype = '';
		$this->siteAutomatedEmailAddress = '';
		$this->siteAutomatedEmailSenderName = '';
		$this->siteContactFormToAddress = '';
		$this->siteNavMenuID = 0;
		$this->siteIndexContentID = 0;
		$this->siteHeaderContentID = 0;
		$this->siteFooterContentID = 0;
		$this->siteIso639 = 'en';
		$this->siteIso4217 = 'usd';
		$this->siteDeploymentDate = 0;
		$this->sitePagesServed = 0;
		$this->siteLangJapanese = 0;
		$this->siteIsDevInstance = 0;
		$this->taxRate = 0;
		$this->pingdomStatus = '';
		$this->pingdomReport = '';
		$this->siteDefaultTimeZone = 'UTC';

		if ($siteID != 0) {
		
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Site WHERE siteID = :siteID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':siteID' => $siteID));
			if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (isset($this->$key)) { $this->$key = $value; } } }
			
		}
	}
	
	public function getTitle() {
	
		if ($_SESSION['lang'] == 'ja') {
			if ($this->siteTitleJapanese != '') { $siteTitle = $this->siteTitleJapanese; } else { $siteTitle = $this->siteTitleEnglish; }
		} else {
			if ($this->siteTitleEnglish != '') { $siteTitle = $this->siteTitleEnglish; } else { $siteTitle = $this->siteTitleJapanese; }
		}
		return $siteTitle;
		
	}
	
	public function getKeywords() {
		
		if ($_SESSION['lang'] == 'ja') {
			if ($this->siteKeywordsJapanese != '') { $siteKeywords = $this->siteKeywordsJapanese; } else { $siteKeywords = $this->siteKeywordsEnglish; }
		} else {
			if ($this->siteKeywordsEnglish != '') { $siteKeywords = $this->siteKeywordsEnglish; } else { $siteKeywords = $this->siteKeywordsJapanese; }
		}
		return $siteKeywords;
		
	}
	
	public function getDescription() {
		
		if ($_SESSION['lang'] == 'ja') {
			if ($this->siteDescriptionJapanese != '') { $siteDescription = $this->siteDescriptionJapanese; } else { $siteDescription = $this->siteDescriptionEnglish; }
		} else {
			if ($this->siteDescriptionEnglish != '') { $siteDescription = $this->siteDescriptionEnglish; } else { $siteDescription = $this->siteDescriptionJapanese; }
		}
		return $siteDescription;
		
	}
	
	public function getBusinessName() {
		
		if ($_SESSION['lang'] == 'ja') {
			if ($this->siteBusinessNameJapanese != '') { $siteBusinessName = $this->siteBusinessNameJapanese; } else { $siteBusinessName = $this->siteBusinessNameEnglish; }
		} else {
			if ($this->siteBusinessNameEnglish != '') { $siteBusinessName = $this->siteBusinessNameEnglish; } else { $siteBusinessName = $this->siteBusinessNameJapanese; }
		}
		return $siteBusinessName;
		
	}
	
	public function getBusinessAddress() {
		
		if ($_SESSION['lang'] == 'ja') {
			if ($this->siteBusinessAddressJapanese != '') { $siteBusinessAddress = $this->siteBusinessAddressJapanese; } else { $siteBusinessAddress = $this->siteBusinessAddressEnglish; }
		} else {
			if ($this->siteBusinessAddressEnglish != '') { $siteBusinessAddress = $this->siteBusinessAddressEnglish; } else { $siteBusinessAddress = $this->siteBusinessAddressJapanese; }
		}
		return $siteBusinessAddress;
		
	}
	
	public function hasPublicProjects() {
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT * FROM perihelion_Project WHERE siteID = :siteID AND projectIsPublic = 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $this->siteID));
		$row = $statement->fetch();
		if (!empty($row)) { return true; } else { return false; }
		
	}
	
	public function businessName($lang = null) {

		switch($lang) {

			case('en'):
			
				return $this->siteBusinessNameEnglish;
				break;
				
			case('ja'):
			
				return $this->siteBusinessNameJapanese;
				break;
				
			default:
			
				if ($_SESSION['lang'] == 'ja' && $this->siteBusinessNameJapanese != '') {
					return $this->siteBusinessNameJapanese;
				} else {
					return $this->siteBusinessNameEnglish;
				}
				
		}

	}
	
	public function businessAddress($lang = null) {

		switch($lang) {

			case('en'):
			
				return $this->siteBusinessAddressEnglish;
				break;
				
			case('ja'):
			
				return $this->siteBusinessAddressJapanese;
				break;
				
			default:
			
				if ($_SESSION['lang'] == 'ja' && $this->siteBusinessAddressJapanese != '') {
					return $this->siteBusinessAddressJapanese;
				} else {
					return $this->siteBusinessAddressEnglish;
				}
				
		}

	}
	
	public function featuredPropertyWidgetTitle() {
		if ($_SESSION['lang'] == 'ja' && $this->indexDisplayFeaturedPropertyWidgetTitleJapanese != '') {
			$featuredPropertyWidgetTitle = $this->indexDisplayFeaturedPropertyWidgetTitleJapanese;
		} else {
			$featuredPropertyWidgetTitle = $this->indexDisplayFeaturedPropertyWidgetTitleEnglish;
		}
		return $featuredPropertyWidgetTitle;
	}
	
	public function propertySearchWidgetTitle() {
		if ($_SESSION['lang'] == 'ja' && $this->indexDisplayPropertySearchWidgetTitleJapanese != '') {
			$propertySearchWidgetTitle = $this->indexDisplayPropertySearchWidgetTitleJapanese;
		} else {
			$propertySearchWidgetTitle = $this->indexDisplayPropertySearchWidgetTitleEnglish;
		}
		return $propertySearchWidgetTitle;
	}

	
	/* STATIC METHODS */
	
	public static function getSiteList() {
		
		$query = "SELECT siteID FROM perihelion_Site ORDER BY siteKey ASC";
		
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->execute();
		
		$sites = array();
		while ($row = $statement->fetch()) { $sites[] = $row['siteID']; }
		return $sites;
		
	}
	
	public static function getCurrentSiteID() {
		
		$siteID = Config::read('default.site');
		$host = $_SERVER['HTTP_HOST'];
		
		if ($host != 'localhost') {

			$siteURL = preg_replace('/^www./', '', $host);
			$query = "SELECT siteID FROM perihelion_Site WHERE siteURL = :siteURL LIMIT 1";
			$nucleus = Nucleus::getInstance();
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':siteURL' => $siteURL));
			while ($row = $statement->fetch()) { $siteID = $row['siteID']; }
			
		}
		
		return $siteID;
		
	}
	
	public static function siteKeyInUse($siteKey) {
	
		$nucleus = Nucleus::getInstance();
		$query = "SELECT siteKey FROM perihelion_Site WHERE siteKey = :siteKey LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteKey' => $siteKey));
		if ($row = $statement->fetch()) { return true; } else { return false; }
	
	}
	
	public static function siteKeyIsReserved($siteKey) {
		
		$reservedSiteKeys = array(
			'about', 
			'admin', 
			'blog', 
			'cloud',
			'connect', 
			'console', 
			'contact', 
			'customers', 
			'data', 
			'db', 
			'dev', 
			'domains', 
			'dns', 
			'faq', 
			'ftp', 
			'git', 
			'groups', 
			'help', 
			'hosting', 
			'images', 
			'imap', 
			'inbox', 
			'int', 
			'jaga', 
			'k', 
			'mail', 
			'member', 
			'my',
			'mysql', 
			'news', 
			'owners', 
			'pop', 
			'pop3', 
			'prod', 
			'qa', 
			'repo', 
			'sales', 
			'sandbox', 
			'secure', 
			'smtp', 
			'sql', 
			'support', 
			'tasks', 
			'the', 
			'users', 
			'wiki', 
			'www'
		);
		
		if (in_array($siteKey,$reservedSiteKeys)) { return true; } else { return false; }
		
	}

	public static function currencySymbol() {
		
		$site = new self($_SESSION['siteID']);

		switch ($site->siteIso4217) {
			case('JPY'):
				$currencyCode = '&yen;';
				break;
			case('USD'):
				$currencyCode = '&dollar;';
				break;
			default:
				$currencyCode = '?';
		}
		
		return $currencyCode;
		
	}
	
	public static function url() {
		
		$site = new self($_SESSION['siteID']);
		return $site->siteURL;
		
	}
	
	public static function siteID() {
		
		$siteID = Config::read('default.site');
		$httpHost = $_SERVER['HTTP_HOST'];
		
		if ($httpHost != 'localhost') {
			
			$siteURL = strtolower(preg_replace('/^www./', '', $httpHost));
			$subDomain = explode('.', $httpHost)[0];
			$domain = explode('.', $httpHost)[1];
			if ($subDomain == 'perihelion' && $domain != 'zenidev') { $subDomain = $domain; }
			$nucleus = Nucleus::getInstance();
			$query = "SELECT siteID FROM perihelion_Site WHERE siteURL = :siteURL OR siteKey = :subDomain LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':siteURL' => $siteURL, ':subDomain' => $subDomain));
			if ($row = $statement->fetch()) { $siteID = $row['siteID']; }
			
		}

		return $siteID;
		
	}
	
	public static function propertyManagementSites() {

		if (isset($_SESSION['propertyManagementSites'])) {
			
			return $_SESSION['propertyManagementSites'];
			
		} else {
			
			$nucleus = Nucleus::getInstance();
			$query = "SELECT siteID FROM perihelion_Site WHERE siteUsesPropertyManagement = 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute();
			
			$sites = array();
			while ($row = $statement->fetch()) { $sites[] = $row['siteID']; }
			$_SESSION['propertyManagementSites'] = $sites;
			return $sites;
			
		}
		
	}
	
	public static function accommodationSites() {

		if (isset($_SESSION['accommodationSites'])) {
			
			return $_SESSION['accommodationSites'];
			
		} else {
			
			$nucleus = Nucleus::getInstance();
			$query = "SELECT siteID FROM perihelion_Site WHERE siteUsesAccommodation = 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute();
			
			$sites = array();
			while ($row = $statement->fetch()) { $sites[] = $row['siteID']; }
			$_SESSION['accommodationSites'] = $sites;
			return $sites;
		
		}
		
	}
	
	public static function developmentSites() {

		if (isset($_SESSION['developmentSites'])) {
			
			return $_SESSION['developmentSites'];
			
		} else {
			
			$nucleus = Nucleus::getInstance();
			$query = "SELECT siteID FROM perihelion_Site WHERE siteIsDevInstance = 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute();
			
			$sites = array();
			while ($row = $statement->fetch()) { $sites[] = $row['siteID']; }
			$_SESSION['developmentSites'] = $sites;
			return $sites;
		
		}
		
	}
	
	public static function validate($input_array,$action,$id = null) {
		
		$error_array = array();

		if (isset($input_array['siteContactFormToAddress'])) {
			if (!Utilities::isValidEmail($input_array['siteContactFormToAddress'])) {
				$error_array['siteContactFormToAddress'][] = Lang::getLang('emailIsInvalid');
			}
		}
		
		if (isset($input_array['siteAutomatedEmailAddress'])) {
			if (!Utilities::isValidEmail($input_array['siteAutomatedEmailAddress'])) {
				$error_array['siteAutomatedEmailAddress'][] = Lang::getLang('emailIsInvalid');
			}
		}
		
		return $error_array;
		
	}
	
}

	
?>