<?php

/*
CREATE TABLE `perihelion_Content` (
  `contentID` int(12) NOT NULL AUTO_INCREMENT,
  `siteID` int(12) NOT NULL,
  `creator` int(12) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `deleted` int(1) NOT NULL,
  `contentURL` varchar(100) NOT NULL,
  `entrySeoURL` varchar(255) NOT NULL,
  `contentCategoryID` int(12) NOT NULL,
  `contentCategoryType` varchar(20) NOT NULL,
  `entryPublished` int(1) NOT NULL,
  `entryPublishStartDate` date NOT NULL,
  `entryPublishEndDate` date NOT NULL,
  `entryTitleEnglish` varchar(255) NOT NULL,
  `entryTitleJapanese` varchar(255) NOT NULL,
  `entryContentEnglish` text NOT NULL,
  `entryContentJapanese` text NOT NULL,
  `entryViews` int(12) NOT NULL,
  `contentMetaKeywordsEnglish` varchar(255) NOT NULL,
  `contentMetaKeywordsJapanese` varchar(255) NOT NULL,
  `contentMetaDescriptionEnglish` varchar(255) NOT NULL,
  `contentMetaDescriptionJapanese` varchar(255) NOT NULL,
  `contentLock` int(1) NOT NULL,
  PRIMARY KEY (`contentID`)
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8mb4

*/

final class Content extends ORM {
	
	public $contentID;
	public $siteID;
	public $creator;
	public $created;
	public $updated;
	public $deleted;
	public $contentURL;
	public $entrySeoURL;
	public $contentCategoryID;
	public $contentCategoryType;
	public $entryPublished;
	public $entryPublishStartDate;
	public $entryPublishEndDate;
	public $entryTitleEnglish;
	public $entryTitleJapanese;
	public $entryContentEnglish;
	public $entryContentJapanese;
	public $entryViews;
	public $contentMetaKeywordsEnglish;
	public $contentMetaKeywordsJapanese;
	public $contentMetaDescriptionEnglish;
	public $contentMetaDescriptionJapanese;
	public $contentLock;
	public $contentClasses;
	public $includeOnSitemap;
	public $authenticatedUsersOnly;

	public function __construct($contentID = null) {

		$dt = new DateTime();
		$randomKey = Utilities::generateUniqueKey();

		$dtPublishStartDate = new DateTime();
		$dtPublishEndDate = new DateTime();
		$dtPublishEndDate->modify('+10 years');

		$this->contentID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->creator = $_SESSION['userID'];
		$this->created = $dt->format('Y-m-d H:i:s');
		$this->updated = $dt->format('Y-m-d H:i:s');
		$this->deleted = 0;
		$this->contentURL = 'content-url-' . $randomKey;
		$this->entrySeoURL = 'entry-seo-url-' . $randomKey; // this feels redundant
		$this->contentCategoryID = 0;
		$this->contentCategoryType = 'page';
		$this->entryPublished = 0;
		$this->entryPublishStartDate = $dtPublishStartDate->format('Y-m-d');
		$this->entryPublishEndDate = $dtPublishEndDate->format('Y-m-d');
		$this->entryTitleEnglish = '';
		$this->entryTitleJapanese = '';
		$this->entryContentEnglish = '';
		$this->entryContentJapanese = '';
		$this->entryViews = 0;
		$this->contentMetaKeywordsEnglish = '';
		$this->contentMetaKeywordsJapanese = '';
		$this->contentMetaDescriptionEnglish = '';
		$this->contentMetaDescriptionJapanese = '';
		$this->contentLock = 0;
		$this->contentClasses = '{"id":null,"container":"container","row":"row","col":"col-12"}';
		$this->includeOnSitemap = 1;
		$this->authenticatedUsersOnly = 0;

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
		if ($plusOne) { $this->plusOne($this->contentID); }
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

	public function plusOne($contentID) {

		$this->entryViews ++;
		$conditions = array('contentID' => $this->contentID);
		self::update($this, $conditions, false);
		
	}
	
}

final class ContentList {

	private $content;

	public function __construct(ContentListParameters $arg) {

		$this->content = array();

		$where = array();

		$where[] = 'deleted = 0';

		if ($arg->siteID) { $where[] = 'siteID = :siteID'; }
		if ($arg->contentID) { $where[] = 'contentID = :contentID'; }
		if ($arg->contentURL) { $where[] = 'contentURL = :contentURL'; }
		if ($arg->seoEntryURL) { $where[] = 'seoEntryURL = :seoEntryURL'; }
		if ($arg->contentCategoryID) { $where[] = 'contentCategoryID = :contentCategoryID'; }
		if ($arg->contentCategoryType) { $where[] = 'contentCategoryType = :contentCategoryType'; }
		if ($arg->contentPublished === true) { $where[] = 'contentPublished = 1'; }
		if ($arg->contentPublished === false) { $where[] = 'contentPublished = 0'; }
		if ($arg->contentPublishedDateCheck) { $where[] = '(contentPublishedStartDate <= :contentPublishedDateCheck AND contentPublishedEndDate >= :contentPublishedDateCheck)'; }
		if ($arg->contentLock === true) { $where[] = 'contentLock = 1'; }
		if ($arg->contentLock === false) { $where[] = 'contentLock = 0'; }

		$search = array();
		$search[] = 'entryTitleEnglish LIKE CONCAT("%",:contentSearchString,"%")';
		$search[] = 'entryTitleJapanese LIKE CONCAT("%",:contentSearchString,"%")';
		$search[] = 'entryContentEnglish LIKE CONCAT("%",:contentSearchString,"%")';
		$search[] = 'entryContentJapanese LIKE CONCAT("%",:contentSearchString,"%")';

		if ($arg->contentSearchString) { $where[] = '(' . implode(' OR ', $search) . ')'; }

		$orderBy = array();
		foreach ($arg->orderBy AS $field => $sort) { $orderBy[] = $field . ' ' . $sort; }

		switch ($arg->resultSet) {
			case 'robust': $selector = '*'; break;
			default: $selector = 'contentID';
		}

		$query = 'SELECT ' . $selector . ' FROM perihelion_Content WHERE ' . implode(' AND ',$where) . ' ORDER BY ' . implode(', ',$orderBy);
		if ($arg->limit) { $query .= ' LIMIT ' . ($arg->offset?$arg->offset.', ':'') . $arg->limit; }

		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);

		if ($arg->siteID) { $statement->bindParam(':siteID', $arg->siteID, PDO::PARAM_INT); }
		if ($arg->contentID) { $statement->bindParam(':contentID', $arg->contentID, PDO::PARAM_INT); }
		if ($arg->contentURL) { $statement->bindParam(':contentURL', $arg->contentURL, PDO::PARAM_STR); }
		if ($arg->seoEntryURL) { $statement->bindParam(':seoEntryURL', $arg->seoEntryURL, PDO::PARAM_STR); }
		if ($arg->contentCategoryID) { $statement->bindParam(':contentCategoryID', $arg->contentCategoryID, PDO::PARAM_INT); }
		if ($arg->contentCategoryType) { $statement->bindParam(':contentCategoryType', $arg->contentCategoryType, PDO::PARAM_STR); }
		if ($arg->contentPublishedDateCheck) { $statement->bindParam(':contentPublishedDateCheck', $arg->contentPublishedDateCheck, PDO::PARAM_STR); }
		if ($arg->contentSearchString) { $statement->bindParam(':contentSearchString', $arg->contentSearchString, PDO::PARAM_STR); }

		$statement->execute();

		while ($row = $statement->fetch()) {
			if ($arg->resultSet == 'robust') { $this->content[] = $row; }
			else { $this->content[] = $row['contentID']; }
		}

	}

	public function content(): array {
		return $this->content;
	}

	public function contentCount(): int {
		return count($this->content);
	}

}

final class ContentListParameters {

	// data filters
	public $siteID;
	public $contentID;
	public $contentURL;
	public $seoEntryURL;
	public $contentCategoryID;
	public $contentCategoryType;
	public $contentPublished;
	public $contentPublishedDateCheck;
	public $contentLock;
	public $contentSearchString;

	// sorting and pagination
	public $resultSet;
	public $orderBy;
	public $limit;
	public $offset;

	public function __construct() {

		// data filters
		$this->siteID = $_SESSION['siteID'];
		$this->contentID = null;
		$this->contentURL = null;
		$this->seoEntryURL = null;
		$this->contentCategoryID = null;
		$this->contentCategoryType = null;
		$this->contentPublished = null; // [null => either; true => published; false => not published]
		$this->contentPublishedDateCheck = null;
		$this->contentLock = null; // [null => either; true => locked; false => not locked]
		$this->contentSearchString = null;

		// sorting and pagination
		$this->resultSet = 'id'; // [id|robust]
		$this->orderBy = array('contentID' => 'DESC');
		$this->limit = null;
		$this->offset = null;

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