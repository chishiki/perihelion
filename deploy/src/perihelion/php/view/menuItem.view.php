<?php

class MenuItemView {
	
	private $menuItemID;
	private $inputArray;
	public $errorArray;
	
	public function __construct($menuItemID = 0, $inputArray = array(),  $errorArray = array()) {

		$this->menuItemID = $menuItemID;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
	}
	
	public function menuItemForm() {

		if (!Auth::isLoggedIn()) { die("You must be logged in to access this resource."); }
	
		if ($this->menuItemID) { $type = "update"; } else { $type = "create"; }
		$formAction = "/" . Lang::prefix() . "designer/menus/item/" . $type . "/" . ($type=='update'?$this->menuItemID."/":"");
		$panelTitle = $type . 'MenuItem';
	
		$menu = new MenuItem($this->menuItemID);
		foreach ($menu AS $key => $value) { ${$key} = $value; }
		if (!empty($this->inputArray)) {
			foreach ($this->inputArray AS $key => $value) { ${$key} = $value; }
			if (!isset($menuItemPublished)) { $menuItemPublished = 0; }
		}
		
		$h = "<div id=\"perihelionProject\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";

							$h .= "<div class=\"card-header perihelionMenuItemPanelHeading\">";
								$h .= "<div class=\"card-title\"><h3>" . Lang::getLang($panelTitle) . "</h3></div>";
							$h .= "</div>";

							$h .= "<div class=\"card-body\">";
							
								$h .= "<form id=\"perihelionMenuItemForm\" name=\"perihelionMenuItemForm\"  method=\"post\" action=\"" . $formAction . "\">";
						
									if ($type == 'update') { $h .= "<input type=\"hidden\" name=\"menuItemID\" value=\"" . $menuItemID . "\">"; }

									$h .= "<div class=\"form-group row\">";
										$h .= "<label for=\"menuItemURL\" class=\"col-form-label col-sm-4\">" . Lang::getLang('menuItemURL') . "</label>";
										$h .= "<div class=\"col-sm-4\">";
											$h .= "<input type=\"text\" id=\"menuItemURL\" name=\"menuItemURL\" class=\"form-control\" value=\"" . $menuItemURL . "\">";
										$h .= "</div>";
									$h .= "</div>";

									$h .= "<hr />";
									
									$h .= "<div class=\"form-group row\">";
										$h .= "<label for=\"menuItemAnchorTextEnglish\" class=\"col-form-label col-sm-4\">" . Lang::getLang('menuItemAnchorTextEnglish') . "</label>";
										$h .= "<div class=\"col-sm-4\">";
											$h .= "<input type=\"text\" id=\"menuItemAnchorTextEnglish\" name=\"menuItemAnchorTextEnglish\" class=\"form-control\" value=\"" . $menuItemAnchorTextEnglish . "\">";
										$h .= "</div>";
									$h .= "</div>";

									$h .= "<div class=\"form-group row\">";
										$h .= "<label for=\"menuItemAnchorTextJapanese\" class=\"col-form-label col-sm-4\">" . Lang::getLang('menuItemAnchorTextJapanese') . "</label>";
										$h .= "<div class=\"col-sm-4\">";
											$h .= "<input type=\"text\" id=\"menuItemAnchorTextJapanese\" name=\"menuItemAnchorTextJapanese\" class=\"form-control\" value=\"" . $menuItemAnchorTextJapanese . "\">";
										$h .= "</div>";
									$h .= "</div>";
									
									$h .= "<hr />";
									
									$h .= "<div class=\"form-group row\">";
										$h .= "<label for=\"menuItemPublished\" class=\"col-form-label col-sm-4\">" . Lang::getLang('menuItemPublished') . "</label>";
										$h .= "<div class=\"col-sm-1\">";
											$h .= "<input type=\"checkbox\" id=\"menuItemPublished\" name=\"menuItemPublished\" value=\"1\"" . ($menuItemPublished?" checked":"") . ">";
										$h .= "</div>";
									$h .= "</div>";

									$h .= "<div class=\"form-group row\">";
										$h .= "<label for=\"menuItemParentID\" class=\"col-form-label col-sm-4\">" . Lang::getLang('parentMenuItem') . "</label>";
										$h .= "<div class=\"col-12 col-sm-4\">" . MenuView::parentDropdown($menuItemParentID) . "</div>";
									$h .= "</div>";
									
									$h .= "<div class=\"form-group row\">";
										$h .= "<label for=\"menuItemOrder\" class=\"col-form-label col-sm-4\">" . Lang::getLang('menuItemOrder') . "</label>";
										$h .= "<div class=\"col-12 col-sm-2 col-md-1\">" . FormElements::numberDropdown('menuItemOrder', $menuItemOrder) . "</div>";
									$h .= "</div>";

									$h .= "<hr />";

									$h .= "<div class=\"form-group row\">";
										$h .= "<label for=\"menuItemDisplayAuth\" class=\"col-form-label col-sm-4\">" . Lang::getLang('menuItemDisplayAuth') . "</label>";
										$h .= "<div class=\"col-sm-1\">";
											$h .= "<input type=\"checkbox\" id=\"menuItemDisplayAuth\" name=\"menuItemDisplayAuth\" value=\"1\"" . ($menuItemDisplayAuth?" checked":"") . ">";
										$h .= "</div>";
									$h .= "</div>";
									
									$h .= "<div class=\"form-group row\">";
										$h .= "<label for=\"menuItemDisplayAnon\" class=\"col-form-label col-sm-4\">" . Lang::getLang('menuItemDisplayAnon') . "</label>";
										$h .= "<div class=\"col-sm-1\">";
											$h .= "<input type=\"checkbox\" id=\"menuItemDisplayAnon\" name=\"menuItemDisplayAnon\" value=\"1\"" . ($menuItemDisplayAnon?" checked":"") . ">";
										$h .= "</div>";
									$h .= "</div>";
									
									$h .= "<div class=\"form-group row\">";
    									$h .= "<label for=\"menuItemDisabled\" class=\"col-form-label col-sm-4\">" . Lang::getLang('menuItemDisabled') . "</label>";
    									$h .= "<div class=\"col-sm-1\">";
    									   $h .= "<input type=\"checkbox\" id=\"menuItemDisabled\" name=\"menuItemDisabled\" value=\"1\"" . ($menuItemDisabled?" checked":"") . ">";
    									$h .= "</div>";
									$h .= "</div>";

									$h .= "<div class=\"form-group row\">";
										$h .= "<label for=\"menuItemClasses\" class=\"col-form-label col-sm-4\">" . Lang::getLang('menuItemClasses') . "</label>";
										$h .= "<div class=\"col-sm-4\">";
											$h .= "<input type=\"text\" id=\"menuItemClasses\" name=\"menuItemClasses\" class=\"form-control\" value=\"" . $menuItemClasses . "\">";
										$h .= "</div>";
									$h .= "</div>";
									
									$h .= "<hr />";
									
									$h .= "<div class=\"form-group row\">";
										$h .= "<div class=\"col-sm-8\">";
											$h .= "<button type=\"submit\" name=\"perihelionProjectSubmit\" id=\"perihelionProjectSubmit\" class=\"btn btn-primary float-right\">";
											$h .= "<span class=\"fas fa-check\"></span> " . strtoupper(Lang::getLang($type)) . "</button>";
										$h .= "</div>";
									$h .= "</div>";
									
									
									
								$h .= "</form>";
							
							$h .= "</div>";
						$h .= "</div>";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";
			
		return $this->html = $h;
		
	}

}

?>