<?php

class Router {
	
	private $urlArray;

	public function __construct($uri) {

		$seoID = SEO::getSeoID(trim($uri,'/'));
		if ($seoID) { $seo = new SEO($seoID); $uri = "/" . $seo->systemURL . "/"; }
		$urlArray = explode('/', substr(rtrim($uri, '/') . '/', 1, -1));
		if ($urlArray[0] == 'ja') { Lang::setLanguage('ja'); array_shift($urlArray); } else { Lang::setLanguage('en'); }
		for ($i = 0; $i <= 5; $i++) { if (!isset($urlArray[$i])) { $urlArray[$i] = ''; } }
		$this->urlArray = $urlArray;
	
	}

	public function getUrlArray() {
	
		return $this->urlArray;
		
	}
	
}

?>