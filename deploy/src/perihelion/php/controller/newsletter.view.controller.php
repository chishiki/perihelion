<?php

class NewsletterViewController {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	private $messageArray;
	
	public function __construct($urlArray, $inputArray, $errorArray, $messageArray) {

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		$this->messageArray = $messageArray;
		
	}
	
	public function getView() {
		
		if ($this->urlArray[1] == 'thank-you') {

			$view = new NewsletterView($this->urlArray,$this->inputArray,$this->errorArray,$this->messageArray);
			return $view->thankYouForSubscribing();

		}

		
	}
	
}

?>