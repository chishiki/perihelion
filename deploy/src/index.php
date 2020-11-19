<?php

	require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/photon.php');
	$sun = new Controller();
	$sun->orbit();
	
?>