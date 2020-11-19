<?php

final class PrintView {

	private $loc;
	private $input;
	private $messages;
	private $errors;
	private $modules;

	public function __construct($loc = array(), $input = array(), $messages = array(), $errors = array(), $modules = array()) {

		$this->loc = $loc;
		$this->input = $input;
		$this->mesages = $messages;
		$this->errors = $errors;
		$this->modules = $modules;

	}

	public function page($markup) {

		$page ='<html><body>' . $markup . '</body></html>';
		return $page;

	}

 }

?>