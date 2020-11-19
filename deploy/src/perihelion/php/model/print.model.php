<?php

final class PrintPDF {

	private $loc;
	private $mod;

	public function __construct($loc = array(), $mod = array()) {
		$this->loc = $loc;
		$this->mod = $mod;
	}

	public function filename($type = 'pdf') {

		$f = 'download.' . $type;

		if (!empty($this->loc)) {

			$loc = $this->loc;
			$loc = array_filter($loc);
			foreach ($loc AS $key => $value) {
				if ($value == 'pdf' || in_array($value,$this->mod)) { unset($loc[$key]); }
			}
			$f = join('-',$loc) . '_' . date('Ymd-his' ) . '.' . $type;

		}

		return $f;

	}

}

?>