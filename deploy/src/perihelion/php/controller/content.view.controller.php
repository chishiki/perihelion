<?php

class ContentViewController {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;

	}
	
	public function getView() {

		$contentURLs = array('privacy','tos');
		
		if (in_array($this->urlArray[0],$contentURLs)) { 

			$contentID = 0;

			switch ($this->urlArray[0]) {
				case('privacy'):
					$contentID = Config::read('privacy.content.id');
					break;
				case('tos'):
					$contentID = Config::read('terms.content.id');
					break;
			}

			if ($contentID) {
				$content = new Content($contentID);
				return $content->content();
			} else {
				$notFound = new NotFoundViewController($this->urlArray, $this->inputArray, $this->errorArray);
				return $notFound->getView();
			}

		} elseif (Content::publishedContentExists($this->urlArray[0])) {

			$view = new ContentView($this->urlArray,$this->inputArray,$this->errorArray);
			return $view->easyContent();
			
		}
		
	}
	
}

?>