<?php

final class DevView {

	private $loc;
	private $input;
	private $errors;
	private $messages;

	public function __construct($loc = array(), $input = array(), $errors = array(), $messages = array()) {

		$this->loc = $loc;
		$this->input = $input;
		$this->errors = $errors;
		$this->messages = $messages;

		$role = Auth::getUserRole();
		if ($role != 'siteAdmin') { die("You do not have permissions sufficient to view the dev module."); }

	}

	public function compileFileView() {

		$arg = new CodeGeneratorArguments();
		$codeGenerator = new CodeGenerator($arg);
		$body = '<pre>' . $codeGenerator->compileFile() . '</pre>';
		$card = new CardView('compile_file_view', array('container-fluid'), '', array('col-12'), 'COMPILE FILE', $body, false);
		return $card->card();

	}

}

?>