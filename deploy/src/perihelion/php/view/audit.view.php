<?php

class AuditView {
	
	private $urlArray;
	private $inputArray;
	private $errorArray;

	
	public function __construct($urlArray = array(), $inputArray = array(),  $errorArray = array()) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
	}
	
	public function auditTrail($type, $siteID = '', $userID = '', $auditObject = '') { // manager|admin

		$auditTrail = Audit::getAuditTrailArray($type, $siteID, $userID, $auditObject);
		
		$h = "<div id=\"perihelionAuditTrail\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
							$h .= "<div class=\"card-header\">";
								$h .= "<div class=\"card-title\">" . Lang::getLang('auditTrail') . "<span class=\"float-right\">" . date('Y-m-d H:i:s') . " (UTC)</span></div>";
							$h .= "</div>";
							$h .= "<div class=\"card-body\">";

								if ($type == 'admin') {

									$h .= "<form method=\"post\" action=\"/" . Lang::prefix() . "admin/audit/\">";
										$h .= "<div class=\"form-group row\">";
											$h .= "<div class=\"col-md-3\">" . SiteView::sitesDropdown($siteID) . "</div>";
											$h .= "<div class=\"col-md-3\">" . UserView::userDropdown($userID) . "</div>";
											$h .= "<div class=\"col-md-3\">" . self::auditObjectDropdown($auditObject) . "</div>";
											$h .= "<div class=\"col-md-3\"><input class=\"form-control btn btn-primary\" type=\"submit\"></div>";
										$h .= "</div>";
									$h .= "</form>";
									
									$h .= "<hr />";
									
								}
							
								$h .= "<div class=\"table-responsive\">";
									$h .= "<table class=\"table table-striped table-sm\">";
										
										foreach ($auditTrail as $auditID) {
											
											$ioa = new Audit($auditID);
											$site = new Site($ioa->siteID);
											$user = new User($ioa->auditUserID);

											$h .= "<tr>";
											
												if ($type == 'admin') {
													$h .= "<td style=\"white-space:nowrap;\"><span><img src=\"/perihelion/assets/images/favicons/favicon-" . $ioa->siteID . ".ico\" style=\"width:16px;\"></span> " . $site->siteURL . "</td>";
												}
												
												$h .= "<td style=\"white-space:nowrap;\"><span class=\"fas fa-clock\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . $ioa->auditDateTime . "\"></span> " . $ioa->auditDateTime . "</td>";
												$h .= "<td style=\"white-space:nowrap;\">";
													$h .= "<a href=\"http://whatismyipaddress.com/ip/" . $ioa->auditIP . "\" target=\"_blank\">";
														$h .= "<span class=\"fas fa-globe\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . $ioa->auditIP . "\"></span> ";
													$h .= "</a> ";
													$h .= $ioa->auditIP;
												$h .= "</td>";
												$h .= "<td>" . $user->username . "</td>";
												$h .= "<td>" . $ioa->auditAction . "</td>";
												
												// switch case => which entities can/should we link to directly from here
												$h .= "<td>" . $ioa->auditObject . " " . ($ioa->auditObjectID?"[".$ioa->auditObjectID."]":"") . "</td>";

												$h .= "<td>" . $ioa->auditResult . "</td>";

												$h .= "<td>";
												
												if ($type == 'admin' && $ioa->auditObject != 'Content' && !empty($ioa->auditNote)) {
															
													$json = json_decode($ioa->auditNote);
													$output = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
													
													$h .= "<button type=\"button\" class=\"btn btn-secondary btn-sm\" data-toggle=\"modal\" data-target=\"#myModal" . $ioa->auditID . 
													"\">View</button>";

													$h .= "
													<div class=\"modal fade\" id=\"myModal" . $ioa->auditID . 
													"\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\">
														<div class=\"modal-dialog\" role=\"document\">
															<div class=\"modal-content\">
																<div class=\"modal-header\">
																	<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
																	<h4 class=\"modal-title\" id=\"myModalLabel\">Modal title</h4>
																</div>
																<div class=\"modal-body\">
																<pre>" . htmlspecialchars($output) . "</pre>
																</div>
																<div class=\"modal-footer\">
																	<button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Close</button>
																</div>
															</div>
														</div>
													</div>
													";
													
												}
												
												$h .= "</td>";

											$h .= "</tr>";
											
										}
										
									$h .= "</table>";
								$h .= "</div>";
								
								
							$h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";
		
		return $h;
		
	}
	
	public static function auditObjectDropdown($auditObject) {
		$auditObjects = Audit::getAuditObjectArray();
		$h = "<select class=\"form-control\" name=\"auditObject\">";
			$h .= "<option value=\"\">" . Lang::getLang('object') . "</option>";
			foreach($auditObjects AS $thisAuditObject) {
				$h .= "<option value=\"" . $thisAuditObject . "\"" . ($thisAuditObject==$auditObject?" selected":"") . ">" . $thisAuditObject . "</option>";
			}
		$h .= "</select>";
		return $h;
	}

}

?>