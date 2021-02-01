<?php

class SiteView {
	
	private $urlArray;
	private $inputArray;
	public $errorArray;
	public $messageArray;
	
	public function __construct($urlArray, $inputArray, $errorArray, $messageArray) {
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		$this->messageArray = $messageArray;
	}

	public function siteSettingsForm() {

		$site = new Site($_SESSION['siteID']);
		foreach ($site AS $key => $value) { ${$key} = $value; }

		if (!empty($this->inputArray)) {
			if (isset($this->inputArray['siteTitleEnglish'])) { $siteTitleEnglish = $this->inputArray['siteTitleEnglish']; }
			if (isset($this->inputArray['siteKeywordsEnglish'])) { $siteKeywordsEnglish = $this->inputArray['siteKeywordsEnglish']; }
			if (isset($this->inputArray['siteDescriptionEnglish'])) { $siteDescriptionEnglish = $this->inputArray['siteDescriptionEnglish']; }
			if (isset($this->inputArray['siteTitleJapanese'])) { $siteTitleJapanese = $this->inputArray['siteTitleJapanese']; }
			if (isset($this->inputArray['siteKeywordsJapanese'])) { $siteKeywordsJapanese = $this->inputArray['siteKeywordsJapanese']; }
			if (isset($this->inputArray['siteDescriptionJapanese'])) { $siteDescriptionJapanese = $this->inputArray['siteDescriptionJapanese']; }
		}

		$formAction = "/" . Lang::prefix() . "manager/settings/";
		$siteLogoImageID = Image::lastImage($_SESSION['siteID'], 'Logo');
		
		$h = "<div id=\"perihelionSiteSettingsForm\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
						
							$h .= "<div class=\"card-header\">";
								$h .= "<div class=\"card-title clearfix\">" . Lang::getLang('settings') . "</div>";
							$h .= "</div>";
							
							$h .= "<div class=\"card-body\">";
								
								$h .= "<form  method=\"post\" action=\"" . $formAction . "\" enctype=\"multipart/form-data\">";

									$h .= '
										<input type="hidden" name="siteID" value="' . $site->siteID . '">

										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="imageUploads">Logo</label>
											<div class="col-sm-4">
									';
									
									if ($siteLogoImageID) {
										$siteLogo = new Image($siteLogoImageID);
										$h .= '<img class="img-fluid img-thumbnail" src="' . $siteLogo->src() . '" style="margin-bottom:20px;">';
									}
									
									$h .= '
												<input type="file" name="imageUploads[]" value="qwerty" accept=\"image/*\">
											</div>
										</div>

										<hr />
										
										<div class="form-group row">
											
											<label class="col-sm-4 col-form-label" for="siteTitleEnglish">' . Lang::getLang('siteTitleEnglish') . '</label>
											
											<div class="col-sm-6">
												<input type="text" id="siteTitleEnglish" name="siteTitleEnglish" class="form-control" placeholder="' . Lang::getLang('siteTitleEnglish') . '" value="' . $siteTitleEnglish . '">
											</div>
										
										</div>
										
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="siteKeywordsEnglish">' . Lang::getLang('siteKeywordsEnglish') . '</label>
											<div class="col-sm-8">
												<input type="text" id="siteKeywordsEnglish" name="siteKeywordsEnglish" class="form-control" placeholder="' . Lang::getLang('siteKeywordsEnglish') . '" value="' . $siteKeywordsEnglish . '"">
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="siteDescriptionEnglish">' . Lang::getLang('siteDescriptionEnglish') . '</label>
											<div class="col-sm-8">
												<textarea id="siteDescriptionEnglish" name="siteDescriptionEnglish" class="form-control" placeholder="' . Lang::getLang('siteDescriptionEnglish') . '">' . $siteDescriptionEnglish . '</textarea>
											</div>
										</div>
										
										<hr />
										
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="siteTitleJapanese">' . Lang::getLang('siteTitleJapanese') . '</label>
											<div class="col-sm-6">
												<input type="text" id="siteTitleJapanese" name="siteTitleJapanese" class="form-control" placeholder="' . Lang::getLang('siteTitleJapanese') . '" value="' . $siteTitleJapanese . '">
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="siteKeywordsJapanese">' . Lang::getLang('siteKeywordsJapanese') . '</label>
											<div class="col-sm-8">
												<input type="text" id="siteKeywordsJapanese" name="siteKeywordsJapanese" class="form-control" placeholder="' . Lang::getLang('siteKeywordsJapanese') . '" value="' . $siteKeywordsJapanese . '">
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="siteDescriptionJapanese">' . Lang::getLang('siteDescriptionJapanese') . '</label>
											<div class="col-sm-8">
												<textarea id="siteDescriptionJapanese" name="siteDescriptionJapanese" class="form-control" placeholder="' . Lang::getLang('siteDescriptionJapanese') . '">' . $siteDescriptionJapanese . '</textarea>
											</div>
										</div>

									
										<hr />

										<div class="form-group row">
											<div class="col-sm-4 offset-sm-4">
												<div class="checkbox">
													<label for="siteIndexable" class="col-form-label">
														<input type="checkbox" value="1"' . ($siteIndexable?' checked':'') . ' disabled> '  . Lang::getLang('siteIndexable') . '
														<span class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="Allow robots and search engines to index your site."></span>
													</label>
												</div>
											</div>
										</div>
										
									';
									
									$h .= "<hr />";
				
									$h .= "<div class=\"form-group row\">";
										$h .= "<div class=\"col-sm-10 text-right\">";
											$h .= " <button type=\"submit\" name=\"submit\" id=\"submit\" class=\"btn btn-primary btn-sm\">" . strtoupper(Lang::getLang('update')) . "</button>";
										$h .= "</div>";
									$h .= "</div>";

								$h .= "</form>";

							$h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>"; // #perihelionManagerSettings

		return $h;


	}

	public function siteGoogleForm() {
		
		$site = new Site($_SESSION['siteID']);
		foreach ($site AS $key => $value) { ${$key} = $value; }

		if (!empty($this->inputArray)) {
			$siteGoogleAnalyticsID = $this->inputArray['siteGoogleAnalyticsID'];
			$siteGoogleAdSenseID = $this->inputArray['siteGoogleAdSenseID'];
			$siteGoogleApiKey = $this->inputArray['siteGoogleApiKey'];
		}

		$formAction = "/" . Lang::prefix() . "manager/google/";
		
		$h = "<div id=\"perihelionSiteGoogleForm\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
						
							$h .= "<div class=\"card-header\">";
								$h .= "<div class=\"card-title clearfix\">" . Lang::getLang('settings') . "</div>";
							$h .= "</div>";
							
							$h .= "<div class=\"card-body\">";
								
								$h .= "<form  method=\"post\" action=\"" . $formAction . "\">";

									$h .= '
									
										<input type="hidden" name="siteID" value="' . $site->siteID . '">

										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="siteGoogleAnalyticsID">' . Lang::getLang('googleAnalyticsTrackingID') . '</label>
											<div class="col-sm-4">
												<input type="text" id="siteGoogleAnalyticsID" name="siteGoogleAnalyticsID" class="form-control" placeholder="' . Lang::getLang('UA-XXXXXXXX-XX') . '" value="' . $siteGoogleAnalyticsID . '">
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="siteGoogleAdSenseID">' . Lang::getLang('siteGoogleAdSenseID') . '</label>
											<div class="col-sm-4">
												<input type="text" id="siteGoogleAdSenseID" name="siteGoogleAdSenseID" class="form-control" placeholder="" value="' . $siteGoogleAdSenseID . '">
											</div>
										</div>

										
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="siteGoogleApiKey">' . Lang::getLang('siteGoogleApiKey') . '</label>
											<div class="col-sm-4">
												<input type="text" id="siteGoogleApiKey" name="siteGoogleApiKey" class="form-control" placeholder="" value="' . $siteGoogleApiKey . '">
											</div>
										</div>

										<hr />
									
										<div class="form-group form-check">
											<input type="checkbox" class="form-check-input" id="siteUsesGoogleMaps" name="siteUsesGoogleMaps"' . ($site->siteUsesGoogleMaps?' checked':'') . '>
											<label class="form-check-label" for="siteUsesGoogleMaps">' . Lang::getLang('siteUsesGoogleMaps') . '</label>
										</div>
										
									';

									$h .= "<hr />";

									$h .= "<div class=\"form-group row\">";
										$h .= "<div class=\"col-sm-10 text-right\">";
											$h .= " <button type=\"submit\" name=\"submit\" id=\"submit\" class=\"btn btn-primary btn-sm\">" . strtoupper(Lang::getLang('update')) . "</button>";
										$h .= "</div>";
									$h .= "</div>";

								$h .= "</form>";

							$h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>"; // #perihelionSiteGoogleForm

		return $h;
		
	}
	
	public function siteSocialForm() {
		
		$site = new Site($_SESSION['siteID']);
		foreach ($site AS $key => $value) { ${$key} = $value; }

		if (!empty($this->inputArray)) {
			if (isset($this->inputArray['siteTwitter'])) { $siteTwitter = $this->inputArray['siteTwitter']; }
			if (isset($this->inputArray['siteFacebook'])) { $siteFacebook = $this->inputArray['siteFacebook']; }
			if (isset($this->inputArray['siteLinkedIn'])) { $siteLinkedIn = $this->inputArray['siteLinkedIn']; }
			if (isset($this->inputArray['sitePinterest'])) { $sitePinterest = $this->inputArray['sitePinterest']; }
			if (isset($this->inputArray['siteInstagram'])) { $siteInstagram = $this->inputArray['siteInstagram']; }
			if (isset($this->inputArray['siteSkype'])) { $siteSkype = $this->inputArray['siteSkype']; }
		}

		$formAction = "/" . Lang::prefix() . "manager/social/";
		
		$h = "<div id=\"perihelionSiteSocialForm\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
						
							$h .= "<div class=\"card-header\">";
								$h .= "<div class=\"card-title clearfix\">" . Lang::getLang('settings') . "</div>";
							$h .= "</div>";
							
							$h .= "<div class=\"card-body\">";
								
								$h .= "<form  method=\"post\" action=\"" . $formAction . "\">";

									$h .= '
										<input type="hidden" name="siteID" value="' . $site->siteID . '">
										
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="siteTwitter">' . Lang::getLang('twitter') . '</label>
											<div class="col-sm-4">
												<input type="text" id="siteTwitter" name="siteTwitter" class="form-control" placeholder="' . Lang::getLang('twitter') . '" value="' . $siteTwitter . '">
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="siteFacebook">' . Lang::getLang('facebook') . '</label>
											<div class="col-sm-4">
												<input type="text" id="siteFacebook" name="siteFacebook" class="form-control" placeholder="' . Lang::getLang('facebook') . '" value="' . $siteFacebook . '">
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="siteLinkedIn">' . Lang::getLang('linkedIn') . '</label>
											<div class="col-sm-4">
												<input type="text" id="siteLinkedIn" name="siteLinkedIn" class="form-control" placeholder="' . Lang::getLang('linkedIn') . '" value="' . $siteLinkedIn . '">
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="sitePinterest">' . Lang::getLang('pinterest') . '</label>
											<div class="col-sm-4">
												<input type="text" id="sitePinterest" name="sitePinterest" class="form-control" placeholder="' . Lang::getLang('pinterest') . '" value="' . $sitePinterest . '">
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="siteInstagram">' . Lang::getLang('instagram') . '</label>
											<div class="col-sm-4">
												<input type="text" id="siteInstagram" name="siteInstagram" class="form-control" placeholder="' . Lang::getLang('instagram') . '" value="' . $siteInstagram . '">
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-4 col-form-label" for="siteSkype">' . Lang::getLang('skype') . '</label>
											<div class="col-sm-4">
												<input type="text" id="siteSkype" name="siteSkype" class="form-control" placeholder="' . Lang::getLang('skype') . '" value="' . $siteSkype . '">
											</div>
										</div>
										
									';
									
									$h .= "<hr />";

									$h .= "<div class=\"form-group row\">";
										$h .= "<div class=\"col-sm-10 text-right\">";
											$h .= " <button type=\"submit\" name=\"submit\" id=\"submit\" class=\"btn btn-primary btn-sm\">" . strtoupper(Lang::getLang('update')) . "</button>";
										$h .= "</div>";
									$h .= "</div>";

								$h .= "</form>";

							$h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>"; // #perihelionManagerSettings

		return $h;
		
	}
	
	public function siteEmailForm() {
		
		$site = new Site($_SESSION['siteID']);
		foreach ($site AS $key => $value) { ${$key} = $value; }

		if (!empty($this->inputArray)) {
			$siteContactFormToAddress = $this->inputArray['siteContactFormToAddress'];
			$siteAutomatedEmailSenderName = $this->inputArray['siteAutomatedEmailSenderName'];
			$siteAutomatedEmailAddress = $this->inputArray['siteAutomatedEmailAddress'];
		}

		$formAction = "/" . Lang::prefix() . "manager/email/";
		
		$h = "<div id=\"perihelionSiteEmailForm\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
						
							$h .= "<div class=\"card-header\">";
								$h .= "<div class=\"card-title clearfix\">" . Lang::getLang('settings') . "</div>";
							$h .= "</div>";
							
							$h .= "<div class=\"card-body\">";
								
								$h .= "<form  method=\"post\" action=\"" . $formAction . "\">";

									$h .= "<div class=\"form-group row\">";
										$h .= "<label for=\"siteContactFormToAddress\" class=\"col-form-label col-sm-5\">";
											$h .= Lang::getLang('siteContactFormToAddress');
											$siteContactFormToAddressInfo = "Notifications generated by your site will be sent to you at this address.";
											$h .= " <span class=\"fas fa-info-circle\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"" . $siteContactFormToAddressInfo . "\"></span>";
										$h .= "</label>";
										$h .= "<div class=\"col-sm-5\">";
											$h .= "<input type=\"email\" id=\"siteContactFormToAddress\" name=\"siteContactFormToAddress\" class=\"form-control\" value=\"" . $siteContactFormToAddress . "\" required>";
										$h .= "</div>";
									$h .= "</div>";

									$h .= "<div class=\"form-group row\">";
										$h .= "<label for=\"siteAutomatedEmailSenderName\" class=\"col-form-label col-sm-5\">";
											$h .= Lang::getLang('siteAutomatedEmailSenderName');
											$siteAutomatedEmailSenderNameInfo = "Mails sent from your website will be sent with this sender name.";
											$h .= " <span class=\"fas fa-info-circle\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"" . $siteAutomatedEmailSenderNameInfo . "\"></span>";
										$h .= "</label>";
										$h .= "<div class=\"col-sm-5\">";
											$h .= "<input type=\"text\" id=\"siteAutomatedEmailSenderName\" name=\"siteAutomatedEmailSenderName\" class=\"form-control\" value=\"" . $siteAutomatedEmailSenderName . "\" required>";
										$h .= "</div>";
									$h .= "</div>";
									
									$h .= "<div class=\"form-group row\">";
										$h .= "<label for=\"siteAutomatedEmailAddress\" class=\"col-form-label col-sm-5\">";
											$h .= Lang::getLang('siteAutomatedEmailAddress');
											$siteAutomatedEmailAddressInfo = "Mails sent from your website will be sent from this address.";
											$h .= " <span class=\"fas fa-info-circle\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"" . $siteAutomatedEmailAddressInfo . "\"></span>";
										$h .= "</label>";
										$h .= "<div class=\"col-sm-5\">";
											$h .= "<input type=\"email\" id=\"siteAutomatedEmailAddress\" name=\"siteAutomatedEmailAddress\" class=\"form-control\" value=\"" . $siteAutomatedEmailAddress . "\" required>";
										$h .= "</div>";
									$h .= "</div>";

									$h .= "<hr />";
									
									$h .= "<div class=\"form-group row\">";
										$h .= "<div class=\"col-sm-10 text-right\">";
											$h .= " <button type=\"submit\" name=\"submit\" id=\"submit\" class=\"btn btn-primary btn-sm\">" . strtoupper(Lang::getLang('update')) . "</button>";
										$h .= "</div>";
									$h .= "</div>";

								$h .= "</form>";

							$h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>"; // #perihelionManagerSettings

		return $h;
		
	}
	
	public function siteModulesForm() {
		
		// $site = new Site($_SESSION['siteID']);
		// foreach ($site AS $key => $value) { ${$key} = $value; }

		// if (!empty($this->inputArray)) {
			if (isset($this->inputArray['xxxxxxx'])) { $xxxxxxx = 1; } else { $xxxxxxx = 0; }
			if (isset($this->inputArray['yyyyyyy'])) { $yyyyyyy = $this->inputArray['yyyyyyy']; } else { $yyyyyyy = 0; }
		// }

		$formAction = "/" . Lang::prefix() . "manager/modules/";
		
		$h = "<div id=\"perihelionSiteModulesForm\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
						$h .= '<div class="card-header">Site Modules</div>';
						$h .= "<div class=\"card-body\" >";
						$h .= '
                            <form>
                            
                              <div class="form-group">
                                <label for="formGroupExampleInput">Example label</label>
                                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Example input">
                              </div>
                              <div class="form-group">
                                <label for="formGroupExampleInput2">Another label</label>
                                <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Another input">
                              </div>
                            
                            <hr />
                            
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
                              <label class="form-check-label" for="exampleRadios1">
                                Default radio
                              </label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
                              <label class="form-check-label" for="exampleRadios2">
                                Second default radio
                              </label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3" value="option3" disabled>
                              <label class="form-check-label" for="exampleRadios3">
                                Disabled radio
                              </label>
                            </div>
                            
                            <hr />

                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                              <label class="form-check-label" for="defaultCheck1">
                                Default checkbox
                              </label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="defaultCheck2" disabled>
                              <label class="form-check-label" for="defaultCheck2">
                                Disabled checkbox
                              </label>
                            </div>

                            <hr />
                            
                            <button type="submit" class="btn btn-primary float-right">Submit</button>
                            
                            </form>
                        ';
						$h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>"; // #perihelionSiteModulesForm

		return $h;
		
	}
	
	public static function sitesDropdown($siteID) {
		
		$sites = Site::getSiteList();
		$h = "<select class=\"form-control\" name=\"siteID\">";
			$h .= "<option value=\"\">" . Lang::getLang('site') . "</option>";
			foreach($sites AS $thisSiteID) {
				$thisSite = new Site($thisSiteID);
				$h .= "<option value=\"" . $thisSiteID . "\"" . ($thisSiteID==$siteID?" selected":"") . ">[" . $thisSite->siteKey . "] " . $thisSite->getTitle() . " [" . $thisSiteID . "]</option>";
			}
		$h .= "</select>";
		
		return $h;
	
	}
	
}

?>