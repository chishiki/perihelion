<?php

class NavBarView {
	
    private $urlArray;
    private $inputArray;
    private $moduleArray;
	private $site;
	
	public function __construct($urlArray, $inputArray, $moduleArray, Site $site) {
		
	    $this->urlArray = $urlArray;
	    $this->inputArray = $inputArray;
	    $this->moduleArray = $moduleArray;
		$this->site = $site;
	}

	public function navbarView($navbarClasses) {

	    $navbar = new NavBar($this->urlArray, $this->inputArray, $this->moduleArray, $this->site->siteNavMenuID);
		$items = $navbar->getNavBarItems();

		$navbarBrand = $this->site->getTitle();
		$imr = new ImageMostRecent('Logo');
		$logoImageID = $imr->imageID();
		if ($logoImageID) {
			$logo = new Image($logoImageID);
			$navbarBrand = '<img id="navbar_logo" src="' . $logo->src() . '" alt="' . $this->site->getTitle() . '">';
		}


		$h = '

        <nav class="navbar ' . implode(' ', $navbarClasses) . '">
    		
            <a class="navbar-brand" href="/' . Lang::prefix() . '">' . $navbarBrand . '</a>
    		
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    		  <span class="navbar-toggler-icon"></span>
    		</button>
    		
    		<div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav mr-auto">

        ';

					foreach ($items AS $item) {

						$h .= "<li class=\"nav-item " . (!empty($item['classes'])?implode(' ', $item['classes']):'') . "\">\n";

							$h .= '<a href="' . $item['url'] . '"';

								if (isset($item['children'])) {
									$h .= ' class="nav-link dropdown-toggle' . ($item['disabled']?' disabled':'') . '"';
									$h .= ' id="navbarDropdown' . $item['id'] . '"';
									$h .= ' role="button"';
									$h .= ' data-toggle="dropdown"';
									$h .= ' aria-haspopup="true"';
									$h .= ' aria-expanded="false"';
								} else {
									$h .= ' class="nav-link' . ($item['disabled']?' disabled':'') . '"';
								}

								if ($item['disabled']) { $h .= ' tabindex="-1" aria-disabled="true"'; }

							$h .= '>' . $item['anchor'] . '</a>';

							if (isset($item['children'])) {

								$h .= '<div class="dropdown-menu" aria-labelledby="navbarDropdown' . $item['id'] . '">';

									foreach ($item['children'] AS $child) {
										$h .= '<a class="dropdown-item';
											if ($child['disabled']) { $h .= ' disabled'; }
											if (!empty($child['classes'])) { $h .= ' '.implode(' ', $child['classes']); }
										$h .= '" href="' . $child['url'] . '">' . $child['anchor'] . '</a>';
									}


								$h .= "</div>";

							}

						$h .= "</li>\n";

					}

					foreach ($this->moduleArray AS $moduleName) {
						$moduleNavbarItemsView = ModuleUtilities::moduleToClassName($moduleName, 'NavbarItemsView');
						if (class_exists($moduleNavbarItemsView)) {
							$navbarItems = new $moduleNavbarItemsView();
							if (method_exists($navbarItems, 'itemsLeft')) {
								$h .= $navbarItems->itemsLeft();
							}
						}
					}

					if (Auth::isLoggedIn()) { $h .= self::masterMenu(); }
		
				$h .= '</ul>';

				/*
				// if a module navbar search form exists then use it
				foreach ($this->moduleArray AS $moduleName) {
			        if ($this->urlArray[0] == $moduleName) {
						$navbarSearchViewClass = ModuleUtilities::moduleToClassName($moduleName, 'SearchView');
			            $sv = new $navbarSearchViewClass($this->urlArray,$this->inputArray,$this->moduleArray,array(),array());
			            $sq = new SearchQuery();
			            if (isset($_SESSION['search']['searchQueryString']) && !empty($_SESSION['search']['searchQueryString'])) {
			            	$sq = new SearchQuery($_SESSION['search']['searchQueryString']);
			           	}
			            $h .=  $sv->navbarSearchForm($sq);
			        }
				}
				*/

				$h .= '<ul class="navbar-nav ml-auto">';
					foreach ($this->moduleArray AS $moduleName) {
						$moduleNavbarItemsView = ModuleUtilities::moduleToClassName($moduleName, 'NavbarItemsView');
						if (class_exists($moduleNavbarItemsView)) {
							$navbarItems = new $moduleNavbarItemsView();
							if (method_exists($navbarItems, 'itemsRight')) {
								$h .= $navbarItems->itemsRight();
							}
						}
					}
					$h .= '<li id="navbar_language_link" class="nav-item">';
						$h .= '<a class="nav-link" href="'. Lang::switchLanguageURL() . '">' . ($_SESSION['lang']=='ja'?'English':'日本語') . '</a>';
					$h .= '</li>';
				$h .= '</ul>';

			$h .= '</div>';
		$h .= '</nav>';

		return $h;

	}
	
	public static function masterMenu() {

		$lup = Lang::languageUrlPrefix();
		$role = Auth::getUserRole();

		$canViewCoreManagerMenus = false;
		$dropdownAnchor = 'settings';

		if (in_array($role,Config::read('manager.menu.access'))) {
			$canViewCoreManagerMenus = true;
			$dropdownAnchor = 'navbar' . ucfirst($role);
		}

		$h = "<li class=\"nav-item dropdown\">\n";
		
    		$h .= '<a href="#"';
    		    $h .= ' class="nav-link dropdown-toggle"';
    		    $h .= ' id="navbarDropdownMasterMenu"';
    		    $h .= ' role="button"';
    		    $h .= ' data-toggle="dropdown"';
    		    $h .= ' aria-haspopup="true"';
    		    $h .= ' aria-expanded="false"';
    		$h .= '>' . Lang::getLang($dropdownAnchor) . '</a>';

			$h .= '<div class="dropdown-menu" aria-labelledby="navbarDropdownMasterMenu">';

				if ($canViewCoreManagerMenus) {

					$designerUserRoleArray = array('siteDesigner','siteManager','siteAdmin');
					if (in_array($role,$designerUserRoleArray)) {
						$h .= '<a class="dropdown-item disabled" href="#" tabindex="-1" aria-disabled="true">' . Lang::getLang("siteDesigner") . '</a>';
						$h .= '<a class="dropdown-item" href="/' . $lup . 'designer/content/">' . Lang::getLang('contentManagement') . '</a>';
						$h .= '<div class="dropdown-divider"></div>';
					}

					$managerUserRoleArray = array('siteManager','siteAdmin');
					if (in_array($role,$managerUserRoleArray)) {
						$h .= '<a class="dropdown-item disabled" href="#" tabindex="-1" aria-disabled="true">' . Lang::getLang("siteManager") . '</a>';
						$h .= '<a class="dropdown-item" href="/' . $lup . 'manager/settings/">' . Lang::getLang('settings') . '</a>';
						$h .= '<div class="dropdown-divider"></div>';
					}

					if ($role == 'siteAdmin') {
						$h .= '<a class="dropdown-item disabled" href="#" tabindex="-1" aria-disabled="true">' . Lang::getLang("siteAdmin") . '</a>';
						$h .= '<a class="dropdown-item" href="/' . $lup . 'admin/audit/">' . Lang::getLang('tools') . '</a>';
						$h .= '<div class="dropdown-divider"></div>';
					}

				}

				$h .= '<a class="dropdown-item disabled" href="#" tabindex="-1" aria-disabled="true">' . Lang::getLang("profile") . '</a>';
				$h .= '<a class="dropdown-item" href="/' . $lup . 'profile/">' . Lang::getLang('emailAndPassword') . '</a>';
				$h .= '<a class="dropdown-item" href="/' . $lup . 'logout/">' . Lang::getLang('logout') . '</a>';
				
				/*
				if (in_array($siteID,$developmentSites)) {
				    $h .= '<div class="dropdown-divider"></div>';
					$h .= '<a class="dropdown-item disabled" href="#" tabindex="-1" aria-disabled="true">' . Lang::getLang("support") . '</a>';
					$h .= '<a class="dropdown-item" href="' . $lup . 'support/">' . Lang::getLang('help') . '</a>';
				}
				*/
				
			$h .= "</ul>\n";
			
		$h .= "</li>\n";
			
		return $h;
		
	}

}

?>