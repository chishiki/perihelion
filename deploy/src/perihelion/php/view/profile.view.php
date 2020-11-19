<?php

class ProfileView {
	
	private $urlArray;
	private $inputArray;
	public $errorArray;
	
	public function __construct($urlArray = array(), $inputArray = array(),  $errorArray = array()) {

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
		if (!Auth::isLoggedIn()) { die("ProfileView :: You are not logged in."); }

	}
	
	public function profileForm() {

		$user = new User($_SESSION['userID']);
		if (!empty($this->inputArray)) {
			foreach ($this->inputArray AS $key => $value) { if (isset($user->$key)) { $user->$key = $value; } }
		}
		
		$formURL = '/' . Lang::languageUrlPrefix() . 'profile/';
		
		$h = '<div id="perihelion_profile_form">';
			$h .= '<div class="container">';
				$h .= '<div class="row">';
					$h .= '<div class="col-sm-10 offset-sm-1 col-md-8 offset-md-2">';

						$h .= '<div class="card">';
						
							$h .= '<div class="card-header"><div class="card-title">' . Lang::getLang('profile') . '</div></div>';
							
							$h .= '<div class="card-body">';

								$h .= '<form role="form" action="' . $formURL . '" method="post" enctype="multipart/form-data">';
			
									$h .= '<input type="hidden" name="userID" value="' . $user->userID . '">';

									$h .= '
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="userEmail">' . Lang::getLang('userEmail') . '</label>
											<div class="col-sm-8">
												<input type="email" id="userEmail" name="userEmail" class="form-control" placeholder="' . Lang::getLang('userEmail') . '" value="' . $user->userEmail . '" required>
											</div>
										</div>
									';
									
									$h .= '
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="username">' . Lang::getLang('username') . '</label>
											<div class="col-sm-8">
												<input type="text" id="username" name="username" class="form-control" placeholder="username' . Lang::getLang('username') . '" value="' . $user->username . '">
											</div>
										</div>
									';
									
									$h .= '
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="userDisplayName">' . Lang::getLang('userDisplayName') . '</label>
											<div class="col-sm-8">
												<input type="text" id="userDisplayName" name="userDisplayName" class="form-control" placeholder="userDisplayName' . Lang::getLang('userDisplayName') . '" value="' . $user->userDisplayName . '">
											</div>
										</div>
									';
									
									$h .= '<hr />';
									
									$h .= '<div id="changePasswordFormGroup" class="form-group row">';
										$h .= '<label class="col-sm-4 col-form-label" for="profileChangePassword">' . Lang::getLang('changePassword') . '</label>';
										$h .= '<div class="col-sm-8">';
											$h .= '<input id="profileChangePassword" name="profileChangePassword" type="checkbox" value="1">';
										$h .= '</div>';
									$h .= '</div>';
									
									$h .= '
										<div id="userPasswordFormGroup" class="form-group row">
											<label class="col-sm-4 col-form-label" for="userPassword">' . Lang::getLang('newPassword') . '</label>
											<div class="col-sm-8">
												<input type="password" id="userPassword" name="userPassword" class="form-control" placeholder="new password" value="" disabled="disabled">
											</div>
										</div>
									';
									
									$h .= '
										<div id="confirmPasswordFormGroup" class="form-group row">
											<label class="col-sm-4 col-form-label" for="confirmPassword">' . Lang::getLang('confirmNewPassword') . '</label>
											<div class="col-sm-8">
												<input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="confirm new password" value="" disabled="disabled">
											</div>
										</div>
									';

									$h .= '<hr />';
									
									$h .= '
										<div class="form-group row">
											<div class="col-sm-4 offset-sm-8">
												<button type="submit" class="btn btn-primary btn-block" name="submit">' . Lang::getLang('update') . '</button>
											</div>
										</div>
									';

								$h .= '</form>';

							$h .= '</div>';
						$h .= '</div>';

					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';
		$h .= '</div>';

		return $h;
		
	}
	
	public function profileUpdateConfirmation() {

		$h = '<div id="perihelion_profile_update_confirmation">';
			$h .= '<div class="container">';
				$h .= '<div class="row">';
					$h .= '<div class="col-sm-10 offset-sm-1 col-md-8 offset-md-2">';
					   $h .= '<div class="alert alert-success" role="alert">Your Perihelion account has been updated successfully</div>';
					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';
		$h .= '</div>';

		return $h;
		
	}
	
	
}

?>