<?php

class SupportView {

	private $urlArray;
	private $inputArray;
	public $errorArray;
	
	public function __construct($urlArray = array(), $inputArray = array(),  $errorArray = array()) {

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
	}
	
	public function menuManager() {

		if (!Auth::isSiteManager()) { die("You must be logged in to access this resource."); }
	
		$h = "<div id=\"perihelionProject\" class=\"perihelionManagerContainer\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
							$h .= "<div class=\"card-header perihelionMenuPanelHeading\">";
								
							$h .= "</div>";
							$h .= "<div class=\"card-body\">";

							$h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";
			
		return $this->html = $h;
		
	}

}

?>