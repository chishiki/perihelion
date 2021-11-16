<?php

final class FormBuilderView {

	private string $form;

	public function __construct(FormBuilderArguments $arg) {

		/*
		public string $form;
		public array $formClasses;
		public string $formMethod;
		public string $formAction;
		public array $hiddenFields;
		public array $displayedFields;
		public array $submitButtons;
		*/

		$this->form = '';

	}

	public function getForm() {

		return $this->form;

	}


}

?>