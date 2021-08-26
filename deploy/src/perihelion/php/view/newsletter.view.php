<?php

class NewsletterView {
	
	private $urlArray;
	private $inputArray;
	private $errorArray;
	private $messageArray;
	
	public function __construct($urlArray, $inputArray, $errorArray, $messageArray) {
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		$this->messageArray = $messageArray;
	}

	public function thankYouForSubscribing() {
		
		$h = "<div id=\"perihelionThanksForSubscribing\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";

							$h .= "<div class=\"card\" >";

								$h .= "<div class=\"card-header\">";
									$h .= "<div class=\"card-title\">" . Lang::getLang('thankYouForSubscribing') . "</div>";
								$h .= "</div>";

							$h .= "</div>";

					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";

		return $h;

	}

	public function subscriberList() {
		
		$subscribers = NewsletterSubscription::subscriberArray();

		$h = "<div id=\"perihelionNewsletter\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
							$h .= "<div class=\"card-header\">";
								$h .= "<div class=\"card-title\"><h3>";
									$h .= Lang::getLang('subscribers');
								$h .= "</h3></div>";
							$h .= "</div>";
							$h .= "<div class=\"card-body\">";
								$h .= "<div class=\"table-responsive\">";
								
									$h .= "<table class=\"table table-bordered table-striped table-hover\">";
										$h .= "<tr>";
											$h .= "<th>" . Lang::getLang('name') . "</th>";
											$h .= "<th>" . Lang::getLang('email') . "</th>";
											$h .= "<th class=\"text-center\">" . Lang::getLang('dateTime') . "</th>";
											$h .= "<th class=\"text-center\">" . Lang::getLang('IP') . "</th>";
										$h .= "</tr>";
										foreach($subscribers AS $subscriberEmail) {
											$sub = new NewsletterSubscription(0,$subscriberEmail);
											$h .= "<tr>";
												$h .= "<td>" . $sub->subscriberName . "</td>";
												$h .= "<td><a href=\"mailto:" . $sub->subscriberEmail . "\">" . $sub->subscriberEmail . "</a></td>";
												$h .= "<td class=\"text-center\">" . $sub->subscribedDateTime . "</td>";
												$h .= "<td class=\"text-center\">";
													$h .= "<a href=\"https://whatismyipaddress.com/ip/" . $sub->subscribedFromIP . "\" target=\"_blank\">";
													$h .= "<span class=\"fas fa-globe\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . $sub->subscribedFromIP . "\" data-original-title=\"" . $sub->subscribedFromIP . "\"></span>";
													$h .= "</a>";
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

}

?>