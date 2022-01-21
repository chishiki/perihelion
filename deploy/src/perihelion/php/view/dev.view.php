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

		$body = '<button type="button" class="btn btn-outline-secondary btn-sm clippy float-right" data-clippable-id="model_code"><span class="far fa-copy"></span></button>';
		$body .= '<pre id="model_code" class="clippable">' . $codeGenerator->compileModelFile() . '</pre>';
		$modelFileCard = new CardView('compile_model_file', array('container-fluid'), '', array('col-12'), 'COMPILE MODEL FILE', $body, true);

		$body = '<button type="button" class="btn btn-outline-secondary btn-sm clippy float-right" data-clippable-id="view_code"><span class="far fa-copy"></span></button>';
		$body .= '<pre id="view_code" class="clippable">' . $codeGenerator->compileViewFile() . '</pre>';
		$viewFileCard = new CardView('compile_view_file', array('container-fluid'), '', array('col-12'), 'COMPILE VIEW FILE', $body, true);

		$body = '<button type="button" class="btn btn-outline-secondary btn-sm clippy float-right" data-clippable-id="controller_code"><span class="far fa-copy"></span></button>';
		$body .= '<pre id="controller_code" class="clippable">' . $codeGenerator->compileViewFile() . '</pre>';
		$controllerFileCard = new CardView('compile_controller_file', array('container-fluid'), '', array('col-12'), 'COMPILE CONTROLLER FILE', $body, true);

		return $modelFileCard->card(); /* . $viewFileCard->card() . $controllerFileCard->card() */

	}

}

?>