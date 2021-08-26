<?php

class MenuView {
	
	private $siteID;
	private $menuID;
	private $urlArray;
	private $inputArray;
	public $errorArray;
	
	public function __construct($urlArray = array(), $inputArray = array(),  $errorArray = array()) {
		
		$this->siteID = $_SESSION['siteID'];
		$site = new Site($this->siteID);
		$this->menuID = $site->siteNavMenuID;
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
	}
	
	public function menuManager() {

		if (!Auth::isSiteManager()) { die("You must be logged in to access this resource."); }
	
		$menu = new Menu($this->menuID);
		foreach ($menu AS $key => $value) { ${$key} = $value; }
		if (!empty($this->inputArray)) { foreach ($this->inputArray AS $key => $value) { ${$key} = $value; } }
		$topLevelItems = $menu->topLevelItems(false);

		$h = "<div id=\"perihelionMenuManager\" class=\"perihelionManagerContainer\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
							$h .= "<div class=\"card-header\">";
								$h .= "<div class=\"card-title\"><h3>" . Lang::getLang('menuManager') . "<a class=\"btn btn-secondary btn-sm float-right\" href=\"/" . Lang::prefix() . "designer/menus/item/create/\"><span class=\"fas fa-plus\"></span></a></h3></div>";
							$h .= "</div>";
							$h .= "<div class=\"card-body\">";
								if (!empty($topLevelItems)) {
									$h .= "<ul>";
									foreach ($topLevelItems AS $topLevelItemID) {
										$h .= "<li>";
											$menuItem = new MenuItem($topLevelItemID);
											
											$h .= "[" . $menuItem->menuItemOrder . "] <a href=\"/" . Lang::prefix() . "designer/menus/item/update/" . $topLevelItemID . "/\">" . $menuItem->getAnchorText() . "</a> " . ($menuItem->menuItemPublished?"&#10004;":"");
											if ($menuItem->hasChildren(false)) {
												$children = $menuItem->getChildren(false);
												if (!empty($children)) {
													$h .= "<ul>";
														foreach ($children AS $childMenuItemID) {
															$childMenuItem = new MenuItem($childMenuItemID);
															$h .= "<li>[" . $childMenuItem->menuItemOrder . "] <a href=\"/" . Lang::prefix() . "designer/menus/item/update/" . $childMenuItemID . "/\">" . $childMenuItem->getAnchorText() . "</a> " . ($childMenuItem->menuItemPublished?"&#10004;":"") . "</li>";
														}
													$h .= "</ul>";
												}
											}
										
										$h .= "</li>";
									}
									$h .= "<ul>";
								}
							$h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";
			
		return $this->html = $h;
		
	}

	public function siteSettingsNav() {
		
		$page = $this->urlArray[1];
		$lang = Lang::languageUrlPrefix();
		
		$userRole = new UserRole($_SESSION['siteID'],$_SESSION['userID']);
		$role = $userRole->getUserRole();
		$allowedUserRoles = array('siteManager','siteAdmin');
		if (!in_array($role,$allowedUserRoles)) { die('permissions error :: siteSettingsNav'); }

		$h = "<div id=\"managerNavTabs\" class=\"fresh-perihelion-nav-tabs\">";
			$h .= "<div class=\"container\">";
				$h .= '<ul class="nav nav-tabs">';
					
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='settings'?' active':'') . '" href="/' . $lang . 'manager/settings/">' . Lang::getLang('settings') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='users'?' active':'') . '" href="/' . $lang . 'manager/users/">' . Lang::getLang('users') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='contacts'?' active':'') . '" href="/' . $lang . 'manager/contacts/">' . Lang::getLang('contacts') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='newsletter'?' active':'') . '" href="/' . $lang . 'manager/newsletter/">' . Lang::getLang('newsletter') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='google'?' active':'') . '" href="/' . $lang . 'manager/google/">' . Lang::getLang('google') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='social'?' active':'') . '" href="/' . $lang . 'manager/social/">' . Lang::getLang('social') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='email'?' active':'') . '" href="/' . $lang . 'manager/email/">' . Lang::getLang('email') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='modules'?' active':'') . '" href="/' . $lang . 'manager/modules/">' . Lang::getLang('modules') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='audit'?' active':'') . '" href="/' . $lang . 'manager/audit/">' . Lang::getLang('audit') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='uptime'?' active':'') . '" href="/' . $lang . 'manager/uptime/">' . Lang::getLang('uptime') . '</a></li>';
					// $h .= '<li class="nav-item"><a class="nav-link' . ($page=='stats'?' active':'') . '" href="/' . $lang . 'manager/stats/">' . Lang::getLang('stats') . '</a></li>';
					// $h .= '<li class="nav-item"><a class="nav-link' . ($page=='notes'?' active':'') . '" href="/' . $lang . 'manager/notes/">' . Lang::getLang('notes') . '</a></li>';
					// $h .= '<li class="nav-item"><a class="nav-link' . ($page=='notifications'?' active':'') . '" href="/' . $lang . 'manager/notifications/">' . Lang::getLang('notifications') . '</a></li>';
					
				$h .= '</ul>';
			$h .= '</div>';
		$h .= '</div>';
		
		return $h;

	}
	
	public function designerSubMenu() {
		
		$page = $this->urlArray[1];
		$lang = Lang::languageUrlPrefix();
		
		$userRole = new UserRole($_SESSION['siteID'],$_SESSION['userID']);
		$role = $userRole->getUserRole();
		$allowedUserRoles = array('siteDesigner','siteManager','siteAdmin');
		if (!in_array($role,$allowedUserRoles)) { die('permissions error :: designerSubMenu'); }
				
		$h = "<div id=\"managerNavTabs\" class=\"fresh-perihelion-nav-tabs\">";
			$h .= "<div class=\"container\">";
				$h .= '<ul class="nav nav-tabs">';
				
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='content'?' active':'') . '" href="/' . $lang . 'designer/content/">' . Lang::getLang('content') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='themes'?' active':'') . '" href="/' . $lang . 'designer/themes/">' . Lang::getLang('themes') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='images'?' active':'') . '" href="/' . $lang . 'designer/images/">' . Lang::getLang('images') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='carousels'?' active':'') . '" href="/' . $lang . 'designer/carousels/">' . Lang::getLang('carousels') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='menus'?' active':'') . '" href="/' . $lang . 'designer/menus/">' . Lang::getLang('menus') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='seo'?' active':'') . '" href="/' . $lang . 'designer/seo/">' . Lang::getLang('seo') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='files'?' active':'') . '" href="/' . $lang . 'designer/files/">' . Lang::getLang('files') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='scripts'?' active':'') . '" href="/' . $lang . 'designer/scripts/">' . Lang::getLang('scripts') . '</a></li>';
					if ($role == 'siteAdmin') { $h .= '<li class="nav-item"><a class="nav-link' . ($page=='tiles'?' active':'') . '" href="/' . $lang . 'designer/tiles/">' . Lang::getLang('tiles') . '</a></li>'; }
					
				$h .= '</ul>';
			$h .= '</div>';
		$h .= '</div>';
		
		return $h;

	}

	public function adminSubMenu() {
		
		$page = $this->urlArray[1];
		$lang = Lang::languageUrlPrefix();
		
		$userRole = new UserRole($_SESSION['siteID'],$_SESSION['userID']);
		$role = $userRole->getUserRole();
		$allowedUserRoles = array('siteAdmin');
		if (!in_array($role,$allowedUserRoles)) { die('adminSubMenu() permissions error'); }
		
		$h = "<div id=\"adminNavTabs\" class=\"fresh-perihelion-nav-tabs\">";
			$h .= "<div class=\"container\">";
				$h .= '<ul class="nav nav-tabs">';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='audit'?' active':'') . '" href="/' . $lang . 'admin/audit/">' . Lang::getLang('audit') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='uptime'?' active':'') . '" href="/' . $lang . 'admin/uptime/">' . Lang::getLang('uptime') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='not-found'?' active':'') . '" href="/' . $lang . 'admin/not-found/">' . Lang::getLang('notFound') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='server'?' active':'') . '" href="/' . $lang . 'admin/server/">' . Lang::getLang('server') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='language'?' active':'') . '" href="/' . $lang . 'admin/language/">' . Lang::getLang('language') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='geography'?' active':'') . '" href="/' . $lang . 'admin/geography/">' . Lang::getLang('geography') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='currency'?' active':'') . '" href="/' . $lang . 'admin/currency/">' . Lang::getLang('currency') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='blacklist'?' active':'') . '" href="/' . $lang . 'admin/blacklist/">' . Lang::getLang('blacklist') . '</a></li>';
					$h .= '<li class="nav-item"><a class="nav-link' . ($page=='cron'?' active':'') . '" href="/' . $lang . 'admin/cron/">' . Lang::getLang('cron') . '</a></li>';
				$h .= '</ul>';
			$h .= '</div>';
		$h .= '</div>';
		
		return $h;

	}

	public function supportNav() {
		
		$page = $this->urlArray[0];
		$lang = Lang::languageUrlPrefix();
		
		$h = "<div id=\"adminNavTabs\" class=\"fresh-perihelion-nav-tabs\">";
			$h .= "<div class=\"container\">";
				$h .= '<ul class="nav nav-tabs">';
					$h .= '<li class="nav-item"><a href="/' . $lang . 'support/faq/">' . Lang::getLang('faq') . '</a></li>';
					$h .= '<li class="nav-item"><a href="/' . $lang . 'support/docs/">' . Lang::getLang('documentation') . '</a></li>';
					$h .= '<li class="nav-item"><a href="/' . $lang . 'support/help/">' . Lang::getLang('support') . '</a></li>';
				$h .= '</ul>';
			$h .= '</div>';
		$h .= '</div>';
		
		return $h;
	}

	public static function parentDropdown($menuItemID = 0) {

		$site = new Site($_SESSION['siteID']);
		$menuID = $site->siteNavMenuID;
		$menu = new Menu($menuID);
		
		$topLevelItems = $menu->topLevelItems();
		
		$h = "<select name=\"menuItemParentID\" class=\"form-control\">";
			$h .= "<option value=\"0\">" . Lang::getLang('noParent') . "</option>";
			foreach ($topLevelItems AS $thisMenuItemID) {
				$menuItem = new MenuItem($thisMenuItemID);
				$h .= "<option value=\"". $thisMenuItemID . "\"" . ($menuItemID==$thisMenuItemID?" selected":"") . ">" . $menuItem->getAnchorText() . "</option>";
			}
		$h .= "</select>";
		
		return $h;
	
	}
	
}

?>