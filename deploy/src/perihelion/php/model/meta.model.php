<?php

class Meta {

	private $urlArray;
	
	public $pageTitle;
	public $pageDescription;
	public $pageKeywords;
	
	public function __construct($urlArray = array()) {

		$this->urlArray = $urlArray;
		
		$site = new Site($_SESSION['siteID']);
		$siteTitle = $site->getTitle();
		
		// default values
		$this->pageTitle = $site->getTitle();
		$this->pageDescription = $site->getDescription();
		$this->pageKeywords = $site->getKeywords();
		
		if (empty($this->pageDescription)) { $this->pageDescription = $this->pageTitle; }
		if (empty($this->pageKeywords)) { $this->pageKeywords = $this->pageTitle; }

		if (empty($urlArray) || $urlArray[0] == '') { $pageName = 'index'; } else { $pageName = $urlArray[0]; }

			
			if ($pageName == 'index') {
			
				$this->pageTitle = $siteTitle;
				
			} elseif ($pageName == 'login') {
				
				$this->pageTitle = Lang::getLang('login') . ' - ' . $siteTitle;

			} elseif ($pageName == 'designer') {

				switch ($urlArray[1]) {
					case('site'):
						$this->pageTitle = Lang::getLang('site');
						break;
					case('content'):
						$this->pageTitle = Lang::getLang('content');
						break;
					case('themes'):
						$this->pageTitle = Lang::getLang('themes');
						break;
					case('images'):
						$this->pageTitle = Lang::getLang('images');
						break;
					case('carousels'):
						$this->pageTitle = Lang::getLang('carousels');
						break;
					case('menus'):
						$this->pageTitle = Lang::getLang('menus');
						break;
					case('seo'):
						$this->pageTitle = Lang::getLang('seo');
						break;
					case('files'):
						$this->pageTitle = Lang::getLang('files');
						break;
					case('tiles'):
						$this->pageTitle = Lang::getLang('tiles');
						break;
					default:
						$this->pageTitle = Lang::getLang('siteDesigner');
				}
				
			} elseif ($pageName == 'manager') {

				switch ($urlArray[1]) {
					case('settings'):
						$this->pageTitle = Lang::getLang('settings');
						break;
					case('users'):
						$this->pageTitle = Lang::getLang('users');
						break;
					case('billing'):
						$this->pageTitle = Lang::getLang('billing');
						break;
					default:
						$this->pageTitle = Lang::getLang('siteManager');
				}
				
			} elseif ($pageName == 'admin') {			
						
				switch ($urlArray[1]) {
					case('audit'):
						$this->pageTitle = Lang::getLang('auditTrail');
						break;
					case('uptime'):
						$this->pageTitle = Lang::getLang('uptime');
						break;
					case('not-found'):
						$this->pageTitle = Lang::getLang('notFound');
						break;
					case('server'):
						$this->pageTitle = Lang::getLang('server');
						break;
					case('language'):
						$this->pageTitle = Lang::getLang('language');
						break;
					case('geography'):
						$this->pageTitle = Lang::getLang('geography');
						break;
					case('currency'):
						$this->pageTitle = Lang::getLang('currency');
						break;
					case('blacklist'):
						$this->pageTitle = Lang::getLang('blacklist');
						break;
					default:
						$this->pageTitle = Lang::getLang('siteAdmin');
				}
			
			} elseif ($pageName == 'contact' || $pageName == 'contact-us' || $pageName == 'get-in-touch') {
				$this->pageTitle = Lang::getLang('contact') . ' - ' . $siteTitle;
			} elseif ($pageName == 'test') {
				$this->pageTitle = Lang::getLang('test') . ' - ' . $siteTitle;
			} else {
			
				// IF CONTENT EXISTS
				$contentID = Content::publishedContentID($pageName);
				if ($contentID) {
					$content = new Content($contentID);
					$this->pageTitle = $content->title();
					$contentDescription = $content->description();
					$this->pageDescription = trim(preg_replace('/\s\s+/', ' ', Utilities::agileTruncate(strip_tags($contentDescription), 128)));
				} else {

					$loc = array_filter($this->urlArray);
					$count = count($loc);

					$pageTitle = array();
					for ($i = 1; $i < $count; $i++) {
						$pageTitle[] = Lang::getLang($loc[$i]);
						$this->pageDescription .= ', ' . Lang::getLang($loc[$i]);
						if (!ctype_digit($loc[$i])) { $this->pageKeywords .= ', ' . Lang::getLang($loc[$i]); }
					}
					$pageTitle[] = $this->pageTitle;
					$this->pageTitle = join(' | ',$pageTitle);

				}
				
			}

			// SEO SUPERCEDES PAGE AND DEFAULT

			$thisUrlArray = array();
			foreach ($urlArray AS $urlArrayElement) {
				if (isset($urlArrayElement) && $urlArrayElement != '') {
					$thisUrlArray[] = $urlArrayElement;
				}
			}
			$systemURL = join('/',$thisUrlArray);
			$seoID = SEO::getSeoID($systemURL);
			if ($seoID) {
				$seo = new SEO($seoID);
				$seoPageTitle = $seo->title();
				$seoPageDescription = $seo->description();
				$seoPageKeywords = $seo->keywords();
				if ($seoPageTitle) { $this->pageTitle = $seoPageTitle; }
				if ($seoPageDescription) { $this->pageDescription = $seoPageDescription; }
				if ($seoPageKeywords) { $this->pageKeywords = $seoPageKeywords; }
			}

	}
	
}

?>