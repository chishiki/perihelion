<?php

class NewsletterSubscription extends ORM {

	public $siteID;
	public $newsletterID;
	public $subscriberEmail;
	public $subscribedDateTime;
	public $subscribedFromIP;
	public $subscriberName;
	public $subscriberVerified;
	
	public function __construct($newsletterID = 0, $subscriberEmail = '') {

		$this->siteID = $_SESSION['siteID'];
		$this->newsletterID = $newsletterID;
		$this->subscriberEmail = $subscriberEmail;
		$this->subscribedDateTime = date('Y-m-d H:i:s');
		$this->subscribedFromIP = $_SERVER['REMOTE_ADDR'];
		$this->subscriberName = '';
		$this->subscriberVerified = 0;
	
		if ($this->siteID && !empty($subscriberEmail)) {

			$nucleus = Nucleus::getInstance();
			
			$query = "SELECT * FROM perihelion_NewsletterSubscription ";
			$query .= "WHERE siteID = :siteID ";
			$query .= "AND newsletterID = :newsletterID ";
			$query .= "AND subscriberEmail = :subscriberEmail ";
			$query .= "LIMIT 1";
			
			$statement = $nucleus->database->prepare($query);
			$statement->bindParam(':siteID', $this->siteID, PDO::PARAM_INT);
			$statement->bindParam(':newsletterID', $newsletterID, PDO::PARAM_INT);
			$statement->bindParam(':subscriberEmail', $subscriberEmail, PDO::PARAM_STR);
			
			$statement->execute();
			
			if ($row = $statement->fetch()) {
				foreach ($row AS $key => $value) { if (isset($this->$key)) { $this->$key = $value; } }
			}
			
		}
	}

	public static function subscriberArray() {

		$siteID = $_SESSION['siteID'];
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT subscriberEmail FROM perihelion_NewsletterSubscription WHERE siteID = :siteID ORDER BY subscribedDateTime DESC ";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $siteID));

		$subscriberArray = array();
		while ($row = $statement->fetch()) { $subscriberArray[] = $row['subscriberEmail']; }
		return $subscriberArray;

	}
	
	public static function subscribed($siteID, $newsletterID, $subscriberEmail) {

		$nucleus = Nucleus::getInstance();
		$query = "SELECT * FROM perihelion_NewsletterSubscription WHERE siteID = :siteID AND newsletterID = :newsletterID AND subscriberEmail = :subscriberEmail LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $siteID, ':newsletterID' => $newsletterID, ':subscriberEmail' => $subscriberEmail));
		
		$subscribed = false;
		if ($statement->fetch()) { $subscribed = true; }
		return $subscribed;

	}

}

?>