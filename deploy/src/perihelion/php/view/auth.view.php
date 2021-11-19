<?php

class AuthView
{

	private $urlArray;
	private $inputArray;
	private $modules;
	private $errorArray;

	public function __construct($urlArray = array(), $inputArray = array(), $modules = array(), $errorArray = array())
	{

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->modules = $modules;
		$this->errorArray = $errorArray;

	}

	public function login() {

		$error = false;
		$errorMessage = '';
		if (isset($this->errorArray['login'])) {
			$error = true;
			foreach ($this->errorArray['login'] as $message) {
				$errorMessage .= '<small>' . $message . '</small> ';
			}
		}

		$userSelector = '';
		$password = '';
		if (isset($this->inputArray['userSelector'])) {
			$userSelector = $this->inputArray['userSelector'];
		}
		if (isset($this->inputArray['password'])) {
			$password = $this->inputArray['password'];
		}

		$h = '

			<div class="container">
				<div class="row">
					<div id="login" class="mainbox col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
						<div class="card">
							<h5 class="card-header">' . strtoupper(Lang::getLang('login')) . '</h5>
							<div class="card-body">
								<form name="login" method="post" action="/' . Lang::prefix() . 'login/">
									<div class="form-row mb-2">
										<div class="col-12">
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text"><span class="fas fa-envelope"></span></span>
												</div>
												<input type="text" id="userSelector" name="userSelector" class="form-control' . ($error ? ' is-invalid' : '') . '" value="' . $userSelector . '" placeholder="username or email" autofocus>
											</div>
										</div>
									</div>
									<div class="form-row mb-2">
										<div class="col-12">
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text"><span class="fas fa-lock"></span></span>
												</div>
												<input id="password" type="password" class="form-control' . ($error ? ' is-invalid' : '') . '" name="password" value="' . $password . '" placeholder="password">
											</div>
										' . $errorMessage . '
										</div>
									</div>
									<div class="form-row">
										<div class="col-12 col-sm-6 offset-sm-6 col-md-4 offset-md-8">
											<button type="submit" name="loginSubmit" id="loginSubmit" class="btn btn-secondary btn-block enter-trigger">' . Lang::getLang('login') . '</button>
										</div>
									</div>
								</form>
								<div class="row">
									<div class="col-12">
										<div class="border-top text-muted my-2 pt-2">
											' . Lang::getLang('havingTroubleLoggingIn') . '
											<a href="/' . Lang::prefix() . 'account-recovery/">' . Lang::getLang('accountRecovery') . '</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		';

		return $h;

	}

	public function loginSuccessful() {

		$site = new Site($_SESSION['siteID']);
		$navbar = new NavBar($this->urlArray, $this->inputArray, array(), $site->siteNavMenuID);
		$items = $navbar->getNavBarItems();

		if (!empty($this->modules)) {
			foreach ($this->modules as $moduleName) {
				$moduleIndexView = ModuleUtilities::moduleToClassName($moduleName, 'IndexView');
				if (class_exists($moduleIndexView)) {
					$view = new $moduleIndexView($this->urlArray);
					$h = $view->getView();
				}
			}
		}

		if (!isset($h)) {

			$h = '<div id="login_successful" class="container">';
				$h .= '<div class="col-12">';
					$h .= '<div class="card" >';
						$h .= '<h5 class="card-header">' . Lang::getLang('loginSuccessful') . '</h5>';
						$h .= '<div class="card-body">';
							$h .= '<ul>';
								foreach ($items as $item) {

									$h .= '<li>';
									if ($item['url'] == '#' || $item['disabled'] == 1) {
										$h .= $item['anchor'];
									} else {
										$h .= '<a href="' . $item['url'] . '">' . $item['anchor'] . '</a>';
									}
									if (isset($item['children'])) {
										$h .= '<ul>';
										foreach ($item['children'] as $child) {
											$h .= '<li>';
											if ($child['url'] == '#' || $child['disabled'] == 1) {
												$h .= $child['anchor'];
											} else {
												$h .= '<a href="' . $child['url'] . '">' . $child['anchor'] . '</a>';
											}
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

		return $h;
	
	}
	
	public function accountRecovery() {

		if (isset($this->inputArray['userEmail'])) { $userEmail = $this->inputArray['userEmail']; } else { $userEmail = ''; }

		$form = '

			<div class="container">
				<div id="perihelionAccountRecovery" class="col-12 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
				
					<div class="card">
						<div class="card-header">
							<div class="card-title">' . strtoupper(Lang::getLang('accountRecovery')) . '</div>
						</div>
						
						<div class="card-body">
						
							<form role="form" id="perihelionAccountRecoveryForm" name="login" class="" method="post" action="/' . Lang::prefix() . 'account-recovery/">
							
								<div class="form-row mb-2' . (isset($this->inputArray['userEmail'])?" has-error":"") . '">
									<div class="form-group col-12">
										<div class="input-group">
											<div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
											<input id="userEmail" type="email" class="form-control" name="userEmail" value="' . $userEmail . '" placeholder="email" required>
										</div>
									</div>
								</div>
								
								<div class="form-row">
									<div class="form-group col-12 col-md-6 offset-md-6">
										<input type="submit" name="perihelionAccountRecoverySubmit" id="perihelionAccountRecoverySubmit" class="btn btn-secondary btn-block" value="' . Lang::getLang('getRecoveryMail') . '">
									</div>
								</div>
								
							</form>
							
						</div>
						
					</div>
					
				</div>
			</div>
		
		';

		return $form;
		
	}
	
	public function resetPasswordForm() {
	
		$accountRecoveryMash = $this->urlArray[1];
		if (isset($this->inputArray['userEmail'])) { $userEmail = $this->inputArray['userEmail']; } else { $userEmail = ''; }
	
		$h = '<div class="container">';
			$h .= '<div id="resetPassword" class="mainbox col-md-6 offset-md-3 col-sm-8 offset-sm-2">';
				$h .= '<div class="card" >';
				
					$h .= '<div class="card-header">';
						$h .= '<div class="card-title">' . strtoupper(Lang::getLang('accountRecovery')) . '</div>';
					$h .= '</div>';

					$h .= '<div style="padding-top:30px" class="card-body">';

						$h .= '<form role="form" id="resetPasswordForm" name="resetPassword" class="" method="post" action="/reset-password/' . $accountRecoveryMash . '/">';
						
							$h .= '<input type="hidden" name="confirmMash" value="' . $accountRecoveryMash . '">';

							$h .= '<div class="form-row mb-2' . (isset($this->errorArray['userEmail'])?" has-error":"") . '">';
								$h .= '<div class="col-12">';
									$h .= '<div class="input-group">';
										$h .= '<div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>';
										$h .= '<input type="text" id="userEmail" class="form-control" name="userEmail" value="' . $userEmail . '" placeholder="email" autocomplete="off" data-lpignore="true" required>';
									$h .= '</div>';
									if (isset($this->errorArray['userEmail'])) { foreach ($this->errorArray['userEmail'] AS $message) { $h .= '<small>' . $message . '</small> '; } }
								$h .= '</div>';
							$h .= '</div>';
							
							$h .= '<div class="form-row mb-2' . (isset($this->errorArray['password'])?" has-error":"") . '">';
								$h .= '<div class="col-12">';
									$h .= '<div class="input-group">';
										$h .= '<div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>';
										$h .= '<input type="password" id="register-password" class="form-control" name="password" placeholder="new password" autocomplete="off" data-lpignore="true" required>';
									$h .= '</div>';
								$h .= '</div>';
							$h .= '</div>';
							
							$h .= '<div class="form-row mb-2' . (isset($this->errorArray['password'])?" has-error":"") . '">';
								$h .= '<div class="col-12">';
									$h .= '<div class="input-group">';
										$h .= '<div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>';
										$h .= '<input type="password" id="register-confirm-password" class="form-control" name="confirmPassword" placeholder="confirm new password" autocomplete="off" data-lpignore="true" required>';
									$h .= '</div>';
									if (isset($this->errorArray['password'])) { foreach ($this->errorArray['password'] AS $message) { $h .= '<small>' . $message . '</small> '; } }
								$h .= '</div>';
							$h .= '</div>';
							
							if (isset($this->errorArray['mash'])) { foreach ($this->errorArray['mash'] AS $message) {
								$h .= '<div class="form-row mb-2"><div class="col-12"><div class="alert alert-danger"><small>' . $message . '</small></div></div></div>'; }
							}
							
							$h .= '<div class="form-row">';
								$h .= '<div class="col-12">';
									$h .= '<input type="submit" name="resetPasswordSubmit" id="resetPasswordSubmit" class="btn btn-secondary float-right" value="' . strtoupper(Lang::getLang('accessYourAccount')) . '">';
								$h .= '</div>';
							$h .= '</div>';

						$h .= '</form>';

					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';
		$h .= '</div>';
			
		return $h;
		
	}

	public function accountRecoveryMailSent() {
	
		$h = '<div class="container alert alert-success">';
			$h .= '<b>An email has been sent to you containing a link to reset your password</u>.</b>';
			$h .= '<ul>';
				$h .= '<li>It can take a few minutes to receive your account recovery email.</li>';
				$h .= '<li>Please check your spam folder if you do not see the email in your inbox.</li>';
				$h .= '<li>Only your most recent account recovery email will work.</li>';
			$h .= '<ul>';
		$h .= '</div>';
		return $h;
		
	}

}

?>