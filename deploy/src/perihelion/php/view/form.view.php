<?php

class FormElements {

	public static function numberDropdown($name, $selectedNumber, $startNumber = 0, $endNumber = 100, $classAttribute = 'form-control', $defaultOptionText = '') {

		$html = "<select class=\"" . $classAttribute . "\" name=\"" . $name . "\">";
		
			if ($defaultOptionText != '') {
				$html .= "<option value=\"0\">" . Lang::getLang($defaultOptionText) . "</option>";
				if ($startNumber == 0) { $startNumber++; }
			}
			
			for ($i = $startNumber; $i <= $endNumber; $i++) {
				$html .= "<option value=\"$i\"";
					if ($i == $selectedNumber) { $html .= " selected"; }
				$html .= ">$i</option>";
			}
		$html .= "</select>";
		return $html;
		
	}
	
	public static function dateInput($name = 'dateInput', $selectedDate = null) {

		if (!$selectedDate) { $selectedDate = ''; }
		
		return "
		<div class=\"input-group\">
			<div class=\"input-group-addon\"><span class=\"fas fa-calendar-alt\"></span></div>
			<input type=\"date\" class=\"form-control\" name=\"" . $name . "\" value=\"" . $selectedDate . "\">
		</div>
		";
		
	}
	
	public static function fieldErrorMessages($fieldErrorArray) {
		
		$html = '';
		foreach ($fieldErrorArray AS $errorMessage) {
			$html .= "<div class=\"text-danger\"><small>" . $errorMessage . "</small></div>";
		}
		return $html;
		
	}

}

?>