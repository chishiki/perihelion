<?php

/*
CREATE TABLE `perihelion_Content` (
`contentID` int(8) NOT NULL AUTO_INCREMENT,
  `siteID` int(8) NOT NULL,
  `creator` int(12) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `deleted` int(1) NOT NULL,
  `entrySiteID` int(8) NOT NULL,
  `contentURL` varchar(100) NOT NULL,
  `entrySeoURL` varchar(255) NOT NULL,
  `contentCategoryKey` varchar(255) NOT NULL,
  `contentCategoryID` int(12) NOT NULL,
  `entrySubmittedByUserID` int(12) NOT NULL,
  `entrySubmissionDateTime` datetime NOT NULL,
  `entryPublishStartDate` date NOT NULL,
  `entryPublishEndDate` date NOT NULL,
  `entryLastModified` datetime NOT NULL,
  `entryTitleEnglish` varchar(255) NOT NULL,
  `entryTitleJapanese` varchar(255) NOT NULL,
  `entryContentEnglish` text NOT NULL,
  `entryContentJapanese` text NOT NULL,
  `entrySortOrder` int(8) NOT NULL,
  `pageID` int(8) NOT NULL,
  `entryPublished` int(1) NOT NULL,
  `entryViews` int(12) NOT NULL,
  `entryKeywordMeta` varchar(255) NOT NULL,
  `entryDescriptionMeta` varchar(255) NOT NULL,
  `contentMetaKeywordsEnglish` varchar(255) NOT NULL,
  `contentMetaKeywordsJapanese` varchar(255) NOT NULL,
  `contentMetaDescriptionEnglish` varchar(255) NOT NULL,
  `contentMetaDescriptionJapanese` varchar(255) NOT NULL,
  `contentDeleted` int(1) NOT NULL,
  `contentDeletedDate` datetime NOT NULL,
  `contentDeletedByUserID` int(12) NOT NULL,
  `contentLock` int(1) NOT NULL,
  PRIMARY KEY (`contentID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

*/

class Content extends ORM {
	
	public $contentID;
	public $siteID;
	public $creator;
	public $created;
	public $updated;
	public $deleted;
	public $entrySiteID;
	public $contentURL;
	public $entrySeoURL;
	public $contentCategoryKey;
	public $contentCategoryID;
	public $entrySubmittedByUserID;
	public $entrySubmissionDateTime;
	public $entryPublishStartDate;
	public $entryPublishEndDate;
	public $entryLastModified;
	public $entryTitleEnglish;
	public $entryTitleJapanese;
	public $entryContentEnglish;
	public $entryContentJapanese;
	public $entrySortOrder;
	public $pageID;
	public $entryPublished;
	public $entryViews;
	public $entryKeywordMeta;
	public $entryDescriptionMeta;
	public $contentMetaKeywordsEnglish;
	public $contentMetaKeywordsJapanese;
	public $contentMetaDescriptionEnglish;
	public $contentMetaDescriptionJapanese;
	public $contentDeleted;
	public $contentDeletedDate;
	public $contentDeletedByUserID;
	public $contentLock;
	
	public function __construct($contentID = null) {

		$dt = new DateTime();

		$dtPublishEndDate = new DateTime();
		$dtPublishEndDate->modify('+10 years');

		$this->contentID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->creator = $_SESSION['userID'];
		$this->created = $dt->format('Y-m-d H:i:s');
		$this->updated = $dt->format('Y-m-d H:i:s');
		$this->deleted = 0;
		$this->entrySiteID = $_SESSION['siteID'];
		$this->contentURL = '';
		$this->entrySeoURL = '';
		$this->contentCategoryKey = '';
		$this->contentCategoryID = 0;
		$this->entrySubmittedByUserID = $_SESSION['userID'];
		$this->entrySubmissionDateTime = $dt->format('Y-m-d H:i:s');
		$this->entryPublishStartDate = '0000-00-00 00:00:00';
		$this->entryPublishEndDate = $dtPublishEndDate->format('Y-m-d H:i:s');
		$this->entryLastModified = '0000-00-00 00:00:00';
		$this->entryTitleEnglish = '';
		$this->entryTitleJapanese = '';
		$this->entryContentEnglish = '';
		$this->entryContentJapanese = '';
		$this->entrySortOrder = 0;
		$this->pageID = 0;
		$this->entryPublished = 0;
		$this->entryViews = 0;
		$this->entryKeywordMeta = '';
		$this->entryDescriptionMeta = '';
		$this->contentMetaKeywordsEnglish = '';
		$this->contentMetaKeywordsJapanese = '';
		$this->contentMetaDescriptionEnglish = '';
		$this->contentMetaDescriptionJapanese = '';
		$this->contentDeleted = 0;
		$this->contentDeletedDate = '000-00-00 00:00:00';
		$this->contentDeletedByUserID = 0;
		$this->contentLock = 0;

		if ($contentID) {

			$where = array();

			$where[] = 'siteID = :siteID';
			$where[] = 'deleted = 0';
			$where[] = 'contentID = :contentID';

			$nucleus = Nucleus::getInstance();
			$query = 'SELECT * FROM perihelion_Content WHERE ' . implode(' AND ',$where) . ' LIMIT 1';

			$statement = $nucleus->database->prepare($query);
			$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
			$statement->bindParam(':contentID', $contentID, PDO::PARAM_INT);
			$statement->execute();

			if ($row = $statement->fetch()) {
				foreach ($row as $key => $value) { if (isset($this->$key)) { $this->$key = $value; } }
			}

		}

	}

	public function title() {
	
		$name = '';
		if ($_SESSION['lang'] == 'ja' && $this->entryTitleJapanese != '') { $name = $this->entryTitleJapanese; } else { $name = $this->entryTitleEnglish; }
		return $name;
		
	}
	
	public function content($plusOne = true) {
	
		$content = '';
		if ($_SESSION['lang'] == 'ja' && $this->entryContentJapanese != '') { $content = $this->entryContentJapanese; } else { $content = $this->entryContentEnglish; }
		if ($plusOne) { self::plusOne($this->contentID); }
		return $content;
		
	}
	
	public function keywords() {
	
		$name = '';
		if ($_SESSION['lang'] == 'ja' && $this->contentMetaKeywordsJapanese != '') { $name = $this->contentMetaKeywordsJapanese; } else { $name = $this->contentMetaKeywordsEnglish; }
		return $name;
		
	}
	
	public function description() {
	
		$name = '';
		if ($_SESSION['lang'] == 'ja' && $this->contentMetaDescriptionJapanese != '') { $name = $this->contentMetaDescriptionJapanese; } else { $name = $this->contentMetaDescriptionEnglish; }
		return $name;
		
	}
	
	public static function contentArray() {

		$siteID = $_SESSION['siteID'];
		
		$nucleus = Nucleus::getInstance();
		$query = "SELECT contentID FROM perihelion_Content  WHERE siteID = :siteID AND contentDeleted = 0 ORDER BY entryPublishStartDate ASC ";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $siteID));

		$contentArray = array();
		while ($row = $statement->fetch()) { $contentArray[] = $row['contentID']; }
		return $contentArray;

	}
	
	public static function publishedContentExists($entrySeoURL) {

		$nucleus = Nucleus::getInstance();
		$query = "SELECT contentID FROM perihelion_Content WHERE siteID = :siteID AND entrySeoURL = :entrySeoURL AND entryPublished = 1 LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $_SESSION['siteID'],':entrySeoURL' => $entrySeoURL));
		$exists = false;
		if ($row = $statement->fetch()) { $exists = true; }
		return $exists;
		
	}
	
	public static function publishedContentID($entrySeoURL) {

		$nucleus = Nucleus::getInstance();
		$query = "SELECT contentID FROM perihelion_Content WHERE siteID = :siteID AND entrySeoURL = :entrySeoURL AND entryPublished = 1 LIMIT 1";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $_SESSION['siteID'],':entrySeoURL' => $entrySeoURL));
		$contentID = 0;
		if ($row = $statement->fetch()) { $contentID = $row['contentID']; }
		return $contentID;
		
	}

	public static function plusOne($contentID) {
		
		$content = new Content($contentID);
		$content->entryViews ++;
		$conditions = array('contentID' => $contentID);
		$logThis = false;
		Content::update($content,$conditions,$logThis);
		
	}
	
}

final class ContentRouting {

	public function __construct($loc) {

		// is $loc[0] contentCategory?
		// is $loc[x] a contentCategory with parentContentCategoryID $loc[x-1]?
		// does $loc[n] correspond with a specific content item?

	}

}

?>