<?php

class DesignerController {

	private $urlArray;
	private $inputArray;
	private $moduleArray;
	private $errorArray;
	private $messageArray;
	
	public function __construct($urlArray, $inputArray, $moduleArray) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->moduleArray = $moduleArray;
		$this->errorArray = array();
		$this->messageArray = array();
		
		if (!Auth::isLoggedIn()) {
			$_SESSION['forward_url'] = $_SERVER['REQUEST_URI'];
			$login = "/" . Lang::prefix() . "login/";
			header("Location: $login");
		}
		
		$authorizedRoles = array('siteAdmin','siteManager','siteDesigner');
		$role = Auth::getUserRole();
		if (!in_array($role,$authorizedRoles)) {
			$message = "[DesignerController] You do not have view permissions for the designer module.";
			die($message);
		}
		
	}
	
	public function setState() {
		
		if ($_SESSION['lang'] == 'ja') { $lang = "/ja"; } else { $lang = ""; }

		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'content' && $this->urlArray[2] == 'create') {
			
			$successURL = $lang . "/designer/content/";

			if (!empty($this->inputArray)) {
				
				$content = new Content();
				
				if (!isset($this->inputArray['entryPublished'])) { $content->entryPublished = 0; }
				// $this->errorArray = Contract::validate($this->inputArray, 'create');

				if (empty($this->errorArray)) {
					
					
					
					// please explicitly allow field to be updated here
					foreach ($this->inputArray AS $property => $value) {
						if (isset($content->$property)) {
							$content->$property = $value;
						}
					} // refactor & secure
					
					Content::insert($content);
					header("Location: $successURL");
					
				}
				
			}
			
		}
		
		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'content' && $this->urlArray[2] == 'update' && ctype_digit($this->urlArray[3])) {
			
			$contentID = $this->urlArray[3];

			if ($this->urlArray[4] == 'images' && isset($this->inputArray['submitted-images'])) {

				Image::uploadImages($_FILES['images-to-upload'], 'Content', $contentID);

			} elseif (!empty($this->inputArray)) {

				$content = new Content($contentID);
				
				// booleans
				if (!isset($this->inputArray['entryPublished'])) { $content->entryPublished = 0; }

				// validation
				if ($contentID != $this->inputArray['contentID']) { $this->errorArray['contentID'][] = 'contentID mismatch'; }
				if ($content->siteID != $_SESSION['siteID']) { $this->errorArray['siteID'][] = 'siteID mismatch'; }
				
				if (empty($this->errorArray)) {

					// please explicitly allow field to be updated here
					foreach ($this->inputArray AS $property => $value) {
						if (isset($content->$property)) {
							$content->$property = $value;
						}
					} // refactor & secure
					
					$conditions = array('contentID' => $contentID);
					Content::update($content,$conditions);
					
					$this->messageArray[] = Lang::getLang('contentUpdateSuccessful');

				}
				
			}
			
		}

		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'themes' && $this->urlArray[2] == 'create') {
			
			$successURL = $lang . "/designer/themes/";
			
			if (!empty($this->inputArray)) {
				
				$theme = new Theme();
				
				// if (!isset($this->inputArray['xxxxxxx'])) { $theme->xxxxxxx = 0; }
				// $this->errorArray = Contract::validate($this->inputArray, 'create');

				if (empty($this->errorArray)) {

					// please explicitly allow field to be updated here
					foreach ($this->inputArray AS $property => $value) {
						if (isset($theme->$property)) {
							$theme->$property = $value;
						}
					} // refactor & secure
					
					Theme::insert($theme);
					header("Location: $successURL");
					
				}
				
			}
			
		}
		
		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'themes' && $this->urlArray[2] == 'select' && ctype_digit($this->urlArray[3])) {
			
			$successURL = "/" . Lang::prefix() . "designer/themes/";

			$site = new Site($_SESSION['siteID']);
			$theme = new Theme($this->urlArray[3]);
			
			if ($theme->siteID == $_SESSION['siteID']) {
				$site->themeID = $this->urlArray[3];
				$conditions = array('siteID' => $_SESSION['siteID']);
				Site::update($site,$conditions);
			}
			
			header("Location: $successURL");
			
		}
		
		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'themes' && $this->urlArray[2] == 'update' && ctype_digit($this->urlArray[3])) {
			
			$themeID = $this->urlArray[3];
			$successURL = $lang . "/designer/themes/";
			
			if (!empty($this->inputArray)) {
				
				$theme = new Theme($themeID);
				
				// booleans
				// if (!isset($this->inputArray['entryPublished'])) { $theme->entryPublished = 0; }

				// validation
				if ($themeID != $this->inputArray['themeID']) { $this->errorArray['themeID'][] = 'themeID mismatch'; }
				if ($theme->siteID != $_SESSION['siteID']) { $this->errorArray['siteID'][] = 'siteID mismatch'; }
				
				if (empty($this->errorArray)) {
					
					
					
					// please explicitly allow field to be updated here
					foreach ($this->inputArray AS $property => $value) {
						if (isset($theme->$property)) {
							$theme->$property = $value;
						}
					} // refactor & secure
					
					$conditions = array('themeID' => $themeID);
					Theme::update($theme,$conditions);
					header("Location: $successURL");
					
				}
				
			}
			
		}

		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'images' && isset($this->inputArray['submitted-images'])) {

			$this->errorArray = Image::uploadImages($_FILES['images-to-upload'],'Site',$_SESSION['siteID']);
			if (empty($this->errorArray)) {
				$successURL = "/" . Lang::languageUrlPrefix() . "designer/images/";
				header("Location: $successURL");
			}

		}
		
		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'images' && $this->urlArray[2] == 'delete' && ctype_digit($this->urlArray[3])) {
						
			$imageID = $this->urlArray[3];
			$image = new Image($imageID);
			$conditions = array('imageID' => $imageID);

			$ioa = new Audit();
			$ioa->auditAction = 'delete';
			$ioa->auditObject = 'Image';
			$ioa->auditObjectID = $imageID;
			$ioa->auditResult = 'success';
			$ioa->auditNote = json_encode($image);
			
			if ($image->siteID == $_SESSION['siteID']) {
				
				if (unlink($image->imagePath)) {
					
					Image::delete($image,$conditions);
					$successURL = "/" . Lang::languageUrlPrefix() . "designer/images/";
					header("Location: $successURL");
					
				} else {
					
					$this->errorArray['imageDelete'][] = "Could not delete that image. Please contact support.";
					$ioa->auditResult = 'fail';
					$ioa->auditNote = '{ "redflag": [{ "reason": "could not unlink image" }] }';
					Audit::createAuditEntry($ioa);
					
				}
				
			} else {

				$this->errorArray['imageDelete'][] = "You do not have permission to delete that image.";
				$ioa->auditResult = 'fail';
				$ioa->auditNote = '{ "redflag": [{ "reason": "trying to delete a different site\'s image" }] }';
				Audit::createAuditEntry($ioa);
				
			}

		}
		
		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'files' && isset($_FILES['files-to-upload'])) {

			$this->errorArray = File::uploadFiles($_FILES['files-to-upload'],'Site',$_SESSION['siteID'], $this->inputArray['fileTitleEnglish'], $this->inputArray['fileTitleJapanese']);
			if (empty($this->errorArray)) {
				$successURL = "/" . Lang::languageUrlPrefix() . "designer/files/";
				header("Location: $successURL");
			}
			
			
		}

		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'seo' && $this->urlArray[2] == 'create') {
			
			$successURL = $lang . "/designer/seo/";

			if (!empty($this->inputArray)) {
				
				$seo = new SEO();
				
				// if (!isset($this->inputArray['entryPublished'])) { $seo->entryPublished = 0; }
				// $this->errorArray = Contract::validate($this->inputArray, 'create');

				if (empty($this->errorArray)) {
					
					
					
					// please explicitly allow field to be updated here
					foreach ($this->inputArray AS $property => $value) {
						if (isset($seo->$property)) {
							$seo->$property = $value;
						}
					} // refactor & secure
					
					SEO::insert($seo);
					header("Location: $successURL");
					
				}
				
			}
			
		}
		
		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'seo' && $this->urlArray[2] == 'update' && ctype_digit($this->urlArray[3])) {
			
			$seoID = $this->urlArray[3];
			$successURL = $lang . "/designer/seo/";
			
			if (!empty($this->inputArray)) {
				
				$seo = new SEO($seoID);
				
				// booleans
				// if (!isset($this->inputArray['entryPublished'])) { $seo->entryPublished = 0; }

				// validation
				// if ($seoID != $this->inputArray['seoID']) { $this->errorArray['seoID'][] = 'seoID mismatch'; }
				// if ($seo->siteID != $_SESSION['siteID']) { $this->errorArray['siteID'][] = 'siteID mismatch'; }
				
				if (empty($this->errorArray)) {
					
					
					
					// please explicitly allow field to be updated here
					foreach ($this->inputArray AS $property => $value) {
						if (isset($seo->$property)) {
							$seo->$property = $value;
						}
					} // refactor & secure
					
					$conditions = array('seoID' => $seoID);
					SEO::update($seo,$conditions);
					header("Location: $successURL");
					
				}
				
			}
			
		}

		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'carousels' && $this->urlArray[2] == 'create' && !empty($this->inputArray)) {

			$carousel = new Carousel();
			
			if ($carousel->siteID != $_SESSION['siteID']) { $this->errorArray['siteID'][] = 'Wrong site. What you up to there, mate?'; }
			// $this->errorArray = Contract::validate($this->inputArray, 'create');

			if (empty($this->errorArray)) {

				foreach ($this->inputArray AS $property => $value) { if (isset($carousel->$property)) { $carousel->$property = $value; } }
				
				if (!isset($this->inputArray['carouselPublished'])) { $carousel->carouselPublished = 0; }
				
				$carouselID = Carousel::insert($carousel);
				
				if (!empty($_FILES['perihelionImages'])) {
					$arrayOfImageIDs = Image::uploadImages($_FILES['perihelionImages'],'Carousel',$carouselID, true);
					$cp = new CarouselPanel();
					$cp->carouselID = $carouselID;
					$cp->imageID = $arrayOfImageIDs[0];
					$cp->carouselPanelPublished = 1;
					$cp->carouselPanelDisplayOrder = 1;
					CarouselPanel::insert($cp);
				}
				
				$successURL = "/" . Lang::languageUrlPrefix() . "designer/carousels/update/" . $carouselID . "/";
				header("Location: $successURL");
				
			}

		}
		
		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'carousels' && $this->urlArray[2] == 'update' && ctype_digit($this->urlArray[3]) && !empty($this->inputArray)) {
			
			$carouselID = $this->urlArray[3];
			$successURL = "/" . Lang::languageUrlPrefix() . "designer/carousels/update/" . $carouselID . "/";

			$carousel = new Carousel($carouselID);
			if ($carousel->siteID != $_SESSION['siteID']) { $this->errorArray['siteID'][] = 'Wrong site. What you up to there, mate?'; }

			if (empty($this->errorArray)) {

				foreach ($this->inputArray AS $property => $value) { if (isset($carousel->$property)) { $carousel->$property = $value; } }
				
				if (!isset($this->inputArray['carouselPublished'])) { $carousel->carouselPublished = 0; }
				
				$conditions = array('carouselID' => $carouselID);
				Carousel::update($carousel,$conditions);

				if (isset($this->inputArray['carouselPanelID']) && !empty($this->inputArray['carouselPanelID'])) {
					
					foreach ($this->inputArray['carouselPanelID'] AS $carouselPanelID) {
						
						$cp = new CarouselPanel($carouselPanelID);
						$cp->carouselPanelTitleEnglish = $this->inputArray['carouselPanelTitleEnglish'][$carouselPanelID];
						$cp->carouselPanelSubtitleEnglish = $this->inputArray['carouselPanelSubtitleEnglish'][$carouselPanelID];
						$cp->carouselPanelAltEnglish = $this->inputArray['carouselPanelAltEnglish'][$carouselPanelID];
						$cp->carouselPanelTitleJapanese = $this->inputArray['carouselPanelTitleJapanese'][$carouselPanelID];
						$cp->carouselPanelSubtitleJapanese = $this->inputArray['carouselPanelSubtitleJapanese'][$carouselPanelID];
						$cp->carouselPanelAltJapanese = $this->inputArray['carouselPanelAltJapanese'][$carouselPanelID];
						$cp->carouselPanelUrlEnglish = $this->inputArray['carouselPanelUrlEnglish'][$carouselPanelID];
						$cp->carouselPanelUrlJapanese = $this->inputArray['carouselPanelUrlJapanese'][$carouselPanelID];
						$cp->carouselPanelDisplayOrder = $this->inputArray['carouselPanelDisplayOrder'][$carouselPanelID];
						
						if (!isset($this->inputArray['carouselPanelPublished'][$carouselPanelID])) { $cp->carouselPanelPublished = 0;}
						else { $cp->carouselPanelPublished = 1; }
						$cpConditions = array('carouselPanelID' => $carouselPanelID);
						CarouselPanel::update($cp, $cpConditions);
						
					}
					
				}
				
				if (!empty($this->inputArray['deleteCarouselPanel'])) {
					foreach($this->inputArray['deleteCarouselPanel'] AS $deleteThisCarouselPanelID) {
						$deleteThisCP = new CarouselPanel($deleteThisCarouselPanelID);
						$deleteCarouselPanelConditions = array('carouselPanelID' => $deleteThisCarouselPanelID);
						CarouselPanel::delete($deleteThisCP, $deleteCarouselPanelConditions);
					}
				}
				
				if (!empty($_FILES['perihelionImages']['name'][0])) {
					$arrayOfImageIDs = Image::uploadImages($_FILES['perihelionImages'],'Carousel',$carouselID, true);
					$cp = new CarouselPanel();
					$cp->carouselID = $carouselID;
					if (!empty($arrayOfImageIDs)) { $cp->imageID = $arrayOfImageIDs[0]; }
					$cp->carouselPanelPublished = 1;
					$cp->carouselPanelDisplayOrder = 1;
					CarouselPanel::insert($cp);
				}
				
				
				header("Location: $successURL");
				
			}

		}

		/*
		
		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'menus' && $this->urlArray[2] == 'create') {
			
			$successURL = $lang . "/designer/menus/";

			if (!empty($this->inputArray)) {
				
				$menu = new Menu();
				
				// if (!isset($this->inputArray['entryPublished'])) { $content->entryPublished = 0; }
				// $this->errorArray = Contract::validate($this->inputArray, 'create');

				if (empty($this->errorArray)) {
					
					
					
					// please explicitly allow field to be updated here
					foreach ($this->inputArray AS $property => $value) {
						if (isset($menu->$property)) {
							$menu->$property = $value;
						}
					} // refactor & secure
					
					Menu::insert($menu);
					header("Location: $successURL");
					
				}
				
			}
			
		}
		
		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'menus' && $this->urlArray[2] == 'update' && ctype_digit($this->urlArray[3])) {
			
			$menuID = $this->urlArray[3];
			$successURL = $lang . "/designer/menus/";
			
			if (!empty($this->inputArray)) {
				
				$menu = new Content($menuID);
				
				// booleans
				// if (!isset($this->inputArray['entryPublished'])) { $content->entryPublished = 0; }

				// validation
				// if ($contentID != $this->inputArray['contentID']) { $this->errorArray['contentID'][] = 'contentID mismatch'; }
				// if ($content->siteID != $_SESSION['siteID']) { $this->errorArray['siteID'][] = 'siteID mismatch'; }
				
				if (empty($this->errorArray)) {
					
					
					
					// please explicitly allow field to be updated here
					foreach ($this->inputArray AS $property => $value) {
						if (isset($menu->$property)) {
							$menu->$property = $value;
						}
					} // refactor & secure
					
					$conditions = array('menuID' => $menuID);
					Menu::update($menu,$conditions);
					header("Location: $successURL");
					
				}
				
			}
			
		}

		*/
		
		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'menus' && $this->urlArray[2] == 'item' && $this->urlArray[3] == 'create') {
			
			$successURL = $lang . "/designer/menus/";

			if (!empty($this->inputArray)) {
				
				$mi = new MenuItem();
				
				if (!isset($this->inputArray['menuItemPublished'])) { $mi->menuItemPublished = 0; }
				if (!isset($this->inputArray['menuItemDisplayAuth'])) { $mi->menuItemDisplayAuth = 0; }
				if (!isset($this->inputArray['menuItemDisplayAnon'])) { $mi->menuItemDisplayAnon = 0; }
				
				// $this->errorArray = Contract::validate($this->inputArray, 'create');

				if (empty($this->errorArray)) {

					// please explicitly allow field to be updated here
					foreach ($this->inputArray AS $property => $value) { if (isset($mi->$property)) { $mi->$property = $value; } } // refactor & secure
					
					MenuItem::insert($mi);
					header("Location: $successURL");
					
				}
				
			}
			
		}
		
		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'menus' && $this->urlArray[2] == 'item' && $this->urlArray[3] == 'update' && ctype_digit($this->urlArray[4])) {
			
			$menuItemID = $this->urlArray[4];
			$successURL = $lang . "/designer/menus/";
			
			if (!empty($this->inputArray)) {
				
				$mi = new MenuItem($menuItemID);
				
				// booleans
				if (!isset($this->inputArray['menuItemPublished'])) { $mi->menuItemPublished = 0; }
				if (!isset($this->inputArray['menuItemDisplayAuth'])) { $mi->menuItemDisplayAuth = 0; }
				if (!isset($this->inputArray['menuItemDisplayAnon'])) { $mi->menuItemDisplayAnon = 0; }
				if (!isset($this->inputArray['menuItemDisabled'])) { $mi->menuItemDisabled = 0; }

				// validation
				if ($mi->siteID != $_SESSION['siteID']) { $this->errorArray['siteID'][] = '[DesignController] => siteID mismatch'; }
				
				if (empty($this->errorArray)) {
					
					
					
					// please explicitly allow field to be updated here
					foreach ($this->inputArray AS $property => $value) {
						if (isset($mi->$property)) {
							$mi->$property = $value;
						}
					} // refactor & secure
					
					$conditions = array('menuItemID' => $menuItemID);
					MenuItem::update($mi,$conditions);
					header("Location: $successURL");
					
				}
				
			}
			
		}

		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'scripts' && $this->urlArray[2] == 'create') {
			
			$successURL = $lang . "/designer/scripts/";
			
			if (!empty($this->inputArray)) {
				
				$script = new Script();
				foreach ($this->inputArray AS $property => $value) { if (isset($script->$property)) { $script->$property = $value; } }
				if (!isset($this->inputArray['scriptEnabled'])) { $script->scriptEnabled = 0; }
				
				if ($script->siteID != $_SESSION['siteID']) { $this->errorArray['siteID'][] = 'siteID mismatch'; }

				if (empty($this->errorArray)) {
					Script::insert($script);
					header("Location: $successURL");
				}
				
			}
			
		}

		if ($this->urlArray[0] == 'designer' && $this->urlArray[1] == 'scripts' && $this->urlArray[2] == 'update' && ctype_digit($this->urlArray[3])) {
			
			$scriptID = $this->urlArray[3];
			$successURL = $lang . "/designer/scripts/";
			
			if (!empty($this->inputArray)) {
				
				$script = new Script($scriptID);
				foreach ($this->inputArray AS $property => $value) { if (isset($script->$property)) { $script->$property = $value; } }
				if (!isset($this->inputArray['scriptEnabled'])) { $script->scriptEnabled = 0; }
				
				if ($script->siteID != $_SESSION['siteID']) { $this->errorArray['siteID'][] = 'siteID mismatch'; }
				
				if (empty($this->errorArray)) {

					$conditions = array('scriptID' => $scriptID);
					Script::update($script,$conditions);
					header("Location: $successURL");
					
				}
				
			}
			
		}

	}
	
	public function getErrors() {
		return $this->errorArray;
	}
	
	public function getMessages() {
		return $this->messageArray;
	}
	
}

?>