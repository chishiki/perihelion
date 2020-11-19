<?php

class EnquiryView {
	
	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
	}

	public function getMoreInfoForm() {

		if (!empty($this->inputArray)) {
			foreach ($this->inputArray AS $key => $value) { if (isset($content->$key)) { $content->$key = $value; } }
		}
		
		$tron = new Content(2000021);
		$features = new Content(2000020);

		$obFussyCat = substr(str_shuffle(MD5(microtime())), 0, 10);
		$_SESSION['obFussyCat'] = $obFussyCat;
		
		$h = '

		<div id="getMoreInfoForm" class="container">
	
			<div class="row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-12">
					' . $tron->content() . '
				</div>
			</div>

			<div class="row">

				<div class="col-12 col-sm-6">
					' . $features->content() . '
				</div>

				<div class="col-12 col-sm-6">
					
					<div class="formBox">

						<div class="formArrow pull-left hidden-xs hidden-sm">
							<span id="look_over_there" class="fas fa-arrow-right"></span>
						</div>
					
						<h2>' . Lang::getLang('requestMoreInformation') . '</h2>
					
						<form action="/' . Lang::prefix() . 'enquiry/" method="post">
						
							<input type="hidden" name="' . $obFussyCat . '" value="enquiry">
							
							<div class="form-group row">
								<label for="enquiry_name">' . Lang::getLang('enquiryYourName') . '</label>
								<input type="text" class="form-control" id="enquiry_name" name="enquiry-name" placeholder="Name" required>
							</div>
							
							<div class="form-group row">
								<label for="enquiry_email">' . Lang::getLang('enquiryYourEmail') . '</label>
								<input type="email" class="form-control" id="enquiry_email" name="enquiry-email" placeholder="Email" required>
							</div>
							
							<div class="form-group row">
								<label for="enquiry_company_name">' . Lang::getLang('enquiryCompanyName') . '</label>
								<input type="text" class="form-control" id="enquiry_company_name" name="enquiry-company-name" placeholder="Company Name">
							</div>
							
							<div class="form-group row">
								<label for="enquiry_role">' . Lang::getLang('enquiryYourRole') . '</label>
								<input type="text" class="form-control" id="enquiry_role" name="enquiry-role" placeholder="CEO, Developer, Designer">
							</div>
							
							<div class="form-group row">
								<label for="enquiry_website">' . Lang::getLang('enquiryCurrentWebsite') . '</label>
								<input type="text" class="form-control" id="enquiry_website" name="enquiry-website" placeholder="http://example.com/">
							</div>
							
							<div class="form-group row">
								<div class="checkbox">
									<label for="enquiry_subscribe">
										<input id="enquiry_subscribe" name="enquiry-subscribe" type="checkbox" value="yes" checked>
										' . Lang::getLang('enquirySubscribeToNewsletter') . '
									</label>
								</div>
							</div>
							
							<div class="form-group row">
								<div class="checkbox">
									<label for="enquiry_acknowledge">
										<input id="enquiry_acknowledge" name="enquiry-acknowledge" type="checkbox" value="yes" checked>
										' . Lang::getLang('enquiryAcknowledgeCTA') . '
									</label>
								</div>
							</div>

							<div class="form-group row' . (!empty($this->errorArray['zaptcha'])?" has-error":"") . '">
										<label for="zaptcha">' . Lang::getLang('pleaseEnterZaptchaCode') . '<img src="/perihelion/zaptcha/zaptcha.php" style="margin-bottom:5px;"></label>
										<input type="text" name="zaptcha" class="form-control" id="zaptcha" placeholder="Enter Code" value="">
							';
							if (!empty($this->errorArray['zaptcha'])) {
								foreach($this->errorArray['zaptcha'] AS $error) { $h .= '<p class="bg-danger"><small>' . $error . '</small></p>'; }
							}
							$h .= '
							</div>
									
							<div id="hachimitsu" class="form-group row">
								<input type="text" name="message">
								<input type="url" name="url">
							</div>
							
							<div class="form-group row">
								<button id="enquiry_submit" name="enquiry-submit" type="submit" class="btn btn-lg btn-success">' . Lang::getLang('submit') . '</button>
							</div>
						
						</form>

					</div>
					
				</div>

			</div>

		</div>

		';
		
		return $h;

	}

	public function getMoreInfoThankYou() {

		$h = '

		<div id="getMoreInfoThankYou" class="container">
			<div class="row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jumbotron">
						<img src="/zeni/assets/images/perihelion-logo.png" />
						<h1>' . Lang::getLang('thankYouForYourInterest') . '</h1>
						<p>' . Lang::getLang('youCanExpectAnEmail') . '</p>
					</div>
				</div>
			</div>
		</div>

		';
		
		return $h;

	}

}

?>