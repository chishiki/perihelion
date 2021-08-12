<?php

class Page {

	public function getPublishedPageArray() {

    $query = 'SELECT entrySeoURL FROM perihelion_Content WHERE siteID = :siteID AND entryPublished = 1';

		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
		$statement->execute();
		
		$publishedPageArray = array();
		while ($row = $statement->fetch()) { $publishedPageArray[] = $row['entrySeoURL']; }
		return $publishedPageArray;
		
	}

}

?>