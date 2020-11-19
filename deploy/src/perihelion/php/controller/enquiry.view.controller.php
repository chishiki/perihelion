<?php

class EnquiryViewController {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;

	}
	
	public function getView() {
		
		
		$view = new EnquiryView($this->urlArray,$this->inputArray,$this->errorArray);
		
		if ($this->urlArray[1] == 'thank-you') {
			return $view->getMoreInfoThankYou();
		} else {
			return $view->getMoreInfoForm();
		}
		
	}
	
}

?>