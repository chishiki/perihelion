<?php

class NewsletterController {

	private $urlArray;
	private $inputArray;
	private $moduleArray;
	private $errorArray;
	private $messageArray;
	
	public function __construct($urlArray, $inputArray, $moduleArray) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->moduleArray = $moduleArray;
		$this->errorArray = array();
		$this->messageArray = array();
		
	}
	
	public function setState() {

		if ($this->urlArray[0] == 'newsletter' && $this->urlArray[1] == 'subscribe') {
			
			if (!empty($this->inputArray)) {
				if (empty($this->errorArray)) {
					
					$subscription = new NewsletterSubscription();
					foreach ($this->inputArray AS $property => $value) { if (isset($subscription->$property)) { $subscription->$property = $value; } }
					if (!NewsletterSubscription::subscribed($_SESSION['siteID'], 0, $subscription->subscriberEmail)) {
						NewsletterSubscription::insert($subscription, false);
					}
					
					$successURL = "/" . Lang::prefix() . "newsletter/thank-you/";
					header("Location: $successURL");
					
				}
			}
			
		}
		
	}
	
	public function getErrors() {
		return $this->errorArray;
	}
	
	public function getMessages() {
		return $this->messageArray;
	}
	
}

?>