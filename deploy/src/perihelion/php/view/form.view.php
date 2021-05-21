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

	public static function formGroupText(FormGroupTextParameters $arg) {

		$fgt = '
			<div class="form-group ' . implode(' ', $arg->classes) . '">
				<label for="' . $arg->id . '" class="col-form-label">' . Lang::getLang($arg->label) . '</label>
				<input type="' . $arg->type . '" id="' . $arg->id . '" name="' . $arg->name . '" class="form-control" placeholder="' . Lang::getLang($arg->placeholder) . '" value="' . $arg->value . '"' . ($arg->readonly?' readonly':'') . ($arg->disabled?' disabled':'') . '>
			</div>
		';

		return $fgt;

	}

	public static function formGroupCheckboxInline(FormGroupCheckboxInlineParameters $arg) {

		$fgci = '
		
			<div class="form-group ' . implode(' ', $arg->classes) . '">
				 <div class="checkbox-inline">
					<label for="' . $arg->id . '" class="col-form-label">
					<input type="checkbox" id="' . $arg->id . '" name="' . $arg->name . '" value="' . $arg->value . '"' . ($arg->checked?' checked':'') . ($arg->disabled?' disabled':'') . '>
					' . Lang::getLang($arg->label) . '
					</label>
				</div>
			</div>
		
		';

		return $fgci;

	}

	public static function formGroupNumberDropdown(FormGroupNumberDropdownParameters $arg) {

		$fgnd = '
		
			<div class="form-group ' . implode(' ', $arg->classes) . '">
				<label for="' . $arg->id . '" class="col-form-label">' . Lang::getLang($arg->label) . '</label>
				' . self::numberDropdown($arg->name, $arg->selectedValue, $arg->start, $arg->end, 'form-control'.($arg->size?' form-control-'.$arg->size:''), $arg->defaultOptionText) . '
			</div>
		
		';

		return $fgnd;

	}

}

final class FormGroupTextParameters {

	public $classes;
	public $label;
	public $type;
	public $id;
	public $name;
	public $placeholder;
	public $value;
	public $readonly;
	public $disabled;

	public function __construct(
		$classes = null,
		$label = null,
		$type = null,
		$id = null,
		$name = null,
		$placeholder = null,
		$value = null,
		$readonly = null,
		$disabled = null
	) {
		$this->classes = $classes;
		$this->label = $label;
		$this->type = $type;
		$this->id = $id;
		$this->name = $name;
		$this->placeholder = $placeholder;
		$this->value = $value;
		$this->readonly = $readonly;
		$this->disabled = $disabled;
	}

}

final class FormGroupCheckboxInlineParameters {

	public $classes;
	public $label;
	public $id;
	public $name;
	public $value;
	public $checked;
	public $disabled;

	public function __construct(
		$classes = null,
		$label = null,
		$id = null,
		$name = null,
		$value = null,
		$checked = null,
		$disabled = null
	) {
		$this->classes = $classes;
		$this->label = $label;
		$this->id = $id;
		$this->name = $name;
		$this->value = $value;
		$this->checked = $checked;
		$this->disabled = $disabled;
	}

}

final class FormGroupNumberDropdownParameters {

	public $classes;
	public $label;
	public $id;
	public $name;
	public $selectedValue;
	public $start;
	public $end;
	public $size;
	public $defaultOptionText;
	public $disabled;

	public function __construct(
		$classes = null,
		$label = null,
		$id = null,
		$name = null,
		$selectedValue = null,
		$start = null,
		$end = null,
		$size = null,
		$defaultOptionText = null,
		$disabled = null
	) {
		$this->classes = $classes;
		$this->label = $label;
		$this->id = $id;
		$this->name = $name;
		$this->selectedValue = $selectedValue;
		$this->start = $start;
		$this->end = $end;
		$this->size = $size;
		$this->defaultOptionText = $defaultOptionText;
		$this->disabled = $disabled;
	}

}

?>