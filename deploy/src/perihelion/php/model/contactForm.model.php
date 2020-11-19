<?php

class ContactForm extends ORM {

	public $contactFormID;
	public $siteID;
	public $companyNameEnglish;
	public $companyAddressEnglish;
	public $companyTelephoneEnglish;
	public $companyFaxEnglish;
	public $companyNameJapanese;
	public $companyAddressJapanese;
	public $companyTelephoneJapanese;
	public $companyFaxJapanese;
	public $displayLocationMap;
	public $locationMapZoom;
	public $locationMapLatitude;
	public $locationMapLongitude;
	public $displayOfficeHours;
	public $officeHoursEnglish;
	public $officeHoursJapanese;
	public $promptForPhoneNumber;
	public $promptForPreferredCorrespondence;
	public $promptForContactReason;
	public $promptForAreaOfInterest;
	public $promptForBudget;
	public $budgetCurrencyPrefix;

	public function __construct($contactFormID = null) {

		$this->contactFormID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->companyNameEnglish = '';
		$this->companyAddressEnglish = '';
		$this->companyTelephoneEnglish = '';
		$this->companyFaxEnglish = '';
		$this->companyNameJapanese = '';
		$this->companyAddressJapanese = '';
		$this->companyTelephoneJapanese = '';
		$this->companyFaxJapanese = '';
		$this->displayLocationMap = 0;
		$this->locationMapZoom = '';
		$this->locationMapLatitude = '47.755749';
		$this->locationMapLongitude = '-122.313465';
		$this->displayOfficeHours = 0;
		$this->officeHoursEnglish = '';
		$this->officeHoursJapanese = '';
		$this->promptForPhoneNumber = 0;
		$this->promptForPreferredCorrespondence = 0;
		$this->promptForContactReason = 0;
		$this->promptForAreaOfInterest = 0;
		$this->promptForBudget = 0;
		$this->budgetCurrencyPrefix = '';

		$nucleus = Nucleus::getInstance();
		$query = "SELECT * FROM perihelion_ContactForm WHERE siteID = :siteID LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $_SESSION['siteID']));
		if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (isset($this->$key)) { $this->$key = $value; } } }

	}
	
	
	
	public function companyName() {
		$companyName = '';
		if ($_SESSION['lang'] == 'en') { $companyName = $this->companyNameEnglish; }
		if ($_SESSION['lang'] == 'ja') { $companyName = $this->companyNameJapanese; }
		return $companyName;
	}
	
	public function address() {
		$address = '';
		if ($_SESSION['lang'] == 'en') { $address = $this->companyAddressEnglish; }
		if ($_SESSION['lang'] == 'ja') { $address = $this->companyAddressJapanese; }
		return $address;
	}
	
	public function telephone() {
		$telephone = '';
		if ($_SESSION['lang'] == 'en') { $telephone = $this->companyTelephoneEnglish; }
		if ($_SESSION['lang'] == 'ja') { $telephone = $this->companyTelephoneJapanese; }
		return $telephone;
	}
	
	public function fax() {
		$fax = '';
		if ($_SESSION['lang'] == 'en') { $fax = $this->companyFaxEnglish; }
		if ($_SESSION['lang'] == 'ja') { $fax = $this->companyFaxJapanese; }
		return $fax;
	}
	
	public function officeHours() {
		$officeHours = '';
		if ($_SESSION['lang'] == 'en') { $officeHours = $this->officeHoursEnglish; }
		if ($_SESSION['lang'] == 'ja') { $officeHours = $this->officeHoursJapanese; }
		return $officeHours;
	}
	
	public function coordinates() {
		return $this->locationMapLatitude . ',' . $this->locationMapLongitude;
	}

	public static function validate($input_array,$action,$id = null) {
		
		$error_array = array();

		// if (isset($input_array['xxxxxxx'])) {
			// if (!validation_check($input_array['xxxxxxx'])) {
				// $error_array['xxxxxxx'][] = Lang::getLang('error');
			// }
		// }

		return $error_array;
		
	}
	
}

?>