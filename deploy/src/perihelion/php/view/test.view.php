<?php

final class PerihelionTestView {

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

	public static function testView() {

		$body = '<h1>YO</h1>';

		$m = new Memcached();
		$m->addServer('localhost', 11211);

		$langArray = Lang::words();

		$body .= '<ul>';
		foreach ($langArray AS $langKey) {
			$cachedLanguageKeyEnglish = 'lang_' . $_SESSION['siteID'] . '_' . $langKey . '_en';
			$cachedLanguageKeyJapanese = 'lang_' . $_SESSION['siteID'] . '_' . $langKey . '_ja';
			$body .= '<li>' . $langKey;
				$body .= '<ul>';
					$body .= '<li>' . $cachedLanguageKeyEnglish . ' => ' . ($m->get($cachedLanguageKeyEnglish)?$m->get($cachedLanguageKeyEnglish):'[CACHE EMPTY]') . '</li>';
					$body .= '<li>' . $cachedLanguageKeyJapanese . ' => ' . ($m->get($cachedLanguageKeyJapanese)?$m->get($cachedLanguageKeyJapanese):'[CACHE EMPTY]') . '</li>';
				$body .= '</ul>';
			$body .= '</li>';
		}
		$body .= '</ul>';

		$card = new CardView('test_view', array('container-fluid'), '', array('col-12'), 'TEST', $body, false);
		return $card->card();


	}

}

?>