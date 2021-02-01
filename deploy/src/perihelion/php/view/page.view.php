<?php

class PageView {
	
	private $urlArray;
	private $inputArray;
	private $moduleArray;
	private $errorArray;
	private $messageArray;
	
	public function __construct($urlArray, $inputArray, $moduleArray, $errorArray, $messageArray) {

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->moduleArray = $moduleArray;
		$this->errorArray = $errorArray;
		$this->messageArray = $messageArray;
		
	}
	
	public function page($html) {
		
		if (empty($this->urlArray[0])) { $html_class = 'index'; } else { $html_class = $this->urlArray[0]; }
		
		$h = '<!DOCTYPE html>';
		$h .= '<html lang="' . ($_SESSION['lang']=='ja'?'ja':'en') . '" class="' . $html_class . '">';
		$h .= $this->head();
		$h .= $this->body($html);
		$h .= '</html>';
		return $h;
		
	}
	
	private function head() {
		
		$meta = new Meta($this->urlArray);
		$pageTitle = $meta->pageTitle;
		$pageDescription = $meta->pageDescription;
		$pageKeywords = $meta->pageKeywords;
		
		$site = new Site($_SESSION['siteID']);
		
		$robots = new Robots($this->urlArray);
		
		$thisUrlArray = array();
		foreach ($this->urlArray AS $urlArrayElement) { if ($urlArrayElement) { $thisUrlArray[] = $urlArrayElement; } }
		$systemURL = join('/',$thisUrlArray);
		$seoID = SEO::getSeoID($systemURL);

		$h = '<head>';
	
		$h .= '<title>' . $pageTitle . '</title>';
		
		$h .= '<meta charset="utf-8">';
		$h .= '<meta http-equiv="content-language" content="' . ($_SESSION['lang']=='ja'?'ja':'en') . '">';
		$h .= '<meta name="robots" content="' . $robots->meta() . '">'; // allow sites to set
		$h .= '<meta name="description" content="' . $pageDescription . '">';
		$h .= '<meta name="keywords" content="' . $pageKeywords . '">';
		$h .= '<meta name="author" content="' . Config::read('copyright.holder') . '">';
		$h .= '<meta name="generator" content="Perihelion">';
		$h .= '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">';
		$h .= '<meta name="apple-mobile-web-app-capable" content="yes">';
		$h .= '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">';

		$h .= '<link rel="icon" type="image/x-icon" href="/perihelion/assets/images/favicons/favicon-' . $_SESSION['siteID'] . '.ico"/>';

		if ($seoID) {
			$seo = new SEO($seoID);
			$h .= '<link rel="canonical" href="http://' . Site::url() . '/' . Lang::languageUrlPrefix() . $seo->seoURL . '/">';
		}

		$h .= '<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">';
		$h .= '<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">';
		$h .= '<link rel="stylesheet" type="text/css" href="/perihelion/assets/css/perihelion.css" />';
		$h .= '<link rel="stylesheet" type="text/css" href="/theme.css" />';

		foreach ($this->moduleArray AS $moduleName) {

			$cssPhysicalPath = Config::read('web.root') . 'satellites/' . $moduleName . '/assets/css/*.css';
			foreach (glob($cssPhysicalPath) AS $style) {

				$cssPath = '';
				$numberOfSlashes = substr_count($style, '/');
				$cssPathPieces = explode('/', $style);
				foreach ($cssPathPieces AS $k => $v) {
					if ($k > ($numberOfSlashes - 5)) { $cssPath .= '/' . $v; }
				}
				$h .= '<link rel="stylesheet" type="text/css" href="' . $cssPath . '" />';

			}

		}
		
		$h .= '<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>';
		$h .= '<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>';
		$h .= '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>';
		$h .= '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>';
		$h .= '<script type="text/javascript" src="https://kit.fontawesome.com/' . Config::read('fa.kit') . '.js"></script>';
		$h .= '<script type="text/javascript" src="/perihelion/vendor/ckeditor/ckeditor.js"></script>';
		$h .= '<script type="text/javascript" src="/perihelion/vendor/masonry/dist/masonry.pkgd.min.js"></script>';
		if ($site->siteUsesGoogleMaps) {
			$h .= '<script async defer src="https://maps.googleapis.com/maps/api/js?key=' . $site->siteGoogleApiKey . '&callback=initMap" type="text/javascript"></script>';
		}
		$h .= '<script type="text/javascript" src="/perihelion/assets/js/perihelion.js"></script>';
		
		foreach ($this->moduleArray AS $moduleName) {

			$jsPhysicalPath = Config::read('web.root') . 'satellites/' . $moduleName . '/assets/js/*.js';
			foreach (glob($jsPhysicalPath) AS $script) {

				$jsPath = '';
				$numberOfSlashes = substr_count($script, '/');
				$jsPathPieces = explode('/', $script);
				foreach ($jsPathPieces AS $k => $v) {
					if ($k > ($numberOfSlashes - 5)) { $jsPath .= '/' . $v; }
				}
				$h .= '<script type="text/javascript" src="' . $jsPath . '"></script>';

			}
			
		}

		$h .= '</head>';

		return $h;
			
	}
	
	private function body($html) {
		
		$integratedErrorMessages = array('contact','contact-us','enquiry','get-in-touch','designer','manager','admin','profile');
		$scriptFilter = array('designer','manager','admin','support');
		
		if (empty($this->urlArray[0])) { $body_class = 'index'; } else { $body_class = $this->urlArray[0]; }
		$h = '<body class="' . $body_class . ' lang-' . $_SESSION['lang'] . '">';
		if (!in_array($this->urlArray[0],$scriptFilter)) { $h .= $this->scripts('header'); }
		$h .= $this->header();
		$h .= $this->messages();
		if (!in_array($this->urlArray[0],$integratedErrorMessages)) { $h .= $this->errors(); }
		$h .= $html;
		$h .= $this->footer();

		if (!in_array($this->urlArray[0],$scriptFilter)) { $h .= $this->scripts('footer'); }
		$h .= '</body>';
		
		return $h;
		
	}
	
	private function header() {

		foreach ($this->moduleArray as $moduleName) {
			$class = ucfirst($moduleName) . 'NavbarView';
			if (class_exists($class)) {
				$navbar = new $class($this->urlArray, $this->inputArray, $this->inputArray);
				return $navbar->navbar();
			}
		}
		if (!isset($navbar)) {
			$site = new Site($_SESSION['siteID']);
			$navbar = new NavBarView($this->urlArray, $this->inputArray, $this->moduleArray, $site);
			$navbarClasses = Config::read('navbar.classes');
			return $navbar->navbarView($navbarClasses);
		}

	}
	
	private function footer() {

		foreach ($this->moduleArray as $moduleName) {
			$class = ucfirst($moduleName) . 'FooterView';
			if (class_exists($class)) {
				$f = new $class($this->urlArray, $this->inputArray, $this->inputArray);
				return $f->footer();
			}
		}
		if (!isset($f)) {
			$f = new FooterView();
			return $f->footer();
		}

	}

	private function scripts($scriptPosition) {
	
		$h = '';
		
		$site = new Site($_SESSION['siteID']);

		$scripts = Script::scriptArray(1, $scriptPosition);

		$h .= '<!-- START DESIGNER ' . strtoupper($scriptPosition) . ' SCRIPTS -->';
			foreach ($scripts AS $scriptID) {
				$script = new Script($scriptID);
				$h .= $script->scriptCode . '';
			}
		$h .= '<!-- END DESIGNER FOOTER SCRIPTS -->';

		return $h;
	
	}
	
	private function errors() {
		
		$h = '';
		if (!empty($this->errorArray)) {
			$h .= '<div class="container-fluid">';
    			foreach ($this->errorArray AS $errorGroup) {
    				$h .= '<div class="row">';
    				    $h .= '<div class="col-12">';
    					   foreach ($errorGroup AS $error) { $h .= '<div class="alert alert-danger" role="alert">' . $error . '</div>'; }
    					$h .= '</div>';
    				$h .= '</div>';
    			}
			$h .= '</div>';
		}
		return $h;

	}
	
	private function messages() {
		
		$h = '';
		if (!empty($this->messageArray)) {
			$h .= '<div class="container-fluid">';
				$h .= '<div class="row">';
					$h .= '<div class="col-12">';
						$h .= '<div class="alert alert-info" role="alert">';
							$h .= '<span class="float-right">[' . date("Y-m-d H:i:s") . ']</span>';
							foreach ($this->messageArray AS $message) { $h .= $message . '<br />'; }
						$h .= '</div>';
					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';
		}
		return $h;

	}	
	
}

?>