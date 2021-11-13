<?php

final class PerihelionTestViewController {

	private $loc;
	private $input;
	private $modules;
	private $errors;
	private $messages;

	public function __construct($loc, $input, $modules, $errors, $messages) {

		$this->loc = $loc;
		$this->input = $input;
		$this->modules = $modules;
		$this->errors = $errors;
		$this->messages = $messages;

	}

	public function getView() {

		if (!Auth::isLoggedIn() || !Auth::isAdmin()) { header("Location: /"); }

		$view = new PerihelionTestView($this->loc, $this->input, $this->modules, $this->errors, $this->messages);
		return $view->testView();


	}

}

?>