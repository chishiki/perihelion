<?php

final class AuthController implements StateControllerInterface {

	private $urlArray;
	private $inputArray;
	private $moduleArray;
	private $errorArray;
	private $messageArray;
	
	public function __construct($urlArray, $inputArray, $moduleArray) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->moduleArray = $moduleArray;
		$this->errorArray = array();
		$this->messageArray = array();
		
	}
	
	public function setState() {

		switch ($this->urlArray[0]) { 

			case 'account-recovery':
			
				if (Auth::isLoggedIn()) { header("Location: /"); }
				
				if (!empty($this->inputArray)) {

					$userEmail = $this->inputArray['userEmail'];
					// $raptcha = $this->inputArray['raptcha'];
					$this->errorArray = AccountRecovery::accountRecoveryValidation($userEmail); // add raptcha
					
					if (empty($this->errorArray)) {
					
						$accountRecovery = new AccountRecovery(0);
						$accountRecovery->accountRecoveryEmail = $userEmail;
						$accountRecovery->accountRecoveryRequestDateTime = date('Y-m-d H:i:s');
						$accountRecovery->accountRecoveryUserID = User::getUserID($userEmail);
						$accountRecoveryID = AccountRecovery::insert($accountRecovery);

						// send email
						$newAccountRecovery = new AccountRecovery($accountRecoveryID);
						$accountRecoveryMash = $newAccountRecovery->accountRecoveryMash;
						
						$user = new User($accountRecovery->accountRecoveryUserID);
						$userDisplayName = $user->getUserDisplayName();
						
						$site = new Site($_SESSION['siteID']);
						$siteName = $site->getTitle();
						$siteURL = $site->siteURL;

						$mailMessage = "<html><body>Hello <b>" . $userDisplayName . "</b>,<br /><br />You can use your email address to reset your password at the following URL:<br /><br />http://" . $siteURL . "/reset-password/" . $accountRecoveryMash . "/<br /><br /><i>Only your most recent account recovery link is valid.<br />This account recovery link is only valid for 24 hours.</i></body></html>";
						Mail::sendEmail($userEmail, "perihelion.zenidev.com <noreply@zenidev.com>", "Account Recovery for $siteName", $mailMessage, $_SESSION['siteID'], $_SESSION['userID'], "html");

						header("Location: /account-recovery-mail-sent/");
						
					}
				}
				
				break;
				
			case 'login':

				if (!empty($this->inputArray) && !Auth::isLoggedIn()) {
					
					$userSelector = $this->inputArray['userSelector'];
					$password = $this->inputArray['password'];
					$this->errorArray = Auth::checkAuth($userSelector, $password);
					if (empty($this->errorArray)) {
						$userID = User::getUserID($userSelector);
						Auth::login($userID);
						$forward_url = "/" . Lang::prefix();
						if (isset($_SESSION['forward_url'])) {
							$forward_url = $_SESSION['forward_url'];
							unset($_SESSION['forward_url']);
						}
						header("Location: $forward_url");
					}
					
				}
				
				break;
				
			case 'logout':
			
				Auth::logout();
				$redirect = '/' . Lang::prefix();
				header("Location: $redirect");
				break;
				
			case 'reset-password':
			
				if (Auth::isLoggedIn()) { header("Location: /"); }
				
				if (!empty($this->inputArray)) {
					
					$accountRecoveryMash = $this->urlArray[1];
					$confirmMash = $this->inputArray['confirmMash'];
					$userEmail = $this->inputArray['userEmail'];
					$password = $this->inputArray['password'];
					$confirmPassword = $this->inputArray['confirmPassword'];

					$this->errorArray = AccountRecovery::resetPasswordRequestValidation($accountRecoveryMash, $confirmMash, $userEmail, $password, $confirmPassword); // add raptcha

					if (empty($this->errorArray)) {

						$accountRecoveryID = AccountRecovery::getAccountRecoveryID($accountRecoveryMash);
						$ar = new AccountRecovery($accountRecoveryID);
						$ar->accountRecoveryVisited = 1;
						$arConditions = array('accountRecoveryID' => $accountRecoveryID);
						
						$userID = User::getUserID($userEmail);
						$user = new User($userID);
						$user->userPassword = password_hash($password, PASSWORD_DEFAULT);
						$userConditions = array('userID' => $userID);

						AccountRecovery::update($ar,$arConditions);
						User::update($user,$userConditions);
						Auth::login($userID);
						
						header("Location: /");

					}
				
				}
				
				
				break;

				
			default:
				header("Location: /");
				break;
				
		}

	}
	
	public function getErrors() {
		return $this->errorArray;
	}
	
	public function getMessages() {
		return $this->messageArray;
	}
	
}

?>