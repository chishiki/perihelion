<?php

class Message extends ORM {

	public $messageID;
	public $messageSenderUserID;
	public $messageRecipientUserID;
	public $messageContent;
	public $messageDateTimeSent;
	public $messageSenderIP;
	public $messageReadByRecipient;

	public function __construct($messageID = 0) {
	
		
		if ($messageID != 0) {
		
			$core = Core::getInstance();
			$query = "SELECT * FROM perihelion_Message WHERE messageID = :messageID LIMIT 1";
			$statement = $core->database->prepare($query);
			$statement->execute(array(':messageID' => $messageID));
			if (!$row = $statement->fetch()) { die('Message does not exist.'); }
			foreach ($row AS $key => $value) { if (!is_int($key)) { $this->$key = $value; } }
			
		} else {

			$this->messageID = 0;
			$this->messageSenderUserID = 0;
			$this->messageRecipientUserID = 0;
			$this->messageContent = '';
			$this->messageDateTimeSent = date('Y-m-d H:i:s');
			$this->messageSenderIP = $_SERVER['REMOTE_ADDR'];
			$this->messageReadByRecipient = 0;

		}
		
	}


	public static function markMessageAsRead($messageID) {
		$core = Core::getInstance();
		$query = "UPDATE perihelion_Message SET messageReadByRecipient = 1 WHERE messageID = :messageID LIMIT 1";
		$statement = $core->database->prepare($query);
		$statement->execute(array(':messageID' => $messageID));
	}
	
	public static function getMessages() {
		
		$core = Core::getInstance();
		$query = "SELECT messageID FROM perihelion_Message WHERE messageRecipientUserID = :userID ORDER BY messageDateTimeSent DESC";
		$statement = $core->database->prepare($query);
		$statement->execute(array(':userID' => $_SESSION['userID']));
		$messages = array();
		while ($row = $statement->fetch()) { $messages[] = $row['messageID']; }
		return $messages;
		
	}
}

?>