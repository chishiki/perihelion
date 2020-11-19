<?php

class GeoAreaView {

	public $urlArray;
	public $inputArray;
	public $errorArray;
	
	public function __construct($urlArray = array(), $inputArray = array(),  $errorArray = array()) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
	}

	public static function geoDropdown($selectedGeoAreaKey = '', $required = false) {

		$geoAreas = GeoArea::geoAreas();
		
		$h = '<select class="form-control" name="geoAreaKey"' . ($required?' required':'') . '>';

			if (!$required)  {  $h .= '<option value="">' . Lang::getLang('geoAreaKey') . '</option>'; }

			foreach($geoAreas AS $geoAreaKey) {
				$geoArea = new GeoArea($geoAreaKey);
				$h .= '<option value="' . $geoArea->geoAreaKey . '"' . ($geoArea->geoAreaKey==$selectedGeoAreaKey?' selected':'') .  '>';
					$h .= $geoArea->name();
				$h .= '</option>';
			}

		$h .= '</select>';

		return $h;

	}

}

?>