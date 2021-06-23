<?php

class ViewController {
	
	private $urlArray;
	private $inputArray;
	private $moduleArray;
	private $errorArray;
	private $messageArray;
	
	public function __construct($urlArray, $inputArray, $moduleArray, $errorArray, $messageArray) {
		$this->urlArray = SEO::seoThisUrlArray($urlArray);
		$this->inputArray = $inputArray;
		$this->moduleArray = $moduleArray;
		$this->errorArray = $errorArray;
		$this->messageArray = $messageArray;
	}
		
	public function getView() {
		
		$loc = $this->urlArray;
		$input = $this->inputArray;
		$mods = $this->moduleArray;
		$errors = $this->errorArray;
		$msg = $this->messageArray;
		
		$role = Auth::getUserRole();
		$isLoggedIn = Auth::isLoggedIn();
		
		$designerUserRoleArray = array('siteManager','siteAdmin','siteDesigner');
		$managerUserRoleArray = array('siteManager','siteAdmin');
		$adminUserRoleArray = array('siteAdmin');

		if (Content::publishedContentExists($loc[0])) { $view = new ContentViewController($loc, $input, $errors); }
		if ($loc[0] == '') { $view = new IndexViewController($loc, $input, $mods, $errors, $msg); }
		if ($loc[0] == 'admin' && in_array($role,$adminUserRoleArray)) { $view = new AdminViewController($loc, $input, $errors); }
		if ($loc[0] == 'designer' && in_array($role,$designerUserRoleArray)) { $view = new DesignerViewController($loc, $input, $errors, $msg); }
		if ($loc[0] == 'enquiry') { $view = new EnquiryViewController($loc, $input, $errors); }
		if ($loc[0] == 'manager' && in_array($role,$managerUserRoleArray)) { $view = new ManagerViewController($loc, $input, $mods, $errors, $msg); }
		if ($loc[0] == 'newsletter') { $view = new NewsletterViewController($loc, $input, $errors, $msg); }
		if ($loc[0] == 'profile') { $view = new ProfileViewController($loc, $input, $errors); }
		if ($loc[0] == 'support' && $isLoggedIn) { $view = new SupportViewController($loc, $input, $errors); }

		$authViews = array('account-recovery','account-recovery-mail-sent','login','reset-password');
		if (in_array($loc[0],$authViews)) { $view = new AuthViewController($loc, $input, $errors, $mods); }

		$contactURLs = array('contact','contact-us','get-in-touch');
		if (in_array($loc[0],$contactURLs)) { $view = new ContactViewController($loc, $input, $errors); }
		
		$contentURLs = array('privacy','tos');
		if (in_array($loc[0],$contentURLs)) { $view = new ContentViewController($loc, $input, $errors); }

		foreach ($this->moduleArray AS $moduleName) {
			if ($loc[0] == $moduleName) {
				$moduleViewController = ModuleUtilities::moduleToClassName($moduleName, 'ViewController');
		        $view = new $moduleViewController($loc, $input, $mods, $errors, $msg);
		    }
		}
		
		if (!isset($view)) { $view = new NotFoundViewController($loc, $input, $errors); }
		
		// pageViewCounter();
		$page = new PageView($loc, $input, $mods, $errors, $msg);
		$html = $page->page($view->getView());
		return $html;
		
	}
	
}

?>