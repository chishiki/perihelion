<?php

class TileView {
	
	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
	}

	public function tileForm() {
		
		// $tile = new Tile($tileID);
		
		if (!empty($this->inputArray)) { foreach ($this->inputArray AS $key => $value) { if (isset($tile->$key)) { $tile->$key = $value; } } }
		
		$h = "<div id=\"perihelionTileForm\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";

							$h .= "<div class=\"card\" >";
								$h .= "<div class=\"card-header\"><div class=\"card-title\">" . Lang::getLang('tile') . "</div></div>";
								$h .= "<div class=\"card-body\">";


								$h .= "</div>";
							$h .= "</div>";

					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";

		return $h;

	}
	
	public function tileList() {
		
		$tileArray = Tile::tileArray($_SESSION['siteID']);
		
		$h = "<div id=\"perihelionTileList\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";

							$h .= "<div class=\"card\" >";
								$h .= "<div class=\"card-header\"><div class=\"card-title\">" . Lang::getLang('tiles') . "</div></div>";
								$h .= "<div class=\"card-body\">";

									$h .= "<div class=\"list-group\">";
										foreach($tileArray AS $tileID) {
											$tile = new Tile($tileID);
											$h .= "<a class=\"list-group-item\" href=\"/manager/tiles/update/" . $tileID . "/\">" . $tile->title() . "</a>";
										}
									$h .= "</div>";

								$h .= "</div>";
							$h .= "</div>";

					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";

		return $h;

	}

}

?>