<?php

class SupportViewController {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
	}
	
	public function getView() {
		
		if (!Auth::isLoggedIn()) { header("Location: /"); }
		
		$view = new SupportView($this->urlArray,$this->inputArray,$this->errorArray);
		
		// EXAMPLE
		// if ($this->urlArray[1] == 'something' && $this->urlArray[2] == 'else') {
			// return $submenu . $view->aaaaaaa();
		// } elseif ($this->urlArray[1] == 'else' && $this->urlArray[2] == 'something' && ctype_digit($this->urlArray[3])) {
			// return $submenu . $view->bbbbbbb();
		// } else {
			// return $submenu . $view->ccccccc();
		// }


	}
	
}

?>