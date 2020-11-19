<?php

class Content extends ORM {
	
	public $contentID;
	public $siteID;
	public $entrySiteID;
	public $contentURL;
	public $entrySeoURL;
	public $contentCategoryKey;
	public $entryCategoryID;
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

		if ($contentID) {

			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Content WHERE contentID = :contentID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':contentID' => $contentID));
			if (!$row = $statement->fetch()) { die("Content [$contentID] does not exist."); }
			foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } }

		} else {

			$this->contentID = 0;
			$this->siteID = $_SESSION['siteID'];
			$this->entrySiteID = $_SESSION['siteID'];
			$this->contentURL = '';
			$this->entrySeoURL = '';
			$this->contentCategoryKey = '';
			$this->entryCategoryID = 0;
			$this->entrySubmittedByUserID = $_SESSION['userID'];
			$this->entrySubmissionDateTime = date('Y-m-d H:i:s');
			$this->entryPublishStartDate = '0000-00-00 00:00:00';
			$this->entryPublishEndDate = date('Y-m-d H:i:s', strtotime('+10 years'));
			$this->entryLastModified = date('Y-m-d H:i:s');
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

?>