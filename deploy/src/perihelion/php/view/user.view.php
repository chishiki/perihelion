<?php

class UserView {

	private $urlArray;
	private $inputArray;
	private $errorArray;

	public function __construct($urlArray = array(), $inputArray = array(),  $errorArray = array()) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
	}

	public function userForm($selectedUserID = null) {

		if (!Auth::isLoggedIn()) { die("You must be logged in to access this resource."); }

		$siteID = $_SESSION['siteID'];
		
		$currentUserRole = new UserRole($siteID, $_SESSION['userID']);
		$currentRole = $currentUserRole->getUserRole();
		
		$selectedUser = new User($selectedUserID);
		$userDisplayName = '';
		$userEmail = '';

		foreach ($selectedUser AS $key => $value) { ${$key} = $value; }
		$selectedUserRole = new UserRole($siteID, $selectedUserID);
		$selectedRole = $selectedUserRole->getUserRole();

		if (!empty($this->inputArray)) {
			foreach ($this->inputArray AS $key => $value) {
				if (isset(${$key})) { ${$key} = $value; }
			}
			if (!isset($userIsPublic)) { $userIsPublic = 0; }
		}
		
		$lang = Lang::languageUrlPrefix();
		if ($selectedUserID) { $type = 'update'; } else { $type = 'create'; }
		$formAction = '/' . $lang . 'manager/users/' . $type . '/' . ($type=='update'?$selectedUserID."/":"");
		$panelTitle = $type . 'User';

		$h = '<div id="perihelionManagerUser">';
			$h .= '<div class="container">';
				$h .= '<div class="row">';
					$h .= '<div class="col-12 col-md-10 offset-md-1">';
					
						$h .= '<div class="card" >';

							$h .= '<div class="card-header">';
								$h .= '<div class="card-title">' . Lang::getLang($panelTitle) . '</div>';
							$h .= '</div>';

							$h .= '<div class="card-body">';
								
								$h .= '<form id="perihelionUserForm" name="perihelionUserForm"  method="post" action="' . $formAction . '" enctype="multipart/form-data">';
									
									if ($type == 'update') { $h .= '<input type="hidden" name="userID" value="' . $selectedUserID . '">'; }

									$h .= '<div class="form-group row">';
										$h .= '<label for="userDisplayName" class="col-form-label col-sm-3">' . Lang::getLang('userDisplayName') . '</label>';
										$h .= '<div class="col-sm-8">';
											$h .= '<div class="input-group">';
												$h .= '<div class="input-group-prepend"><div class="input-group-text"><span class="fas fa-user"></span></div></div>';
												$h .= '<input type="text" id="userDisplayName" class="form-control" name="userDisplayName" value="' . $userDisplayName . '" placeholder="Display Name" ' . ($selectedUserID==$_SESSION['userID']?"required":"disabled") . '>';
											$h .= '</div>';
										$h .= '</div>';
									$h .= '</div>';

									$h .= '<div class="form-group row">';
										$h .= '<label for="userEmail" class="col-form-label col-sm-3">' . Lang::getLang('userEmail') . '</label>';
										$h .= '<div class="col-sm-8">';
											$h .= '<div class="input-group">';
												$h .= '<div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-envelope"></i></div></div>';
												$h .= '<input id="register-email" type="email" class="form-control" name="userEmail" value="' . $userEmail . '" placeholder="email" ' . ($selectedUserID==$_SESSION['userID']?"required":"disabled") . '>';
											$h .= '</div>';
										$h .= '</div>';
									$h .= '</div>';

									if ($selectedUserID == $_SESSION['userID']) { // users can only change their own password

										$h .= '<hr />';
										
										$h .= '<div class="form-group row">';
											$h .= '<label for="userPassword" class="col-form-label col-sm-3">' . Lang::getLang('userPassword') . '</label>';
											$h .= '<div class="col-sm-8">';
												$h .= '<div class="input-group">';
													$h .= '<div class="input-group-prepend"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>';
													$h .= '<input type="password" id="userPassword" class="form-control" name="userPassword" placeholder="new password" value=""';
														if ($selectedUserID!=$_SESSION['userID']) { $h .= ' readonly'; }
													$h .= '>';
												$h .= '</div>';
											$h .= '</div>';
										$h .= '</div>';
										
										$h .= '<div class="form-group row">';
											$h .= '<label for="confirmPassword" class="col-form-label col-sm-3">' . Lang::getLang('confirmPassword') . '</label>';
											$h .= '<div class="col-sm-8">';
												$h .= '<div class="input-group">';
													$h .= '<div class="input-group-prepend"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>';
													$h .= '<input type="password" id="confirmPassword" class="form-control" name="confirmPassword" placeholder="confirm new password" value=""';
														if ($selectedUserID!=$_SESSION['userID']) { $h .= ' readonly'; }
													$h .= '>';
												$h .= '</div>';
											$h .= '</div>';
										$h .= '</div>';

									}

									$userRolePermissions = array('siteManager','siteAdmin');
									if (in_array($currentRole,$userRolePermissions)) { // only managers and admins can manage user role's
										
										$h .= '<hr />';
										
										$h .= '<div class="form-group row">';
											$h .= '<label for="userRole" class="col-form-label col-sm-3">' . Lang::getLang('userRole') . '</label>';
											$h .= '<div class="col-sm-8">';
												$h .= '<select id="userRole" class="form-control" name="userRole" required>';
													$userRoleArray = array('siteDesigner','siteManager');
													foreach ($userRoleArray AS $ur) { $h .= '<option value="' . $ur . '"' . ($ur==$selectedRole?" selected":"") . '>' . Lang::getLang($ur) . '</option>'; }
												$h .= '</select>';
											$h .= '</div>';
										$h .= '</div>';

									}
								
									$h .= '<hr />';
									
									$h .= '<div class="form-group row">';
											
										  $h .= '<div class="col-12 col-md-10 offset-md-1 text-right">';
										      $h .= '<a href="/' . $lang . 'manager/users/revoke-access/' . $selectedUserID . '/" name="perihelionRevokeAccess" id="perihelionRevokeAccess" class="btn btn-danger mr-2">';
										          $h .= '<span class="fas fa-trash-alt" style="color:#fff;"></span> ' . Lang::getLang('revokeAccess');
										      $h .= '</a>';
											$h .= '<button type="submit" name="perihelionUserSubmit" id="perihelionUserSubmit" class="btn btn-primary">';
												$h .= '<span class="fas fa-check"></span> ' . Lang::getLang($type);
											$h .= '</button>';
										$h .= '</div>';
										
									$h .= '</div>';

								$h .= '</form>';
							$h .= '</div>';
							
						$h .= '</div>';
					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';
		$h .= '</div>';
			
		return $h;
		
	}

	public function grantAccessForm() {

		$authorizedRoles = array('siteAdmin','siteManager');
		$role = Auth::getUserRole();
		if (!in_array($role,$authorizedRoles)) { die("You do not have sufficient permissions to grant access to other users."); }
		
		$lang = Lang::languageUrlPrefix();
		$formAction = '/' . $lang . 'manager/users/grant-access/';

		if (isset($this->inputArray['userEmail']) ) { $userEmail = $this->inputArray['userEmail']; } else { $userEmail = ''; }
		if (isset($this->inputArray['userRole']) ) { $userRole = $this->inputArray['userRole']; } else { $userRole = 'siteOwner'; }

		$h = '<div id="perihelionGrantAccessForm">';
			$h .= '<div class="container">';
				$h .= '<div class="row">';
					$h .= '<div class="col-sm-12 col-md-12">';

						$h .= '<div class="card" >';

							$h .= '<h5 class="card-header">' . Lang::getLang('grantAccess') . '</h5>';

							$h .= '<div class="card-body">';
							
								$h .= '<form id="grantAccessForm" name="grantAccessForm" class="form-inline"  method="post" action="' . $formAction . '">';

									$h .= '<div class="form-group mr-2">';
                                        $h .= '<input id="userEmail" type="email" class="form-control" name="userEmail" value="' . $userEmail . '" placeholder="email" required> ';
									$h .= '</div>';
										
									$h .= '<div class="form-group mr-2">';
										$h .= '<select id="userRole" class="custom-select" name="userRole" required>';
											$userRoleArray = array('siteDesigner','siteManager');
											foreach ($userRoleArray AS $ur) {
												$h .= '<option value="' . $ur . '"' . ($ur==$userRole?" selected":"") . '>' . Lang::getLang($ur) . '</option>';
											}
										$h .= '</select>';
									$h .= '</div>';
									
									$h .= '<div class="form-group">';
										$h .= ' <button type="submit" name="grantAccessSubmit" id="grantAccessSubmit" class="btn btn-primary">' . strtoupper(Lang::getLang('grantAccess')) . '</button>';
									$h .= '</div>';

								$h .= '</form>';
								
							$h .= '</div>';
							
						$h .= '</div>';
					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';
		$h .= '</div>';
			
		return $h;
		
	}

	public function revokeAccessConfirmationForm($userID) {

		$authorizedRoles = array('siteAdmin','siteManager');
		$role = Auth::getUserRole();
		if (!in_array($role,$authorizedRoles)) { die("You do not have sufficient permissions to revoke other users' access."); }
		
		$user = new User($userID);
		
		$lang = Lang::languageUrlPrefix();
		$formAction = '/' . $lang . 'manager/users/revoke-access/' . $userID . '/';

		$h = '<div id="perihelionGrantAccessForm">';
			$h .= '<div class="container">';
				$h .= '<div class="row">';
					$h .= '<div class="col-12 col-md-6 offset-md-3">';

						$h .= '<div class="card" >';

							$h .= '<h5 class="card-header">' . Lang::getLang('revokeAccess') . '</h5>';

							$h .= '<div class="card-body">';
							
								$h .= '<form id="revokeAccessForm" name="revokeAccessForm" class="form"  method="post" action="' . $formAction . '">';
									
									$h .= '<input type="hidden" name="' . $userID . '" value="sayonara">';
									
									$h .= '<div class="form-group row">';
										$h .= '<div class="col-12">';
											$h .= 'Are you sure that you want to revoke '  . $user->userEmail . '\'s access to your website?';
										$h .= '</div>';
									$h .= '</div>';
									
									$h .= '<div class="form-group row">';
										$h .= '<div class="col-12 col-md-6">';
											$h .= '<a href="/' . $lang . 'manager/users/update/' . $userID . '/" name="cancelRevokeAccess" id="cancelRevokeAccess" class="btn btn-warning btn-sm btn-block">' . Lang::getLang('cancel') . '</a>';
										$h .= '</div>';
										$h .= '<div class="col-12 col-md-6">';
											$h .= '<button type="submit" name="revokeAccessSubmit" id="revokeAccessSubmit" class="btn btn-danger btn-sm btn-block">' . Lang::getLang('revokeAccess') . '</button>';
										$h .= '</div>';
									$h .= '</div>';
									
								$h .= '</form>';
								
							$h .= '</div>';
							
						$h .= '</div>';
					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';
		$h .= '</div>';
			
		return $h;
		
	}
	
	public function userList($siteID = null) {

		if (!$siteID) { $siteID = $_SESSION['siteID']; }
		$userID = $_SESSION['userID'];
		$userRole = new UserRole($siteID,$userID);
		$role = $userRole->getUserRole();
		$userRolePermissions = array('siteManager','siteAdmin');
		if (!in_array($role,$userRolePermissions)) {
			die("You must be either a manager or an admin to access this resource.");
		}
		
		$userArray = User::getUserArray($siteID);
		$lang = Lang::languageUrlPrefix();
		
		$h = '<div id="perihelionUserList">';
			$h .= '<div class="container">';

				$h .= '<div class="row">';
					$h .= '<div class="col-12">';
						
						$h .= '<div class="card" >';
							$h .= '<div class="card-header">';
								$h .= '<div class="card-title"><h3>';
									$h .= Lang::getLang('userManager') . '<a href="/' . $lang . 'manager/users/grant-access/"><span class="fas fa-plus float-right"></span></a>';
								$h .= '</h3></div>';
							$h .= '</div>';
							$h .= '<div class="card-body">';
								if (!empty($userArray)) {
									$h .= '<div class="table-responsive">';
										$h .= '<table class="table table-sm table-hover">';
										
											$h .= '<thead>';
												$h .= '<tr>';
													$h .= '<th>' . Lang::getLang('id') . '</th>';
													$h .= '<th>' . Lang::getLang('name') . '</th>';
													$h .= '<th>' . Lang::getLang('userRole') . '</th>';
													$h .= '<th>' . Lang::getLang('email') . '</th>';
													$h .= '<th>' . Lang::getLang('registrationDate') . '</th>';
													$h .= '<th>' . Lang::getLang('lastVisit') . '</th>';
												$h .= '</tr>';
											$h .= '</thead>';
											
											$h .= '<tbody>';
												foreach ($userArray AS $userID) {
													$user = new User($userID);
													$userRole = new UserRole($_SESSION['siteID'],$userID);
													$h .= '<tr class="clickable" data-url="/' . $lang . 'manager/users/update/' . $userID . '/">';
														$h .= '<td>' . $user->userID . '</td>';
														$h .= '<td>' . $user->userDisplayName . '</td>';
														$h .= '<td>' . Lang::getLang($userRole->getUserRole()) . '</td>';
														$h .= '<td>' . $user->userEmail . '</td>';
														$h .= '<td>' . ($user->userRegistrationDateTime=='0000-00-00 00:00:00'?"":date('Y-m-d', strtotime($user->userRegistrationDateTime))) . '</td>';
														$h .= '<td>' . ($userRole->lastVisit=='0000-00-00 00:00:00'?"":date('Y-m-d', strtotime($userRole->lastVisit))) . '</td>';
													$h .= '</tr>';
												}
											$h .= '</tbody>';
											
										$h .= '</table>';
									$h .= '</div>';
								}
							$h .= '</div>';
						$h .= '</div>';
						
					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';
		$h .= '</div>';
			
		return $h;
		
	}

	public static function userDropdown($userID) {
		
		$users = User::getUserList();
		$h = '<select class="form-control" name="userID">';
			$h .= '<option value="">' . Lang::getLang('user') . '</option>';
			foreach($users AS $thisUserID) {
				$thisUser = new User($thisUserID);
				$h .= '<option value="' . $thisUserID . '"' . ($thisUserID==$userID?" selected":"") . '>' . $thisUser->userDisplayName . ' [' . $thisUserID . ']</option>';
			}
		$h .= '</select>';
		
		return $h;
		
	}
	
}

class UserViewDropdown {
	
	private $dropdown;
	
	public function __construct($userID, $filter = false, $size = null, $name = 'userID') {
		
		$users = User::getUserArray($_SESSION['siteID']);
		$this->dropdown = '<select class="form-control' . ($size?" form-control-".$size:"") . '" name="' . $name . '">';
			if ($filter) { $this->dropdown .= '<option value="0">----</option>'; }
			foreach($users AS $thisUserID) {
				$thisUser = new User($thisUserID);
				$this->dropdown .= '<option value="' . $thisUserID . '"' . ($thisUserID==$userID?" selected":"") . '>' . $thisUser->getUserDisplayName() . '</option>';
			}
		$this->dropdown .= '</select>';

	}
	
	public function dropdown() {
		
		return $this->dropdown;
	}

}

?>