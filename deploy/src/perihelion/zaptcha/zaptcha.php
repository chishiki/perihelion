<?php

	require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/photon.php');
	$sun = new Controller();
	
	$site = new Site($_SESSION['siteID']);
	$theme = new Theme($site->themeID);
	
	$textColor = $theme->body_color;
	$textColorRed = hexdec(substr($textColor, 1, 2));
	$textColorGreen = hexdec(substr($textColor, 3, 2));
	$textColorBlue = hexdec(substr($textColor, 5, 2));

	$backgroundColor = $theme->body_backgroundcolor;
	$backgroundColorRed = hexdec(substr($backgroundColor, 1, 2));
	$backgroundColorGreen = hexdec(substr($backgroundColor, 3, 2));
	$backgroundColorBlue = hexdec(substr($backgroundColor, 5, 2));
	
	$image = imagecreate(100,30);
	$setBackgroundColor = imagecolorallocate($image, $backgroundColorRed, $backgroundColorGreen, $backgroundColorBlue);
	$setTextColor = imagecolorallocate($image, $textColorRed, $textColorGreen, $textColorBlue);
	
	$zaptcha =  substr(md5(uniqid(rand())), 0, 6);
	$_SESSION['zaptcha'] = $zaptcha;

	$font = $_SERVER['DOCUMENT_ROOT'] . '/perihelion/assets/fonts/Quando-Regular.ttf';
	imagettftext($image, 14, 0, 17, 22, $setTextColor, $font, $zaptcha);
	header("content-type: image/png");
	imagepng($image);
	imagedestroy($image);

?>