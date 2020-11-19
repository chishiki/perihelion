<?php

class Currency {

	private $code;
	
	public function __construct($currency) { // ISO 4217

		$currency = strtoupper($currency);
		
		switch ($currency) {
			case('JPY'):
				$this->code = '&yen;';
				break;
			case('USD'):
				$this->code = '&dollar;';
				break;
			default:
				$this->code = '?';
		}

	}
	

	public function code() {
			
		return $this->code;
			
	}

}

?>