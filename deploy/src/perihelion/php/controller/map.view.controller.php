<?php

class MapViewController {

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

		$view = new MapView($this->urlArray, $this->inputArray, $this->errorArray);
	
		if ($this->urlArray[0] == 'map') {
			$pq = new PropertyQuery();
			$properties = Property::search($pq);
			return $view->map($properties);
		}
		
	}
	
}

?>