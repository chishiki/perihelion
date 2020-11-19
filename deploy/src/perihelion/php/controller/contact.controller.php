<?php

class ContactController {

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
		$contactURLs = array('contact','contact-us','get-in-touch');
		
		
		
		if (in_array($this->urlArray[0],$contactURLs)) {
			
			if (!empty($post) && isset($_SESSION['obFussyCat']) && isset($_SESSION['zaptcha'])) {
				
				// $this->errorArray = Contact::contactFormValidate($this->inputArray);

				if (!isset($post[$_SESSION['obFussyCat']])) { $this->errorArray['obFussyCat'][] = 'We apologize. There was a problem submitting your information.'; }
				if (!isset($post['contactName']) || empty($post['contactName'])) { $this->errorArray['contactName'][] = 'Please enter a name.'; }
				if (!isset($post['contactEmail']) || empty($post['contactEmail'])) { $this->errorArray['contactEmail'][] = 'Please enter an email address.'; }
				if (!isset($post['zaptcha']) || $post['zaptcha'] != $_SESSION['zaptcha']){ $this->errorArray['zaptcha'][] = 'The security code was incorrect.'; }
				if (isset($post['contactContent']['contactMessage'])) {
					$banned = BlacklistWord::words();
					$matchFound = preg_match_all("/\b(".implode($banned,"|").")\b/i",$post['contactContent']['contactMessage'],$matches);
					if ($matchFound) { $this->errorArray['contactContent'][] = 'We are sorry. Our language filter prevented your message from being sent.'; }
				}
				
				if (empty($this->errorArray)) {
					
					$hachimitsu = true;
					if (empty($post['message']) && empty($post['url'])) { $hachimitsu = false; }
					
					$contact = new Contact();
					$contact->contactName = $post['contactName'];
					$contact->contactEmail = $post['contactEmail'];
					$contact->contactContent = json_encode($post['contactContent']);
					if ($hachimitsu) { $contact->contactName = '[hachimitsu] ' . $contact->contactName; }
					Contact::insert($contact);

					$siteID = $_SESSION['siteID'];
					$userID = $_SESSION['userID'];
					
					$site = new Site($siteID);
					
					$toAddress = $site->siteContactFormToAddress;
					$fromAddress = $site->siteAutomatedEmailSenderName . ' <' . $site->siteAutomatedEmailAddress . '>';
					$mailSubject = '[ a message from ' . $contact->contactName . ' via site contact page ]';

					$contentInsert = Lang::getLang('name') . ": <b>" . $contact->contactName . "</b><br />\n";
					$contentInsert .= Lang::getLang('email') . ": <b>" . $contact->contactEmail . "</b><br />\n";
					foreach ($post['contactContent'] AS $key => $value) {
						$contentInsert .= Lang::getLang($key) . ": <b>";
						if (is_array($value)) { $contentInsert .= join(', ',$value); } else { $contentInsert .= strip_tags($value); }
						$contentInsert .= "</b><br />\n";
					}	
					$mailContent = Mail::htmlMailContentWrapper($contentInsert);

					if (!$hachimitsu) {
						Mail::sendEmail($toAddress, $fromAddress, $mailSubject, $mailContent, $siteID, $userID, 'html');
						if (isset($post['contactContent']['contactNewsletter']) && Utilities::isValidEmail($contact->contactEmail)) {
							$s = new NewsletterSubscription(0, $contact->contactEmail);
							$s->subscriberName = $contact->contactName;
							NewsletterSubscription::insert($s);
						}
					} else {
						$mailSubject = '[hachimitsu] ' . $mailSubject;
					}
					
					
					Mail::sendEmail('support@zenidev.com', $fromAddress, $mailSubject, $mailContent, $siteID, $userID, 'html');

					if ($_SESSION['lang'] == 'ja') { $lang = "/ja"; } else { $lang = ""; }
					$successURL = $lang . "/" . $this->urlArray[0] . "/thank-you/";
					header("Location: $successURL");
					
				}
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