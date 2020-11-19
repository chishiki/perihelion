<?php

class Map {

	public function __construct() {

	}
	
	public static function propertyMap() {

		$query = "SELECT propertyID FROM perihelion_Property WHERE propertyLatitude != '' ";
		$query .= "AND siteID = :siteID AND propertyLongitude != '' AND propertyIsForSale = 1 ";
		$query .= "AND propertySold = 0 AND propertyPublished = 1 AND propertyDisplay = 1";

		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':siteID',$_SESSION['siteID']);
		$statement->execute();
		
		$properties = array();
		while ($row = $statement->fetch()) { $properties[] = $row['propertyID']; }
		return $properties;
		
	}
	
	public static function googleApiKey() {
		
		$site = new Site($_SESSION['siteID']);
		if ($site->siteGoogleApiKey) {
			$key = $site->siteGoogleApiKey;
		} else {
			$key = Config::read('google.maps_api_key');
		}
		return $key;

	}

}

?>