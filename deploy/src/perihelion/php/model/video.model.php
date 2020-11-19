<?php

class Video extends ORM {

	public $videoID;
	public $siteID;
	public $videoSubmittedByUserID;
	public $videoSubmissionDateTime;
	public $videoURL;
	public $videoObject;
	public $videoObjectID;
	public $videoDisplay;
	public $videoDisplayOrder;
	public $videoAutoplay;
	public $videoControls;
	public $videoShowinfo;
	public $videoModestbranding;
	public $videoLoop;

	public function __construct($videoID = null) {

		$this->videoID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->videoSubmittedByUserID = $_SESSION['userID'];
		$this->videoSubmissionDateTime = date('Y-m-d H:i:s');
		$this->videoURL = '';
		$this->videoObject = '';
		$this->videoObjectID = 0;
		$this->videoDisplay = 0;
		$this->videoDisplayOrder = 0;
		$this->videoAutoplay = 0;
		$this->videoControls = 0;
		$this->videoShowinfo = 0;
		$this->videoModestbranding = 0;
		$this->videoLoop = 0;
	
		if ($videoID) {
		
			$nucleus = Nucleus::getInstance();
			$query = "SELECT * FROM perihelion_Video WHERE videoID = :videoID LIMIT 1";
			$statement = $nucleus->database->prepare($query);
			$statement->execute(array(':videoID' => $videoID));
			if ($row = $statement->fetch()) { foreach ($row AS $key => $value) { if (isset($this->$key)) { $this->$key = $value; } } }
			
		}
		
	}

	public static function videoArray($videoObject, $videoObjectID) {

		$nucleus = Nucleus::getInstance();
		$query = "SELECT videoID FROM perihelion_Video WHERE siteID = :siteID AND videoObject = :videoObject AND videoObjectID = :videoObjectID ORDER BY videoDisplayOrder ASC";
		$statement = $nucleus->database->prepare($query);
		$statement->execute(array(':siteID' => $_SESSION['siteID'], ':videoObject' => $videoObject, ':videoObjectID' => $videoObjectID));
		$objectVideoArray = array();
		while ($row = $statement->fetch()) { $objectVideoArray[] = $row['videoID']; }
		return $objectVideoArray;
		
	}
	
	public static function isYouTubeVideo($url) {
	    $videoID = 0;
	    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
	        $videoID = $match[1];
	    }
	    return $videoID;
	}
	
	public static function isVimeoVideo($url) {
	    $videoID = 0;
	    if (preg_match("/https?:\/\/(?:www\.)?vimeo\.com\/(\d{8,12})/", $url, $match)) {
	        $videoID = $match[1];
	    }
	    return $videoID;
	}

}

?>