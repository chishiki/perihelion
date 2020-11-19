<?php

class ScriptView {

	private $urlArray;
	private $inputArray;
	public $errorArray;

	public function __construct($urlArray, $inputArray, $errorArray) {
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
	}

	public function scriptForm($action, $scriptID = null) {
		
		if ($action == 'update' && $scriptID) {
			$script = new Script($scriptID);
			$actionURL = '/' . Lang::languageUrlPrefix() . 'designer/scripts/update/' . $scriptID . '/';
		} else {
			$script = new Script();
			$actionURL = '/' . Lang::languageUrlPrefix() . 'designer/scripts/create/';
		}
		if (!empty($this->inputArray)) { foreach ($this->inputArray AS $key => $value) { if (isset($script->$key)) { $script->$key = $value; } } }
		
		$h = "<div id=\"perihelionScriptForm\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
							$h .= "<div class=\"card-header\"><div class=\"card-title\">" . Lang::getLang('script') . "</div></div>";
							$h .= "<div class=\"card-body\">";

								$h .= '
								
								<form role="form" class="form-horizontal" action="' . $actionURL . '" method="post">

									' . ($action=='update'&&$scriptID?'<input type="hidden" name="scriptID" value="'.$scriptID.'">':'') . '
									
									<div class="form-group row">
										<label class="col-sm-2 col-form-label" for="scriptName">' . Lang::getLang('scriptName') . '</label>
										<div class="col-sm-4">
											<input type="text" id="scriptName" name="scriptName" class="form-control" placeholder="Script Name" value="' . $script->scriptName . '">
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-2 col-form-label" for="scriptCode">' . Lang::getLang('scriptCode') . '</label>
										<div class="col-sm-10"><textarea class="form-control" rows="15" id="scriptCode" name="scriptCode" placeholder="valid Javascript and jQuery only">' . $script->scriptCode . '</textarea></div>
									</div>

									<div class="form-group row">
										<label class="col-sm-2 col-form-label" for="scriptPosition">' . Lang::getLang('scriptPosition') . '</label>
										<div class="col-sm-4">
											<select class="form-control" id="scriptPosition" name="scriptPosition">
												<option value="header"'.($script->scriptPosition=='header'?" selected":"").'>' . Lang::getLang('header') . '</option>
												<option value="footer"'.($script->scriptPosition=='footer'?" selected":"").'>' . Lang::getLang('footer') . '</option>
											</select>
										</div>
									</div>
									
									
									<div class="form-group row">
										<label class="col-sm-2 col-form-label" for="scriptOrder">' . Lang::getLang('scriptOrder') . '</label>
										<div class="col-sm-4">' . FormElements::numberDropdown('scriptOrder', $script->scriptOrder, 1, 100) . '</div>
									</div>
									
									<div class="form-group row">
										<label class="col-sm-2 col-form-label" for="scriptEnabled">' . Lang::getLang('scriptEnabled') . '</label>
										<div class="col-sm-10"><input name="scriptEnabled" type="checkbox" value="1"' . ($script->scriptEnabled?" checked":"") . '></div>
									</div>
									
									<div class="form-group row">
										<div class="col-sm-12 text-right"><button type="submit" class="btn btn-primary" name="submit">' . Lang::getLang($action) . '</button></div>
									</div>

								</form>
								
								';
							$h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";	// #perihelionScript

		return $h;

	}
	
	public function scriptList() {

		$scriptArray = Script::scriptArray(); // $scriptEnabled: null|0|1 // $scriptPosition: null|header|footer
		$actionURL = '/' . Lang::languageUrlPrefix() . 'designer/scripts/create/';
		
		$h = "<div id=\"perihelionScriptList\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
							$h .= "<div class=\"card-header\">";
								$h .= "<div class=\"card-title\">";
									$h .= Lang::getLang('scripts') . " <a class=\"btn btn-secondary btn-sm float-right\" href=\"" . $actionURL . "\"><span class=\"fas fa-plus\"></span></a>";
								$h .= "</div>";
							$h .= "</div>";
							$h .= "<div class=\"card-body\">";
								$h .= "<div class=\"table-responsive\">";
									$h .= "<table class=\"table table-bordered table-striped table-hover\">";
										$h .= "<tr>";
											$h .= "<th id=\"th_scriptID\" class=\"text-center\">" . Lang::getLang('ID') . "</th>";
											$h .= "<th id=\"th_scriptName\">" . Lang::getLang('name') . "</th>";
											$h .= "<th id=\"th_scriptCreationDateTime\" class=\"text-center\">" . Lang::getLang('scriptCreationDateTime') . "</th>";
											$h .= "<th id=\"th_scriptPosition\" class=\"text-center\">" . Lang::getLang('scriptPosition') . "</th>";
											$h .= "<th id=\"th_scriptEnabled\" class=\"text-center\">" . Lang::getLang('scriptEnabled') . "</th>";
											$h .= "<th id=\"th_scriptAction\" class=\"text-center\">" . Lang::getLang('action') . "</th>";
										$h .= "</tr>";
										foreach($scriptArray AS $scriptID) {
									
											$script = new Script($scriptID);
											$h .= "<tr>";
												$h .= "<td class=\"text-center\">" . $scriptID . "</td>";
												$h .= "<td>" . $script->scriptName . "</td>";
												$h .= "<td class=\"text-center\">" . $script->scriptCreationDateTime . "</td>";
												$h .= "<td class=\"text-center\">" . $script->scriptPosition . "</td>";
												$h .= "<td class=\"text-center\">" . $script->scriptEnabled . "</td>";
												$h .= "<td class=\"text-center\"><a class=\"btn btn-secondary btn-sm\" href=\"/" . Lang::languageUrlPrefix() . "designer/scripts/update/" . $scriptID . "/\">" . Lang::getLang('update') . "</a></td>";
											$h .= "</tr>";
											
										}
									$h .= "</table>";
								$h .= "</div>";
							$h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>"; // #perihelionScriptList

		return $h;
	}


}

?>