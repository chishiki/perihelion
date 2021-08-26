<?php

class ContactView {
	
	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
	}

	public function contactForm() {

		$cf = new ContactForm();
		foreach ($cf AS $key => $value) { ${$key} = $value;}
		
		$companyName = $cf->companyName();
		$address = $cf->address();
		$telephone = $cf->telephone();
		$fax = $cf->fax();
		$coordinates = $cf->coordinates();
		$officeHours = $cf->officeHours();
		
		if ($address != '') { $displayContactOfficeAddress = true; } else { $displayContactOfficeAddress = false; }

		$contactName = '';
		$contactEmail = '';
		$contactContent['contactTelephone'] = '';
		$contactContent['contactPreferredCorrespondence'] = array('email','telephone');
		$contactContent['contactNewsletter'] = 'subscribe';
		$contactContent['contactReasons'] = array();
		$contactContent['contactPropertyAreas'] = array();
		$contactContent['contactBudget'] = '';
		$contactContent['contactMessage'] = '';

		if (!empty($this->inputArray)) {
			if (isset($this->inputArray['contactName'])) { $contactName = $this->inputArray['contactName']; }
			if (isset($this->inputArray['contactEmail'])) { $contactEmail = $this->inputArray['contactEmail']; }
			if (isset($this->inputArray['contactContent'])) {
				$contactContent = $this->inputArray['contactContent'];
				if (!isset($contactContent['contactNewsletter'])) { $contactContent['contactNewsletter'] = ''; }
				if (isset($contactContent['attributes']) && !empty($contactContent['attributes'])) {
				
					$attributeCount = count($contactContent['attributes']);
					for ($i = 1; $i <= $attributeCount; $i++) { // start with one to ignore "contact" or its aliases
						$this->urlArray[$i] = $contactContent['attributes'][$i-1];
					}
				}
			}
		}

		// $zaptcha requires a the form to be viewed to create a new code
		// $obFussyCat confirms and tests that the form was viewed triggering a new $zaptcha
		// $zaptcha and $obFussyCat are reset each time this form is viewed
		
		$obFussyCat = substr(str_shuffle(MD5(microtime())), 0, 10);
		$_SESSION['obFussyCat'] = $obFussyCat;
		
		$h = "<div id=\"perihelionContactForm\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";

						$h .= "<div class=\"container\">";

							if ($displayLocationMap) {

								$h .= "<div class=\"col-sm-12 col-md-5\">";
							
									$h .= "<h3 class=\"themePrimaryColor\">" . Lang::getLang('contactOurLocation') . " <span class=\"fas fa-thumbtack\"></span></h3>";
									$h .= "<div id=\"map-canvas\" class=\"col-md-12\" style=\"margin-bottom:10px;\"></div>";
										
									$h .= '
										<script>
											var map;
											function initialize() {
												var mapOptions = {
													zoom: ' . $locationMapZoom . ',
													scrollwheel: false,
													center: new google.maps.LatLng(' . $coordinates . ')
												};
												map = new google.maps.Map(document.getElementById(\'map-canvas\'), mapOptions);
												var latlng = new google.maps.LatLng(' . $coordinates . ');
												var marker = new google.maps.Marker({
													position: latlng,
													map: map
												});
											}
											google.maps.event.addDomListener(window, \'load\', initialize);
										</script>
									';
									
									if ($displayContactOfficeAddress) {
										$h .= "<h3 class=\"themePrimaryColor\">" . Lang::getLang('contactOfficeAddress') . " <span class=\"fas fa-map-pin\"></span></h3>";
									}
									
									$h .= "<address>";
										if ($companyName) { $h .= "<strong>" . $companyName . "</strong><br />"; }
										if ($address) { $h .= $address . "<br />"; }
										if ($telephone) { $h .= "<abbr title=\"Telephone\">T:</abbr> <a href=\"tel:" . preg_replace("/[^0-9]/","",$telephone) . "\">" . $telephone . "</a>"; }
										if ($telephone && $fax) { $h .= "<br />"; }
										if ($fax) { $h .= "<abbr title=\"Fax\">F:</abbr> " . $fax; }	
									$h .= "</address>";
									
									if ($displayOfficeHours && !empty($officeHours)) {
										$h .= "<h3 class=\"themePrimaryColor\">" . Lang::getLang('officeHours') . " <span class=\"fas fa-clock\"></span></h3>";
										$h .= $officeHours;
									}
									
								$h .= "</div>";
								
							}
							
							
							$h .= "<div class=\"col-sm-12 col-md-7\" style=\"margin-bottom:10px;\">";
							
								if (!$displayLocationMap) {
								
									if ($displayContactOfficeAddress) {
										$h .= "<h3 class=\"themePrimaryColor\">" . Lang::getLang('contactOfficeAddress') . " <span class=\"fas fa-map-pin\"></span></h3>";
									}
									
									$h .= "<address>";
										if ($companyName) { $h .= "<strong>" . $companyName . "</strong><br />"; }
										if ($address) { $h .= $address . "<br />"; }
										if ($telephone) { $h .= "<abbr title=\"Telephone\">T:</abbr> <a href=\"tel:" . preg_replace("/[^0-9]/","",$telephone) . "\">" . $telephone . "</a>"; }
										if ($telephone && $fax) { $h .= "<br />"; }
										if ($fax) { $h .= "<abbr title=\"Fax\">F:</abbr> " . $fax; }	
									$h .= "</address>";
									
								}
							
								$h .= "<h3 class=\"themePrimaryColor\">" . Lang::getLang('contactGetInTouch') . " <span class=\"fas fa-envelope\"></span></h3>";
								$h .= "<form role=\"form\" method=\"post\" action=\"/" . Lang::prefix() . $this->urlArray[0] . "/\">";
								 
									$h .= "<input type=\"hidden\" name=\"" . $obFussyCat . "\" value=\"contact\">";
									
									$h .= "<div class=\"form-group row" . (!empty($this->errorArray['contactName'])?" has-error":"") . "\">";
										$h .= "<label class=\"col-form-label\" for=\"contactName\">" . Lang::getLang('contactName') . "</label>";
										$h .= "<input type=\"text\" name=\"contactName\" class=\"form-control\" id=\"contactName\" placeholder=\"Your Name\" value=\"" . $contactName . "\">";
										if (!empty($this->errorArray['contactName'])) {
											foreach($this->errorArray['contactName'] AS $error) {
												$h .= "<p class=\"bg-danger\"><small>" . $error . "</small></p>";
											}
										}
									$h .= "</div>";
									
									$h .= "<div class=\"form-group row" . (!empty($this->errorArray['contactEmail'])?" has-error":"") . "\">";
										$h .= "<label class=\"col-form-label\" for=\"contactEmail\">" . Lang::getLang('contactEmail') . "</label>";
										$h .= "<input type=\"email\" name=\"contactEmail\" class=\"form-control\" id=\"contactEmail\" placeholder=\"Your Email\" value=\"" . $contactEmail . "\">";
										if (!empty($this->errorArray['contactEmail'])) {
											foreach($this->errorArray['contactEmail'] AS $error) {
												$h .= "<p class=\"bg-danger\"><small>" . $error . "</small></p>";
											}
										}
									$h .= "</div>";
									
									if ($promptForPhoneNumber) {
										
										$h .= "<div class=\"form-group row\">";
											$h .= "<label for=\"contactTelephone\">" . Lang::getLang('contactTelephone') . "</label>";
											$h .= "<input type=\"tel\" name=\"contactContent[contactTelephone]\" class=\"form-control\" id=\"contactTelephone\" placeholder=\"Enter Telephone number\" value=\"" . $contactContent['contactTelephone'] . "\">";
										 $h .= "</div>";
									
									}
									
									if ($promptForPreferredCorrespondence) {
										
										$h .= "<div class=\"form-group row\">";
											$h .= "<label for=\"prefered correspondence\">How may we contact you?</label><br/>";
											$h .= "<label class=\"checkbox-inline\">";
												$h .= "<input type=\"checkbox\" name=\"contactContent[contactPreferredCorrespondence][]\" id=\"inlineCheckbox1\" value=\"telephone\"".(in_array('telephone',$contactContent['contactPreferredCorrespondence'])?" checked":"")."> ";
												$h .= "Telephone";
											$h .= "</label>";
											$h .= "<label class=\"checkbox-inline\">";
											  $h .= "<input type=\"checkbox\" name=\"contactContent[contactPreferredCorrespondence][]\" id=\"inlineCheckbox2\" value=\"email\"".(in_array('email',$contactContent['contactPreferredCorrespondence'])?" checked":"").">";
											  $h .= "Email";
											$h .= "</label>";
										 $h .= "</div>";

									}
									
									if ($promptForContactReason) {
										
										$contactReasonOptions = array('buy','sell','propertyManagement','rental','accommodation','other');
										
										$selectedReasons = array();
										if (isset($contactContent['contactReasons'])) { $selectedReasons = $contactContent['contactReasons']; }
										
										$h .= "<div class=\"form-group row\">";
											$h .= "<label for=\"contactReason\">How can we be of assistance?</label><br />";
											foreach ($contactReasonOptions AS $reason) {
												$reasonString = Lang::getLang('contactReason'.ucwords($reason));
												$h .= "<div class=\"checkbox\">";
													$h .= "<label>";
														$h .= "<input type=\"checkbox\" name=\"contactContent[contactReasons][]\" id=\"contactReason" . ucwords($reason) . "\" value=\"" . $reason . "\"" . (in_array($reason,$selectedReasons)?" checked":"") . ">";
														$h .= $reasonString;
													$h .= "</label>";
												$h .= "</div>";
											}
										 $h .= "</div>";
									}

									if ($promptForBudget) {
										$h .= "<div class=\"form-group row\">";
											$h .= "<label for=\"contactBudget\">" . Lang::getLang('contactBudget') . "</label>";
											$h .= "<input type=\"text\" name=\"contactContent[contactBudget]\" class=\"form-control\" id=\"contactBudget\" placeholder=\"" . $budgetCurrencyPrefix . "\" value=\"" . $contactContent['contactBudget'] . "\">";
										$h .= "</div>";
									}
									
									$h .= "<div class=\"form-group row\">";
										$h .= "<label for=\"contactMessage\">" . Lang::getLang('yourMessage') . "</label>";
										$h .= "<textarea name=\"contactContent[contactMessage]\" id=\"contactMessage\" class=\"form-control\" rows=\"3\">" . $contactContent['contactMessage'] . "</textarea>";
									$h .= "</div>";

									$h .= "<div class=\"form-group row\">";
										$h .= "<label class=\"checkbox-inline\" for=\"contactNewsletter\">";
											$h .= "<input type=\"checkbox\" name=\"contactContent[contactNewsletter]\" id=\"contactNewsletter\" value=\"subscribe\"".($contactContent['contactNewsletter']=='subscribe'?" checked":"").">";
											$h .= Lang::getLang('subscribeToNewsletter');
										$h .= "</label>";
									$h .= "</div>";

									$h .= "<div class=\"form-group row" . (!empty($this->errorArray['zaptcha'])?" has-error":"") . "\">";
										$h .= "<label for=\"zaptcha\">" . Lang::getLang('pleaseEnterZaptchaCode') . "<img src=\"/perihelion/zaptcha/zaptcha.php\" style=\"margin-bottom:5px;\"></label>";
										$h .= "<input type=\"text\" name=\"zaptcha\" class=\"form-control\" id=\"zaptcha\" placeholder=\"Enter Code\" value=\"\">";
										if (!empty($this->errorArray['zaptcha'])) {
											foreach($this->errorArray['zaptcha'] AS $error) {
												$h .= "<p class=\"bg-danger\"><small>" . $error . "</small></p>";
											}
										}
									$h .= "</div>";

									$attributeCount = count($this->urlArray);
									for ($i = 1; $i < $attributeCount; $i++) { // start with one to ignore "contact" or its aliases
										if (isset($this->urlArray[$i]) && !empty($this->urlArray[$i])) {
											$h .= "<input type=\"hidden\" name=\"contactContent[attributes][]\" value=\"" . $this->urlArray[$i] . "\">";
										}
									}
									
									$h .= "<div id=\"hachimitsu\" class=\"form-group row\">";
										$h .= "<input type=\"text\" name=\"message\">";
										$h .= "<input type=\"url\" name=\"url\">";
									$h .= "</div>";
									
									$h .= "<div class=\"form-group row\">";
										$h .= "<button type=\"submit\" name=\"submit\" class=\"btn btn-primary col-lg-12\">" . Lang::getLang('submit') . "</button>";
									$h .= "</div>";
									
								$h .= "</form>";
								
							$h .= "</div>";

						$h .= "</div>";

					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";

		return $h;

	}
	
	public function contactList() {
		
		$contactArray = Contact::contactArray($_SESSION['siteID']);
		
		$h = "<div id=\"perihelionContactList\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";

							$h .= "<div class=\"card\" >";
								$h .= "<div class=\"card-header\"><div class=\"card-title\"><h3>" . Lang::getLang('contact') . "</h3></div></div>";
								$h .= "<div class=\"card-body\">";

									$h .= "<div class=\"list-group\">";
										foreach($contactArray AS $contactID) {
											$contact = new Contact($contactID);
											$h .= "<a class=\"list-group-item\" href=\"/manager/contacts/view/" . $contactID . "/\">[" . $contact->contactDateTime . "] " . $contact->contactName . "</a>";
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

	public function contactThankYou() {

		$h = "<div id=\"perihelionContactThankYou\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";

							$h .= "<div class=\"card\" >";
								$h .= "<div class=\"card-header\"><div class=\"card-title\">" . Lang::getLang('thankYou') . "</div></div>";
								$h .= "<div class=\"card-body\">";
									$h .= "<p>Thank you for reaching out. We will follow up with you shortly.</p>";
								$h .= "</div>";
							$h .= "</div>";

					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";

		return $h;

	}

	public static function contactView($contactID) {
		
		$contact = new Contact($contactID);
		$content = json_decode($contact->contactContent);
		
		$h = "<div id=\"perihelionContactView\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
							
							$h .= "<div class=\"card-header\">";
								$h .= "<div class=\"card-title\">";
									$h .= $contact->contactName;
									$h .= "<span class=\"float-right\"><i>" . $contact->contactDateTime . " [" . $contact->contactIP . "]</i></span>";
								$h .= "</div>";
							$h .= "</div>";
							
							$h .= "<div class=\"card-body\">";

								$h .= $contact->contactEmail . '<br />';
								if(isset($content->contactTelephone) && !empty($content->contactTelephone)) { $h .= $content->contactTelephone; }
								$h .= '<hr />';
								
								if (isset($content->contactPreferredCorrespondence) && !empty($content->contactPreferredCorrespondence)) {
									$h .= Lang::getLang('contactPreferredCorrespondence');
										foreach ($content->contactPreferredCorrespondence AS $howToContact) { $h .= '<br />&#10003; ' . $howToContact; }
									$h .= '<hr />';
								}

								if (isset($content->contactBudget) && !empty($content->contactBudget)) {
									$h .= Lang::getLang('contactBudget') . ': ' . $content->contactBudget . '<hr />';
								}
								
								if (isset($content->contactMessage) && !empty($content->contactMessage)) {
									$h .= '<p>' . Lang::getLang('contactMessage') . ':</p><p><b>' . nl2br($content->contactMessage) . '</b></p><hr />';
								}
								
								if (isset($content->contactNewsletter) && !empty($content->contactNewsletter)) {
									$h .= Lang::getLang('contactNewsletter') . ": " . $content->contactNewsletter . '<hr />';
								}
								
								if (isset($content->attributes) && !empty($content->attributes)) {
									$h .= Lang::getLang('attributes') . ": " . join(', ',$content->attributes);
								}

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