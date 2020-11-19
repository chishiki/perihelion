<?php

class AdminView {

	private $urlArray;
	private $inputArray;
	public $errorArray;
	
	public function __construct($urlArray = array(), $inputArray = array(),  $errorArray = array()) {

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
		$role = Auth::getUserRole();
		if ($role != 'siteAdmin') { die("You do not have permissions sufficient to view the staff module."); }
		
	}

	public function server() {
	    
	    $phpinfo = new PhpInfo();
	    
		$h = "<div id=\"perihelionAdminServer\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
							$h .= "<div class=\"card-header perihelionAdminServerHeading\">";
								$h .= "<div class=\"card-title\">" . Lang::getLang('server') . "</div>";
							$h .= "</div>";
							$h .= "<div class=\"card-body\">";
							    $h .= "<div style=\"overflow-x:scroll;\">";
                                    $h .= $phpinfo->getInfo();
							    $h .= "</div>";
                            $h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";
			
		return $this->html = $h;
		
	}
	
	public function lang() {

		$words = Lang::words();

		$h = "<div id=\"perihelionAdminLang\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
							$h .= "<div class=\"card-header perihelionAdminServerHeading\">";
								$h .= "<div class=\"card-title\">" . Lang::getLang('server') . "</div>";
							$h .= "</div>";
							$h .= "<div class=\"card-body\">";
								$h .= "<div class=\"row\">";
									$h .= "<div class=\"table-responsive\">";
										$h .= "<table class=\"table table-striped table-bordered\">";
											foreach ($words AS $langKey) {
												$word = new Lang($langKey);
												$h .= "<tr>";
													$h .= "<td>" . $word->langKey . "</td>";
													$h .= "<td>" . $word->enLang . "</td>";
													$h .= "<td>" . $word->enCount . "</td>";
													$h .= "<td>" . $word->jaLang . "</td>";
													$h .= "<td>" . $word->jaCount . "</td>";
													// $h .= "<td>" . $word->langTimeStamp . "</td>";
												$h .= "</tr>";
											}
										$h .= "</table>";
									$h .= "</div>";
								$h .= "</div>";
							$h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";
			
		return $this->html = $h;
		
	}
	
	public function cron() {

		$formAction = "/" . Lang::prefix() . "admin/cron/";
		$cronKey = '';
		
		if (!empty($this->inputArray)) { foreach($this->inputArray AS $key => $value) { ${$key} = $value; } }
	
		$h = "<div id=\"perihelionAdminCron\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
							$h .= "<div class=\"card-header\">";
								$h .= "<div class=\"card-title\">" . Lang::getLang('cron') . "</div>";
							$h .= "</div>";
							$h .= "<div class=\"card-body\">";
								$h .= "<div class=\"row\">";

									$h .= "<form id=\"perihelionAdminCronForm\" name=\"perihelionAdminCronForm\"  method=\"post\" action=\"" . $formAction . "\">";
						
										$h .= "<input type=\"hidden\" name=\"cronKeyCheck\" value=\"cron\">";

										$h .= "<div class=\"form-group row\">";
											$h .= "<label for=\"invoiceYearMonth\" class=\"col-form-label col-sm-4\">" . Lang::getLang('invoiceYearMonth') . "</label>";
											$h .= "<div class=\"col-sm-2\">";
												$h .= "<input type=\"text\" id=\"invoiceYearMonth\" name=\"invoiceYearMonth\" class=\"form-control\" value=\"" . date('Y-m',strtotime('-1 day')) . "\" autocomplete=\"off\">";
											$h .= "</div>";
										$h .= "</div>";
										
										$h .= "<div class=\"form-group row\">";
											$h .= "<label for=\"invoiceDate\" class=\"col-form-label col-sm-4\">" . Lang::getLang('invoiceDate') . "</label>";
											$h .= "<div class=\"col-sm-3\">";
												$h .= "<input type=\"text\" id=\"invoiceDate\" name=\"invoiceDate\" class=\"form-control\" value=\"" . date('Y-m',strtotime('+1 day')) . "-01\" autocomplete=\"off\">";
											$h .= "</div>";
										$h .= "</div>";

										$h .= "<div class=\"form-group row\">";
											$h .= "<label for=\"transactionDateTime\" class=\"col-form-label col-sm-4\">" . Lang::getLang('transactionDateTime') . "</label>";
											$h .= "<div class=\"col-sm-4\">";
												$h .= "<input type=\"text\" id=\"transactionDateTime\" name=\"transactionDateTime\" class=\"form-control\" value=\"" . date('Y-m',strtotime('+1 day')) . "-01 00:00:01\" autocomplete=\"off\">";
											$h .= "</div>";
										$h .= "</div>";
										
										$h .= "<div class=\"form-group row\">";
											$h .= "<label for=\"cronKey\" class=\"col-form-label col-sm-4\">" . Lang::getLang('cronKey') . "</label>";
											$h .= "<div class=\"col-sm-6\">";
												$h .= "<input type=\"text\" id=\"cronKey\" name=\"cronKey\" class=\"form-control\" value=\"\" autocomplete=\"off\">";
											$h .= "</div>";
										$h .= "</div>";

										$h .= "<hr />";

										$h .= "<div class=\"form-group row\">";
											$h .= "<div class=\"col-sm-8\">";
												$h .= "<button type=\"submit\" name=\"perihelionCronSubmit\" id=\"perihelionCronSubmit\" class=\"btn btn-primary float-right\">";
												$h .= "<span class=\"fas fa-check\"></span> " . strtoupper(Lang::getLang('runCron')) . "</button>";
											$h .= "</div>";
										$h .= "</div>";

									$h .= "</form>";

								$h .= "</div>";
							$h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";
			
		return $this->html = $h;
		
	}
	
}

?>