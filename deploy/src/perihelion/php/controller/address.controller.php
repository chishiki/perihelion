<?php

class AddressController {

	private $loc;
	private $input;
	private $modules;
	private $errors;
	private $messages;
	
	public function __construct($loc, $input, $modules) {
		
		$this->loc = $loc;
		$this->input = $input;
		$this->modules = $modules;
		$this->errors = array();
		$this->messages = array();
		
	}
	
	public function setState($baseURL, $action, $addressObject, $addressObjectID, $addressID) {

		
		
		if ($action == 'create' && !empty($this->input)) {

			$addy = new Address();
			foreach ($this->input AS $property => $value) { if (isset($addy->$property)) { $addy->$property = $value; } }

			$ad = new AddressDefault($addressObject, $addressObjectID, true);
			$defaultAddress = $ad->address();
			if (empty($defaultAddress)) { $addy->addressDefault = 1; }
			
			Address::insert($addy);
			
			header("Location: $baseURL");
			
		} elseif ($action == 'update' && !empty($this->input)) {

			// update an address
			
		} elseif ($action == 'delete' && ctype_digit($addressID)) {

			$addy = new Address($addressID);
			$addy->updated = date('Y-m-d H:i:s');
			$addy->deleted = 1;
			$conditions = array('addressID' => $addressID);
			Address::update($addy, $conditions);
			
			header("Location: $baseURL");

		} elseif ($action == 'set-default' && ctype_digit($addressID)) {

			$addy = new Address($addressID);
			$addy->updated = date('Y-m-d H:i:s');
			$addy->addressDefault = 1;
			$conditions = array('addressID' => $addressID);
			Address::update($addy, $conditions);
				
			$a = new Addresses($addressObject, $addressObjectID);
			$addresses = $a->list();
			
			foreach ($addresses AS $thisAddressID) {
				if ($addressID != $thisAddressID) {
					$a = new Address($thisAddressID);
					$a->updated = date('Y-m-d H:i:s');
					$a->addressDefault = 0;
					$conditions = array('addressID' => $thisAddressID);
					Address::update($a, $conditions);
				}
			}

			header("Location: $baseURL");

		}

	}
	
	public function getErrors() {
		return $this->errors;
	}
	
	public function getMessages() {
		return $this->messages;
	}
	
}

?>