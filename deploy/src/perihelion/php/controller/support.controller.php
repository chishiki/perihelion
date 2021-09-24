<?php

final class SupportController implements StateControllerInterface {

	private $urlArray;
	private $inputArray;
	private $moduleArray;
	
	public function __construct($urlArray, $inputArray, $moduleArray) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->moduleArray = $moduleArray;
		$this->errorArray = array();
		$this->messageArray = array();
		
	}
	
	public function setState() {

	}
	
	public function getErrors() {
		return $this->errorArray;
	}
	
	public function getMessages() {
		return $this->messageArray;
	}
	
}

?>