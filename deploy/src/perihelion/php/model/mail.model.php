<?php

class Mail extends ORM {

	public $mailID;
	public $siteID;
	public $mailSentByUserID;
	public $mailSentDateTime;
	public $mailToAddress;
	public $mailFromAddress;
	public $mailSubject;
	public $mailMessage;

	public function __construct($mailID = 0) {
	
		$this->mailID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->mailSentByUserID = $_SESSION['userID'];
		$this->mailSentDateTime = date('Y-m-d H:i:s');
		$this->mailToAddress = '';
		$this->mailFromAddress = '';
		$this->mailSubject = '';
		$this->mailMessage = '';
		
		if ($mailID) {
		
			$core = Core::getInstance();
			$query = "SELECT * FROM perihelion_Mail WHERE mailID = :mailID LIMIT 1";
			$statement = $core->database->prepare($query);
			$statement->execute(array(':mailID' => $mailID));
			if ($row = $statement->fetch()) { 
				foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } }
			}
			
		}
		
	}

	public static function sendEmail($mailRecipient, $mailSender, $mailSubject, $mailMessage, $siteKey = 0, $userID = 0, $mailType = 'plaintext') {

		$mailHeader = "From: $mailSender\n";
		$mailHeader .= "Reply-To: $mailSender\n";
		$mailHeader .= "MIME-Version: 1.0\n";
		$mailHeader .= "Content-Type: text/plain; charset=UTF-8\n";
			
		if ($mailType == 'html') {
			$mailHeader .= "Content-Type: text/html; charset=UTF-8\n";
		}

		// SEND MAIL
		
		
		
		if (mail($mailRecipient,$mailSubject,$mailMessage,$mailHeader)) {
			
			// SAVE MAIL TO DB
			$mail = new Mail();
			$mail->siteID = $_SESSION['siteID'];
			$mail->mailSentByUserID = $_SESSION['userID'];
			$mail->mailSentDateTime = date('Y-m-d H:i:s');
			$mail->mailToAddress = $mailRecipient;
			$mail->mailFromAddress = $mailSender;
			$mail->mailSubject = $mailSubject;
			$mail->mailMessage = json_encode($mailMessage);
			$mailID = Mail::insert($mail);
			
		} else {

			$ioa = new Audit();
			$ioa->auditAction = 'send';
			$ioa->auditObject = 'Mail';
			$ioa->auditResult = 'fail';
			
			$mail = new Mail();
			$mail->siteID = $_SESSION['siteID'];
			$mail->mailSentByUserID = $_SESSION['userID'];
			$mail->mailSentDateTime = date('Y-m-d H:i:s');
			$mail->mailToAddress = $mailRecipient;
			$mail->mailFromAddress = $mailSender;
			$mail->mailSubject = $mailSubject;
			$mail->mailMessage = $mailMessage;
			
			$ioa->auditNote = json_encode($mail);
			
			Audit::createAuditEntry($ioa);

		}

	}

	public static function htmlMailContentWrapper($contentInsert) {

		$lastLogoImageID = Image::lastImage($_SESSION['siteID'], 'Logo');

		$mailContent = "<html>\n";
		
			$mailContent .= "\t<body>\n";

				if ($lastLogoImageID) {
					$site = new Site($_SESSION['siteID']);
					$logo = new Image($lastLogoImageID);
					$imagePath = $logo->src();
					$mailContent .= "\t\t<img src=\"http://" . $site->siteURL . $logo->src() . "\"><br />\n";
				}

				$mailContent .= $contentInsert;

			$mailContent .= "\n\t</body>\n";
		$mailContent .= "</html>";
					
		return $mailContent;
	
	}
	
}

?>