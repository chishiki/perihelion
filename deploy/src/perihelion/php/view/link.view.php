<?php

class LinkView {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	private $messageArray;
	
	public function __construct($urlArray, $inputArray,  $errorArray,  $messageArray) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		$this->messageArray = $messageArray;
		
	}

	public static function freshPerihelionLinkTable($baseURL, $objectType, $objectID) {

		$links = Link::getObjectLinkArray($objectType,$objectID);
								
		if (!empty($links)) {
			
			$h = "<div id=\"perihelionLinkList\" class=\"row\">";
				$h .= "<div class=\"col-12\">";
					$h .= "<div class=\"table-responsive\">";
						$h .= "<table class=\"table table-striped\">";

							$h .= "<tr>";
								$h .= "<th>" . Lang::getLang('link') . "</th>";
								$h .= "<th class=\"text-center\">" . Lang::getLang('linkClickCount') . "</th>";
								$h .= "<th class=\"text-center\">" . Lang::getLang('linkPublished') . "</th>";
								$h .= "<th class=\"text-center\">" . Lang::getLang('order') . "</th>";
								$h .= "<th class=\"text-center\">" . Lang::getLang('delete') . "</th>";
							$h .= "</tr>";
						
							foreach ($links as $linkID) {
								
								$link = new Link($linkID);
								$h .= "<tr>";
									$h .= "<td><a href=\"" . $link->url() . "\">" . $link->anchor() . "</a></td>";
									$h .= "<td class=\"text-center\">" . $link->linkClickCount . "</td>";
									$h .= "<td class=\"text-center\" style=\"vertical-align:middle;\">";
										if ($link->linkPublished) {
											$h .= "<a class=\"btn btn-secondary btn-sm\" href=\"" . $baseURL . "unpublish/" . $linkID . "/\"><span class=\"fas fa-check\"></span></a>";
										} else {
											$h .= "<a class=\"btn btn-secondary btn-sm\" href=\"" . $baseURL . "publish/" . $linkID . "/\"><span class=\"fas fa-check\" style=\"color:#fff;\"></span></a>";
										}
									$h .= "</td>";
									$h .= "<td class=\"text-center\" style=\"vertical-align:middle;\">";
										// $h .= self::linkDisplayOrderDropdown($objectID, $linkID, $link->linkDisplayOrder);
									$h .= "</td>";
									$h .= "<td class=\"text-center\" style=\"vertical-align:middle;\">";
										$h .= "<a class=\"btn btn-danger btn-sm\" href=\"" . $baseURL . "delete/" . $linkID . "/\"><span class=\"fas fa-trash-alt\" style=\"color:#fff;\"></span></a>";
									$h .= "</td>";
								$h .= "</tr>";
								
							}
						
						$h .= "</table>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
			$h .= "<hr />";
			
			return $h;
			
		}
		
	}

	public static function freshPerihelionLinkForm($formAction, $objectKeyField, $objectID) {

		$h = "<form id=\"perihelionLinkForm\" method=\"post\" action=\"" . $formAction . "\">";

			$h .= "<input type=\"hidden\" name=\"" . $objectKeyField . "\" value=\"" . $objectID . "\">";

			$h .= "<div class=\"form-group row\">";
				
				$h .= "<label class=\"col-12 col-sm-4 col-md-3\" for=\"linkUrlEnglish\">" . Lang::getLang('url') . " (" . Lang::getLang('english') . ")</label> ";
				$h .= "<div class=\"col-12 col-sm-8 col-md-3\"><input type=\"url\" id=\"linkUrlEnglish\" name=\"linkUrlEnglish\" class=\"form-control\" name=\"linkUrlEnglish\" required></div>";

				$h .= "<label class=\"col-12 col-sm-4 col-md-3\" for=\"linkAnchorTextEnglish\">" . Lang::getLang('anchorText') . " (" . Lang::getLang('english') . ")</label> ";
				$h .= "<div class=\"col-12 col-sm-8 col-md-3\"><input type=\"text\" id=\"linkAnchorTextEnglish\" name=\"linkAnchorTextEnglish\" class=\"form-control\" name=\"linkAnchorTextEnglish\" required></div>";
				
			$h .= "</div> ";
			
			$h .= "<div class=\"form-group row\">";
			
				$h .= "<label class=\"col-12 col-sm-4 col-md-3\" for=\"linkUrlJapanese\">" . Lang::getLang('url') . " (" . Lang::getLang('japanese') . ")</label> ";
				$h .= "<div class=\"col-12 col-sm-8 col-md-3\"><input type=\"url\" id=\"linkUrlJapanese\" name=\"linkUrlJapanese\" class=\"form-control\" name=\"linkUrlJapanese\"></div>";

				$h .= "<label class=\"col-12 col-sm-4 col-md-3\" for=\"linkAnchorTextJapanese\">" . Lang::getLang('anchorText') . " (" . Lang::getLang('japanese') . ")</label> ";
				$h .= "<div class=\"col-12 col-sm-8 col-md-3\"><input type=\"text\" id=\"linkAnchorTextJapanese\" name=\"linkAnchorTextJapanese\" class=\"form-control\" name=\"linkAnchorTextJapanese\"></div>";
				
			$h .= "</div> ";
			
			$h .= "<button type=\"submit\" name=\"freshPerihelionLinkFormSubmit\" class=\"btn btn-primary float-right\"><span class=\"fas fa-plus\" style=\"color:#fff;\"></span> " . Lang::getLang('addLink') . "</button>";

		$h .= "</form>";
		
		return $h;
								
	}
	
}

?>