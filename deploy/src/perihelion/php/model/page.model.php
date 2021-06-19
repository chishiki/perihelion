<?php

class Page {

	public function getPublishedPageArray() {

		$dt = new DateTime();

		$where = array();
		$where[] = 'siteID = :siteID';
		$where[] = 'entryPublishStartDate >= :entryPublishStartDate';
		$where[] = 'entryPublishEndDate <= :entryPublishEndDate';

		
		$query = 'SELECT entrySeoURL FROM perihelion_Content WHERE ' . implode(', ',$where) . ' ORDER BY entryPublishStartDate DESC';
	
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
		$statement->bindParam(':entryPublishStartDate', $dt->format('Y-m-d'), PDO::PARAM_STR);
		$statement->bindParam(':entryPublishEndDate', $dt->format('Y-m-d'), PDO::PARAM_STR);
		$statement->execute();
		
		$publishedPageArray = array();
		while ($row = $statement->fetch()) { $publishedPageArray[] = $row['entrySeoURL']; }
		return $publishedPageArray;
		
	}

}

?>