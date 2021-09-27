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

		$h .= '<link rel="stylesheet" type="text/css" href="/perihelion/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">';
		$h .= '<link rel="stylesheet" type="text/css" href="/perihelion/vendor/components/jqueryui/themes/base/jquery-ui.min.css">';
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

		$h .= '<script type="text/javascript" src="https://kit.fontawesome.com/' . Config::read('fa.kit') . '.js"></script>';
		$h .= '<script type="text/javascript" src="/perihelion/vendor/ckeditor/ckeditor/ckeditor.js"></script>';
		$h .= '<script type="text/javascript" src="/perihelion/vendor/components/jquery/jquery.min.js"></script>';
		$h .= '<script type="text/javascript" src="/perihelion/vendor/components/jqueryui/jquery-ui.min.js"></script>';
		$h .= '<script type="text/javascript" src="/perihelion/vendor/furf/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>';
		if (Config::read('vue.js') === true) {
			if (Config::read('environment') == 'prod') {
				$h .= '<script type="text/javascript" src="/perihelion/vendor/vuejs/vue/dist/vue.min.js"></script>';
			}
			if (Config::read('environment') == 'dev') {
				$h .= '<script type="text/javascript" src="/perihelion/vendor/vuejs/vue/dist/vue.js"></script>';
			}
		}
		$h .= '<script type="text/javascript" src="/perihelion/vendor/desandro/masonry/dist/masonry.pkgd.min.js"></script>';
		$h .= '<script type="text/javascript" src="/perihelion/vendor/popperjs/popper-core/popper.min.js"></script>';
		$h .= '<script type="text/javascript" src="/perihelion/vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>';
		$h .= '<script type="text/javascript" src="/perihelion/assets/js/perihelion.js"></script>';

		if ($site->siteUsesGoogleMaps) {
			$h .= '<script src="https://maps.googleapis.com/maps/api/js?key=' . $site->siteGoogleApiKey . '&libraries=places" type="text/javascript"></script>';
		}

		if ($site->siteUsesLocationPicker) {
			$h .= '<script src="/perihelion/vendor/logicify/jquery-locationpicker-plugin/dist/locationpicker.jquery.min.js"></script>';
		}

		if ($site->siteUsesDataTables) {
			$h .= '<script type="text/javascript" src="/perihelion/vendor/datatables/datatables/datatables.min.js"></script>';
		}

		foreach ($this->moduleArray AS $moduleName) {

			$versionDateTime = new DateTime();

			$jsPhysicalPath = Config::read('web.root') . 'satellites/' . $moduleName . '/assets/js/*.js';
			foreach (glob($jsPhysicalPath) AS $script) {

				$jsPath = '';
				$numberOfSlashes = substr_count($script, '/');
				$jsPathPieces = explode('/', $script);
				foreach ($jsPathPieces AS $k => $v) {
					if ($k > ($numberOfSlashes - 5)) { $jsPath .= '/' . $v; }
				}
				$h .= '<script type="text/javascript" src="' . $jsPath . '?version=' . $versionDateTime->format('Ymd') . '"></script>';

			}
			
		}

		if (Config::read('javascript.required') == true) {
			$h .= '
			<noscript>
				<style>
					div { display:none !important; }
					h1.enable-javascript { text-align:center; }
				</style>
			</noscript>
			';
		}

		$h .= '</head>';

		return $h;
			
	}
	
	private function body($html) {
		
		$integratedErrorMessages = array('contact','profile');
		$scriptFilter = array('designer','manager','admin','support');

		$bodyClasses = array();
		if (empty($this->urlArray[0])) { $bodyClasses[] = 'index'; } else { $bodyClasses[] = $this->urlArray[0]; }
		$bodyClasses[] = 'site-' . $_SESSION['siteID'];
		$bodyClasses[] = 'lang-' . $_SESSION['lang'];
		$bodyClasses[] = Config::read('environment');

		$h = '<body class="' . implode(' ',$bodyClasses) . '">';
			$h .= '<div id="perihelion_body">';
				if (!in_array($this->urlArray[0],$scriptFilter)) { $h .= $this->scripts('header'); }
				$h .= $this->header();
				$h .= $this->messages();
				if (!in_array($this->urlArray[0],$integratedErrorMessages)) { $h .= $this->errors(); }
				$h .= '<div id="perihelion_main">' . $html . '</div>';
				$h .= $this->footer();
			$h .= '</div>';
			if (!in_array($this->urlArray[0],$scriptFilter)) { $h .= $this->scripts('footer'); }
			if (Config::read('javascript.required') == true) {
				$h .= '<noscript><h1 class="enable-javascript">' . Lang::getLang('youMustEnableJavaScript') . '</h1></noscript>';
			}
		$h .= '</body>';

		return $h;
		
	}
	
	private function header() {

		foreach ($this->moduleArray as $moduleName) {
			$navbarViewClass = ModuleUtilities::moduleToClassName($moduleName, 'NavbarView');
			if (class_exists($navbarViewClass)) {
				$navbar = new $navbarViewClass($this->urlArray, $this->inputArray, $this->moduleArray, $this->errorArray);
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

		$footer = '';

		if (Config::read('environment') == 'dev' && Auth::isLoggedIn()) {

			$devDataArray = array(
				'$_SESSION' => $_SESSION,
				'$loc' => $this->urlArray,
				'$input' => $this->inputArray,
				'$modules' => $this->moduleArray,
				'$errors' => $this->errorArray,
				'$messages' => $this->messageArray
			);

			$devData = '';
			foreach ($devDataArray AS $key => $value) {
				$devData .= '<div class="card mb-3"><div class="card-header">' . $key . '</div><div class="card-body"><pre>' . print_r($value, true) . '</pre></div></div>';
			}

			$card = new CardView('dev_session_data',array('container-fluid my-3'),null,array('col-12'),Lang::getLang('devData'),$devData,true);
			$footer .= $card->card();
		}
		
		foreach ($this->moduleArray as $moduleName) {
			$footerViewClass = ModuleUtilities::moduleToClassName($moduleName, 'FooterView');
			if (class_exists($footerViewClass)) {
				$f = new $footerViewClass($this->urlArray, $this->inputArray, $this->inputArray);
				$footer .= $f->footer();
			}
		}
		if (!isset($f)) {
			$f = new FooterView();
			$footer .= $f->footer();
		}
		
		return $footer;

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
			$h .= '<div class="perihelion-errors container-fluid">';
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
			$h .= '<div class="perihelion-messages container-fluid">';
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