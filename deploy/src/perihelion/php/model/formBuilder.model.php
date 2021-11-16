<?php

final class FormBuilderArguments {

	public string $formID;
	public array $formClasses;
	public string $formMethod;
	public string $formAction;
	public array $hiddenFields;
	public array $formRows;
	public array $formGroups;
	public array $submitButtons;

	public function __construct($formID, $formClasses = array(), $formMethod = 'post', $formAction = '') {

		$this->formID = $formID;
		$this->formClasses = $formClasses;
		$this->formMethod = $formMethod;
		$this->formAction = $formAction;
		$this->hiddenFields = array();
		$this->formRows = array();
		$this->formGroups = array();
		$this->submitButtons = array();

	}

	public function addHiddenField(
		string $id,
		string $name,
		string $value
	) {
		$this->hiddenFields[] = array(
			'id' => $id,
			'name' => $name,
			'value' => $value
		);
	}

	public function addFormRow(
		string $id,
		array $classes
	) {
		$this->formRows[] = array(
			'id' => $id,
			'classes' => $classes
		);
		return max(array_keys($this->formRows));
	}

	public function addFormGroup(
		string $rowID,
		array $groupClasses
	) {

	}

	public function addSubmitButton(
		string $type, // (submit, button, link)
		string $id = '',
		array $classes = array('btn', 'btn-block', 'btn-primary'),
		string $name = '',
		string $value = '',
		bool $disabled = false,
		array $data = array()
	) {
		$this->submitButtons[] = array(
			'type' => $type,
			'id' => $id,
			'classes' => $classes,
			'name' => $name,
			'value' => $value,
			'disabled' => $disabled,
			'data' => $data,
		);
	}

}

final class FormBuilderArgumentsButton {

	public string $type;
	public string $id;
	public array $classes;
	public string $name;
	public string $value;
	public bool $disabled;
	public array $data;

	public function __construct() {

		$this->type = 'submit'; // (submit, button, link)
		$this->id = '';
		$this->classes = array('btn', 'btn-block', 'btn-outline-primary');
		$this->name = '';
		$this->value = '';
		$this->disabled = false;
		$this->data = array();

	}
}

final class FormBuilderArgumentsInput {

	public string $type;
	public string $id;
	public string $name;
	public array $classes;
	public string $value;
	public bool $readonly;
	public bool $disabled;
	public string $label;
	public string $prepend;
	public string $append;

}

final class FormBuilderArgumentsOther {

}

?>