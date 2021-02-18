<?php

	/* =====================
	
	configure and copy to <physical.path>/vault/config/instance.config.php
	
	===================== */

	// System Info
	Config::write('physical.path','/var/www/xxxxxxx/example.com/');
	Config::write('web.root','/var/www/xxxxxxx/example.com/src/');
	Config::write('copyright.holder', 'Christopher Webb');
	Config::write('copyright.url', 'https://perihelion.xyz/');
	Config::write('copyright.start', '2020');
	Config::write('javascript.required', true);

	// Environment
    Config::write('environment', 'dev'); // dev|prod
    Config::write('default.site', 1); // siteID
    
	// MySQL
	Config::write('db.host', 'xxxxxxx');
	Config::write('db.basename', 'xxxxxxx');
	Config::write('db.user', 'xxxxxxx');
	Config::write('db.password', 'xxxxxxx');
	
	// Admin
	Config::write('admin.email', 'support@perihelion.xyz');
	$admins = array(1); // array of userIDs
	Config::write('admin.userIdArray', $admins);

	// Support
	Config::write('support.name', 'Perihelion Support');
	Config::write('support.email', 'support@perihelion.xyz');
	Config::write('support.replyto.email', 'noreply@perihelion.xyz');

	// Cron
	Config::write('cron.key', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

	// AWS
	Config::write('aws.region', 'xxxxxxx');
	Config::write('aws.key', 'xxxxxxx');
	Config::write('aws.secret', 'xxxxxxx');
	
	// FontAwesome
	Config::write('fa.kit', 'xxxxxxx');

	// Twilio
	Config::write('account.sid', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
	Config::write('auth.token', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
	Config::write('phone.number.sid', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

	// Design Classes
	$navbarClasses = array('navbar-expand-xl','navbar-light','bg-light');
	Config::write('navbar.classes', $navbarClasses);

	// Boilerplate Content
	Config::write('terms.content.id', 3);
	Config::write('privacy.content.id', 4);

	// Manager Menu
	$managerMenuRoles = array('siteAdmin');
	Config::write('manager.menu.access', $managerMenuRoles);

	/* ===================== */
	
?>