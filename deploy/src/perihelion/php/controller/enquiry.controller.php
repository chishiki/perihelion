<?php

final class EnquiryController implements StateControllerInterface {

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

		$post = $this->inputArray;

		if (!empty($post)) {
			
			// $this->errorArray = Enquiry::validate($post);

			if (!isset($post['enquiry-name']) || empty($post['enquiry-name'])) { $this->errorArray['enquiry-name'][] = 'Please enter your name.'; }
			if (!isset($post['enquiry-email']) || empty($post['enquiry-email'])) { $this->errorArray['enquiry-email'][] = 'Please enter an email address.'; }
			if (isset($post['enquiry-email']) && !Utilities::isValidEmail($post['enquiry-email'])) { $this->errorArray['enquiry-email'][] = 'That email address does not appear to be valid'; }
			
			
			if (!isset($post['enquiry-acknowledge'])) { $this->errorArray['enquiry-acknowledge'][] = 'Please acknowledge our terms of service and privacy policy.'; }
			
			if (!isset($post[$_SESSION['obFussyCat']])) { $this->errorArray['obFussyCat'][] = 'We apologize. There was a problem submitting your enquiry.'; }
			if (!isset($post['zaptcha']) || empty($post['zaptcha']) || $post['zaptcha'] != $_SESSION['zaptcha']){ $this->errorArray['zaptcha'][] = 'The security code was incorrect.'; }

			if (empty($this->errorArray)) {
				
				$hachimitsu = true;
				if (empty($post['message']) && empty($post['url'])) { $hachimitsu = false; }

				$siteID = $_SESSION['siteID'];
				$userID = $_SESSION['userID'];
				$site = new Site($siteID);
				
				$to = Config::read('support.email');
				$from = Config::read('support.replyto.email');
				$subject = ($hachimitsu?'[hachimitsu] ':'') . '[' . $post['enquiry-name'] . ' has requested more information about perihelion.zenidev.com]';
				$content = ($hachimitsu?'<p>[hachimitsu]</p>':'') . '<p><pre>' . print_r($post,true) . '</pre></p><p>' . $_SERVER['REMOTE_ADDR'] . '</p>';
				$content = Mail::htmlMailContentWrapper($content);

				Mail::sendEmail($to, $from, $subject, $content, $siteID, $userID, 'html');
				
				if (isset($post['enquiry-subscribe']) && Utilities::isValidEmail($post['enquiry-email'])) {
					$s = new NewsletterSubscription(0, $post['enquiry-email']);
					$s->subscriberName = $post['enquiry-name'];
					NewsletterSubscription::insert($s);
				}

				$successURL = "/" . Lang::prefix() . "enquiry/thank-you/";
				header("Location: $successURL");
				
			}
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