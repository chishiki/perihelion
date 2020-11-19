<?php

class ViewUtilities {

	public static function switchLanguageLink() {
		$link = '<a href="' . Lang::switchLanguageURL() . '">' . ($_SESSION['lang']=='ja'?'English':'日本語') . '</a>';
		return $link;
	}

}

?>