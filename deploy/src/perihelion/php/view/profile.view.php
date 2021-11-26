<?php

final class ProfileView {
	
	private $loc;
	private $input;
	public $errorArray;
	
	public function __construct($loc = array(), $input = array(),  $errorArray = array()) {

		$this->loc = $loc;
		$this->input = $input;
		$this->errorArray = $errorArray;
		
		if (!Auth::isLoggedIn()) { die("ProfileView :: You are not logged in."); }

	}
	
	public function profileForm() {

		$user = new User($_SESSION['userID']);
		if (!empty($this->input)) {
			foreach ($this->input AS $key => $value) { if (isset($user->$key)) { $user->$key = $value; } }
		}
		
		$formURL = '/' . Lang::languageUrlPrefix() . 'profile/';

		$profileImage = '';
		$imr = new ImageMostRecent('User', $_SESSION['userID']);
		$imageID = $imr->imageID();
		if ($imageID) {
			$image = new Image($imageID);
			$src = $image->src(300);
			$profileImage = '
				<div class="form-row">
					<div class="form-group col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 text-center mb-2">
						<img src="' . $src . '" class="img-thumbnail">
					</div>
				</div>
			';
		}

		
		$form = '

			<form role="form" action="' . $formURL . '" method="post" enctype="multipart/form-data">
			
				<input type="hidden" name="userID" value="' . $user->userID . '">
				
				' . $profileImage . '
				
				<div class="form-row">
					<div class="form-group col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3">
						<label id="new_image_select_label" class="btn btn-secondary btn-block btn-file">
							<span id="new_image_select_prompt">' . Lang::getLang('updateProfileImage') . '</span> 
							<input id="new_image_select_input" class="d-none" type="file" name="images-to-upload[]" accept="image/*">
						</label>
					</div>
				</div>
				
				<hr />
				
				<div class="form-group row">
					<label class="col-sm-4 col-form-label" for="userEmail">' . Lang::getLang('userEmail') . '</label>
					<div class="col-sm-8">
						<input type="email" id="userEmail" name="userEmail" class="form-control" placeholder="' . Lang::getLang('userEmail') . '" value="' . $user->userEmail . '" required>
					</div>
				</div>
	
				<div class="form-group row">
					<label class="col-sm-4 col-form-label" for="username">' . Lang::getLang('username') . '</label>
					<div class="col-sm-8">
						<input type="text" id="username" name="username" class="form-control" placeholder="username' . Lang::getLang('username') . '" value="' . $user->username . '">
					</div>
				</div>
	
				<div class="form-group row">
					<label class="col-sm-4 col-form-label" for="userDisplayName">' . Lang::getLang('userDisplayName') . '</label>
					<div class="col-sm-8">
						<input type="text" id="userDisplayName" name="userDisplayName" class="form-control" placeholder="userDisplayName' . Lang::getLang('userDisplayName') . '" value="' . $user->userDisplayName . '">
					</div>
				</div>
				
				<hr />
				
				<div id="changePasswordFormGroup" class="form-group row">
					<label class="col-sm-4 col-form-label" for="profileChangePassword">' . Lang::getLang('changePassword') . '</label>
					<div class="col-sm-8">
						<input id="profileChangePassword" name="profileChangePassword" type="checkbox" value="1">
					</div>
				</div>
	
	
				<div id="userPasswordFormGroup" class="form-group row">
					<label class="col-sm-4 col-form-label" for="userPassword">' . Lang::getLang('newPassword') . '</label>
					<div class="col-sm-8">
						<input type="password" id="userPassword" name="userPassword" class="form-control" placeholder="new password" value="" disabled="disabled">
					</div>
				</div>
	
				<div id="confirmPasswordFormGroup" class="form-group row">
					<label class="col-sm-4 col-form-label" for="confirmPassword">' . Lang::getLang('confirmNewPassword') . '</label>
					<div class="col-sm-8">
						<input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="confirm new password" value="" disabled="disabled">
					</div>
				</div>
				
				<hr />
	
				<div class="form-group row">
					<div class="col-sm-4 offset-sm-8">
						<button type="submit" class="btn btn-primary btn-block" name="submit">' . Lang::getLang('update') . '</button>
					</div>
				</div>
			
			</form>

		';

		$container = array('container', 'mb-3');
		$colClasses = array('col-sm-10', 'offset-sm-1', 'col-md-8', 'offset-md-2');
		$header = Lang::getLang('profile');

		$card = new CardView('perihelion_profile_form', $container, '', $colClasses, $header, $form, false);

		return $card->card();
		
	}

}

?>