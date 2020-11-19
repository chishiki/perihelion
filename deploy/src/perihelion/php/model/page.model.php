<?php

class Page {

	public function getPublishedPageArray() {
	
		$siteID = Site::getCurrentSiteID();
		
		$query = "SELECT entrySeoURL FROM perihelion_Content WHERE siteID = :siteID AND contentCategoryKey = 'page' AND entryPublished = 1";
	
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $siteID));
		
		$publishedPageArray = array();
		while ($row = $statement->fetch()) { $publishedPageArray[] = $row['entrySeoURL']; }
		return $publishedPageArray;
		
	}

}

?>