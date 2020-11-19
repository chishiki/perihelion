<?php

class Robots {
	
	private $siteID;
	private $index;
	private $follow;
	
	public function __construct($urlArray) {

		$this->siteID = $_SESSION['siteID'];
		$this->index = true;
		$this->follow = true;
		
		$site = new Site($this->siteID);
		if (!$site->siteIndexable) {
			$this->index = false;
			$this->follow = false;
		}

	}

	public function meta() {
		
		if ($this->index && $this->follow) {
			return 'INDEX, FOLLOW';
		} else {
			return 'NOINDEX, NOFOLLOW';
		}
		
	}
	
}

?>