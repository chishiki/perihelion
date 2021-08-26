<?php

class UptimeView {

	private $urlArray;
	private $inputArray;
	public $errorArray;
	
	public function __construct($urlArray = array(), $inputArray = array(),  $errorArray = array()) {

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
		$role = Auth::getUserRole();
		if ($role != 'siteAdmin' && $role != 'siteManager') { die("You do not have permissions sufficient to view the uptime module."); }
		
	}

	public function uptime($type) { // admin|manager

		$h = "<div id=\"perihelionUptime\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
							$h .= "<div class=\"card-header perihelionUptimeHeading\">";
								$h .= "<div class=\"card-title\"><h3>" . Lang::getLang('uptime') . "</h3></div>";
							$h .= "</div>";
							$h .= "<div class=\"card-body\">";
								$h .= "<div class=\"row\">";
									if ($type == 'admin') { // admin/uptime
										$h .= "<iframe class=\"col-lg-12 col-md-12 col-sm-12\" src=\"http://stats.pingdom.com/03z4gs29zo53\" frameBorder=\"0\" style=\"height:600px;\"></iframe>";
									} else { // OTHER manager/uptime
										$h .= "<iframe class=\"col-lg-12 col-md-12 col-sm-12\" src=\"http://stats.pingdom.com/03z4gs29zo53/963062\" frameBorder=\"0\" style=\"height:600px;\"></iframe>";
									}
								$h .= "</div>";
							$h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";
			
		return $h;
		
	}

}

?>