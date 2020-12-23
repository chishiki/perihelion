<?php

final class FooterView {

	private $loc;
	private $input;
	private $modules;
	private $errors;
	private $messages;

	public function __construct($loc = array(), $input = array(), $modules = array(), $errors = array(), $messages = array()) {

		$this->loc = $loc;
		$this->input = $input;
		$this->modules = $modules;
		$this->errors = $errors;
		$this->messages = $messages;

	}

	public function footer() {

		$site = new Site($_SESSION['siteID']);

		$copyrightHolder = Config::read('copyright.holder');
		$copyrightURL = Config::read('copyright.url');
		$copyrightStartYear = Config::read('copyright.start');

		$currentYear = date('Y');
		$copyrightYears = $copyrightStartYear;
		if ($currentYear > $copyrightStartYear) { $copyrightYears = $copyrightStartYear . '~' . $currentYear; }

		if (Auth::isLoggedIn()) { $action = 'logout'; } else { $action = 'login'; }

		$items = array();
		$items[] = '&copy; ' . $copyrightYears . ' <a href="' . $copyrightURL . '">' . Lang::getLang($copyrightHolder) . '</a>';
		$items[] = '<a href="/' . Lang::prefix() . 'tos/">' . Lang::getLang('tos') . '</a>';
		$items[] = '<a href="/' . Lang::prefix() . 'privacy/">' . Lang::getLang('privacy') . '</a>';
		$items[] = '<a href="/' . Lang::prefix() . $action . '/">' . Lang::getLang($action) . '</a>';
		if ($site->siteLangJapanese || $_SESSION['lang']!='en') { $items[] = ViewUtilities::switchLanguageLink(); }

		$footer = '
			<div id="footer_container" class="perihelion-footer">
				<div class="container-fluid">
					<footer>
						<p>' . implode(' | ', $items) . '</p>
					</footer>
				</div>
			</div>
		';

		return $footer;

	}

}

?>