<?php

class AuthView {

	private $urlArray;
	private $inputArray;
	private $modules;
	private $errorArray;
	
	public function __construct($urlArray = array(), $inputArray = array(), $modules = array(), $errorArray = array()) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->modules = $modules;
		$this->errorArray = $errorArray;
		
	}

	public function login() {

		$h = "<div class=\"container\">";

			$h .= "<div id=\"login\" class=\"mainbox col-12 col-md-6 offset-md-3\">";
				$h .= "<div class=\"card\" >";
					
					$h .= "<h5 class=\"card-header\">" . strtoupper(Lang::getLang('login')) . "</h5>";
					
					$h .= "<div class=\"card-body\">";
					
						$h .= "<form role=\"form\" id=\"login\" name=\"login\" method=\"post\" action=\"/" . Lang::prefix() . "login/\">";
					
							$h .= "<div class=\"form-row mb-2\">";

							     $h .= '<div class="col-12">';
							     
    							     $h .= "<div class=\"input-group\">";
    								
    								    $h .= '<div class="input-group-prepend">';
    								        $h .= '<span class="input-group-text"><span class="fas fa-envelope"></span></span>';
    								    $h .= '</div>';
    								
    									$h .= "<input id=\"userSelector\" type=\"text\" class=\"form-control" . (isset($this->errorArray['login'])?" is-invalid":"") . "\" name=\"userSelector\" value=\"";
    										if (isset($this->inputArray['userSelector'])) { $h .= $this->inputArray['userSelector']; }
    									$h .= "\" placeholder=\"username or email\" autofocus>";
    									
    								  $h .= "</div>";
								
								$h .= "</div>";
								
							$h .= "</div>";

							$h .= "<div class=\"form-row mb-2\">";
							
    							$h .= '<div class="col-12">';
    								
    							    $h .= "<div class=\"input-group\">";
    									
    							        $h .= '<div class="input-group-prepend">';
    							             $h .= '<span class="input-group-text"><span class="fas fa-lock"></span></span>';
    							        $h .= '</div>';
    							    
    									$h .= "<input id=\"password\" type=\"password\" class=\"form-control" . (isset($this->errorArray['login'])?" is-invalid":"") . "\" name=\"password\" value=\"";
    										if (isset($this->inputArray['password'])) { $h .= $this->inputArray['password']; }
    									$h .= "\" placeholder=\"password\">";
    									
    								$h .= "</div>";
    								
    								if (isset($this->errorArray['login'])) {
    									foreach ($this->errorArray['login'] AS $message) { $h .= "<small>" . $message . "</small> "; }
    								}
    								
    							$h .= "</div>";
							
							$h .= "</div>";

							$h .= '<div class="form-row">';
                                $h .= '<div class="col-12">';
								    $h .= "<input type=\"submit\" name=\"loginSubmit\" id=\"loginSubmit\" class=\"btn btn-secondary float-right\" value=\"" . Lang::getLang('login') . "\">";
								$h .= "</div>";
							$h .= "</div>";

						$h .= "</form>";
						
						$h .= "<div class=\"form-row\">";
							$h .= "<div class=\"col-12\">";
								$h .= "<div style=\"border-top:1px solid #888;padding-top:15px;margin-top:15px;font-size:85%;\">";
									$h .= Lang::getLang('havingTroubleLoggingIn') . " <a href=\"/account-recovery/\">" . Lang::getLang('accountRecovery') . "</a>";
								$h .= "</div>";
							$h .= "</div>";
						$h .= "</div>";
						
					$h .= "</div>";

				$h .= "</div>";
			$h .= "</div>";

		$h .= "</div>";
		$h .= "<!-- END AUTH CONTAINER -->";
			
		return $h;
	
	}

	public function loginSuccessful() {
		
		$site = new Site($_SESSION['siteID']);
		$navbar = new NavBar($this->urlArray, $this->inputArray, array(), $site->siteNavMenuID);
		$items = $navbar->getNavBarItems();

		foreach ($this->modules AS $moduleName) {

			$moduleDashboardView = ucfirst($moduleName) . 'DashboardView';
			if (class_exists($moduleDashboardView)) {
				$view = new $moduleDashboardView($this->urlArray);
				$h = $view->dashboard();
			} else {

		$h = '<div id="login_successful" class="container">';

			$h .= '<div class="col-12">';
				$h .= '<div class="card" >';
					
					$h .= '<h5 class="card-header">' . Lang::getLang('loginSuccessful') . '</h5>';
					
					$h .= '<div class="card-body">';

						$h .= '<ul>';
						
							foreach ($items AS $item) {
							
								$h .= '<li>';
									if ($item['url'] == '#' || $item['disabled'] == 1) { $h .= $item['anchor']; } 
									else { $h .= '<a href="' . $item['url'] . '">' . $item['anchor'] . '</a>'; }
									if (isset($item['children'])) {
										$h .= '<ul>';
											foreach ($item['children'] AS $child) {
												$h .= '<li>';
													if ($child['url'] == '#' || $child['disabled'] == 1) { $h .= $child['anchor']; } 
													else { $h .= '<a href="' . $child['url'] . '">' . $child['anchor'] . '</a>'; }
												$h .= '</li>';
											}
										$h .= '</ul>';
									}
								$h .= '</li>';
							}
						
						$h .= '</ul>';

					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';

		$h .= '</div>';

			}
		}

		return $h;
	
	}
	
	public function accountRecovery() {

		if (isset($this->inputArray['userEmail'])) { $userEmail = $this->inputArray['userEmail']; } else { $userEmail = ''; }
		
		$h = "<div class=\"container\">";
			$h .= "<div id=\"perihelionAccountRecovery\" class=\"mainbox col-md-6 offset-md-3 col-sm-8 offset-sm-2\">";
				$h .= "<div class=\"card\" >";
					
					$h .= "<div class=\"card-header\">";
						$h .= "<div class=\"card-title\">" . strtoupper(Lang::getLang('accountRecovery')) . "</div>";
					$h .= "</div>";

					$h .= "<div style=\"padding-top:30px\" class=\"card-body\">";
						$h .= "<form role=\"form\" id=\"perihelionAccountRecoveryForm\" name=\"login\" class=\"\" method=\"post\" action=\"/account-recovery/\">";
					
							$h .= "<div class=\"form-row mb-2" . (isset($this->inputArray['userEmail'])?" has-error":"") . "\">";
								$h .= '<div class="col-12">';
									$h .= "<div class=\"input-group\">";
										$h .= '<div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>';
										$h .= "<input id=\"userEmail\" type=\"email\" class=\"form-control\" name=\"userEmail\" value=\"" . $userEmail . "\" placeholder=\"email\" required>";
									$h .= "</div>";
									if (isset($this->errorArray['userEmail'])) {
										foreach ($this->errorArray['userEmail'] AS $message) { $h .= "<small>" . $message . "</small> "; }
									}
								$h .= '</div>';
							$h .= "</div>";

							$h .= "<div class=\"form-row\">";
								$h .= '<div class="col-12">';
									$h .= "<input type=\"submit\" name=\"perihelionAccountRecoverySubmit\" id=\"perihelionAccountRecoverySubmit\" class=\"btn btn-secondary float-right\" value=\"" . Lang::getLang('getRecoveryMail') . "\">";
								$h .= '</div>';
							$h .= "</div>";

						$h .= "</form>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";

		return $h;
		
	}
	
	public function resetPasswordForm() {
	
		$accountRecoveryMash = $this->urlArray[1];
		if (isset($this->inputArray['userEmail'])) { $userEmail = $this->inputArray['userEmail']; } else { $userEmail = ''; }
	
		$h = "<div class=\"container\">";
			$h .= "<div id=\"resetPassword\" class=\"mainbox col-md-6 offset-md-3 col-sm-8 offset-sm-2\">";
				$h .= "<div class=\"card\" >";
				
					$h .= "<div class=\"card-header\">";
						$h .= "<div class=\"card-title\">" . strtoupper(Lang::getLang('accountRecovery')) . "</div>";
					$h .= "</div>";

					$h .= "<div style=\"padding-top:30px\" class=\"card-body\">";

						$h .= "<form role=\"form\" id=\"resetPasswordForm\" name=\"resetPassword\" class=\"\" method=\"post\" action=\"/reset-password/" . $accountRecoveryMash . "/\">";
						
							$h .= "<input type=\"hidden\" name=\"confirmMash\" value=\"" . $accountRecoveryMash . "\">";

							$h .= "<div class=\"form-row mb-2" . (isset($this->errorArray['userEmail'])?" has-error":"") . "\">";
								$h .= '<div class="col-12">';
									$h .= "<div class=\"input-group\">";
										$h .= '<div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>';
										$h .= '<input type="text" id="userEmail" class="form-control" name="userEmail" value="' . $userEmail . '" placeholder="email" autocomplete="off" data-lpignore="true" required>';
									$h .= "</div>";
									if (isset($this->errorArray['userEmail'])) { foreach ($this->errorArray['userEmail'] AS $message) { $h .= "<small>" . $message . "</small> "; } }
								$h .= '</div>';
							$h .= "</div>";
							
							$h .= "<div class=\"form-row mb-2" . (isset($this->errorArray['password'])?" has-error":"") . "\">";
								$h .= '<div class="col-12">';
									$h .= "<div class=\"input-group\">";
										$h .= '<div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>';
										$h .= "<input type=\"password\" id=\"register-password\" class=\"form-control\" name=\"password\" placeholder=\"new password\" autocomplete=\"off\" data-lpignore=\"true\" required>";
									$h .= "</div>";
								$h .= '</div>';
							$h .= "</div>";
							
							$h .= "<div class=\"form-row mb-2" . (isset($this->errorArray['password'])?" has-error":"") . "\">";
								$h .= '<div class="col-12">';
									$h .= "<div class=\"input-group\">";
										$h .= '<div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>';
										$h .= "<input type=\"password\" id=\"register-confirm-password\" class=\"form-control\" name=\"confirmPassword\" placeholder=\"confirm new password\" autocomplete=\"off\" data-lpignore=\"true\" required>";
									$h .= "</div>";
									if (isset($this->errorArray['password'])) { foreach ($this->errorArray['password'] AS $message) { $h .= "<small>" . $message . "</small> "; } }
								$h .= '</div>';
							$h .= "</div>";
							
							if (isset($this->errorArray['mash'])) { foreach ($this->errorArray['mash'] AS $message) {
								$h .= '<div class="form-row mb-2"><div class="col-12"><div class="alert alert-danger"><small>' . $message . '</small></div></div></div>'; }
							}
							
							$h .= "<div class=\"form-row\">";
								$h .= '<div class="col-12">';
									$h .= '<input type="submit" name="resetPasswordSubmit" id="resetPasswordSubmit" class="btn btn-secondary float-right" value="' . strtoupper(Lang::getLang('accessYourAccount')) . '">';
								$h .= '</div>';
							$h .= "</div>";

						$h .= "</form>";

					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";
			
		return $h;
		
	}

	public function accountRecoveryMailSent() {
	
		$h = "<div class=\"container alert alert-success\">";
			$h .= "<b>An email has been sent to you containing a link to reset your password</u>.</b>";
			$h .= "<ul>";
				$h .= "<li>It can take a few minutes to receive your account recovery email.</li>";
				$h .= "<li>Please check your spam folder if you do not see the email in your inbox.</li>";
				$h .= "<li>Only your most recent account recovery email will work.</li>";
			$h .= "<ul>";
		$h .= "</div>";
		return $h;
		
	}

}

?>