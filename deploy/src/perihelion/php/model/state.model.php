<?php

final class State {
	
	public $loc;
	public $input;
	public $modules;
	public $errors;
	public $messages;
	public $console;
	
	public function __construct() {

		$this->loc = array();
		$this->input = array();
		$this->modules = array();
		$this->errors = array();
		$this->messages = array();
		$this->console = array();
		
	}

}

?>