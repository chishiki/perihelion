<?php

$awsAutoloaderPath = $_SERVER['DOCUMENT_ROOT'] . '/perihelion/vendor/aws/aws-autoloader.php';
if (file_exists($awsAutoloaderPath)) { require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/vendor/aws/aws-autoloader.php'); }
// required to use aws-sdk-php
// https://aws.amazon.com/sdk-for-php/
// https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/welcome.html
// https://github.com/aws/aws-sdk-php
	
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/interface/state.controller.interface.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/interface/view.controller.interface.php');

require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/_perihelion.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/_state.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/_view.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/_api.controller.php');

require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/address.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/admin.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/admin.view.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/auth.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/auth.view.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/contact.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/contact.view.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/content.view.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/csv.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/designer.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/designer.view.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/enquiry.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/enquiry.view.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/index.view.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/manager.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/manager.view.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/newsletter.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/newsletter.view.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/notFound.view.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/portal.view.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/profile.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/profile.view.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/support.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/support.view.controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/controller/test.view.controller.php');

require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/nucleus.model.php'); // required by ORM
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/orm.model.php'); // extended by other model classes

require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/accountRecovery.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/address.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/audit.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/auth.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/aws.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/blacklistDomain.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/blacklistIP.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/blacklistWord.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/carousel.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/carouselPanel.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/config.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/codeGenerator.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/contact.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/contactForm.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/content.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/contentCategory.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/contract.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/contractSignature.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/cookie.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/currency.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/file.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/geoArea.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/group.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/image.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/lang.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/languageException.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/link.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/mail.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/menu.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/menuItem.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/meta.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/navbar.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/newsletterSubscription.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/note.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/page.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/phpinfo.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/print.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/project.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/robots.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/router.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/rss.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/seo.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/session.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/script.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/site.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/state.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/theme.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/tile.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/user.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/userRole.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/utilities.model.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/model/video.model.php');

require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/address.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/admin.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/audit.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/auth.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/breadcrumbs.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/calendar.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/carousel.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/card.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/codeGenerator.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/contact.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/content.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/enquiry.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/file.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/footer.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/form.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/geoArea.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/group.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/image.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/link.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/menu.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/menuItem.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/navbar.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/newsletter.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/note.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/page.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/pagination.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/panel.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/print.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/profile.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/seo.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/script.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/site.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/sitemap.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/support.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/theme.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/test.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/tile.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/uptime.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/user.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/utilities.view.php');
require($_SERVER['DOCUMENT_ROOT'] . '/perihelion/php/view/video.view.php');

require(substr($_SERVER['DOCUMENT_ROOT'],0,strrpos($_SERVER['DOCUMENT_ROOT'],'/'))."/vault/config/instance.config.php");

?>