<?php

class ThemeView {
	
	private $urlArray;
	private $inputArray;
	public $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
	}

	public function themeForm($action, $themeID = null, $errorArray) {
		
		if ($action == 'update' && $themeID) {
			$theme = new Theme($themeID);
			$actionURL = '/' . Lang::languageUrlPrefix() . 'designer/themes/update/' . $themeID . '/';
		} else {
			$theme = new Theme();
			$actionURL = '/' . Lang::languageUrlPrefix() . 'designer/themes/create/';
		}

		/* Maybe this Block could go in the constructor */
    $css_error = null;
    $name_error = null;
    $nameInputError = null;
    $cssTextErrors = null;

    if (!empty($errorArray)) {
      //print_r($errorArray);
      if(isset($errorArray['themeNameError'])) {
        $name_error = $errorArray['themeNameError'][0];
        $nameInputError = 'is-invalid';
      }
      if(isset($errorArray['themeCSSError'])) {
        $css_error =  $errorArray['themeCSSError'][0];
        $cssTextErrors = 'is-invalid';
      }
    }




		if (!empty($this->inputArray)) { foreach ($this->inputArray AS $key => $value) { if (isset($theme->$key)) { $theme->$key = $value; } } }
		
		$h = "<div id=\"perihelionThemeForm\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";

							$h .= "<div class=\"card\" >";
								$h .= "<div class=\"card-header\"><div class=\"card-title\">" . Lang::getLang('theme') . "</div></div>";
								$h .= "<div class=\"card-body\">";

									$h .= '
									
										<form role="form" class="form-horizontal" action="' . $actionURL . '" method="post" enctype="multipart/form-data">

											' . ($action=='update'&&$themeID?'<input type="hidden" name="themeID" value="'.$themeID.'">':'') . '
											
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="themeName">' . Lang::getLang('themeName') . '</label>
												<div class="col-sm-4">
													<input type="text" id="themeName" name="themeName" class="form-control ' . $nameInputError . ' " placeholder="Theme Name" value="' . $theme->themeName . '"' . ($action=='update'?' readonly="readonly"':'') . '>
													<div class="invalid-feedback">
                           ' . $name_error . '
                           </div>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="themeCss">' . Lang::getLang('CSS') . '</label>
												<div class="col-sm-10">
												<!-- valid CSS only 有効なCSSのみ validCSSOnly --> 
												<textarea class="form-control  ' . $cssTextErrors . ' " rows="25" id="themeCss" name="themeCss" placeholder="' . Lang::getLang('validCSSOnly') . '">' . $theme->themeCss . '</textarea>
												　<div class="invalid-feedback">
                           ' . $css_error . '
                          </div>
												</div>
											</div>

											<!--
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="imageToBeUploaded">' . Lang::getLang('images') . '</label>
												<div class="col-sm-10"><input type="file" name="imageToBeUploaded" value="addAnImage"></div>
											</div>
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="faviconToBeUploaded">' . Lang::getLang('favicon') . '</label>
												<div class="col-sm-10"><input type="file" name="faviconToBeUploaded" value="uploadFavicon"></div>
											</div>
											-->

											<div class="form-group row">
												<div class="col-sm-12 text-right"><button type="submit" class="btn btn-primary" name="submit">' . Lang::getLang($action) . '</button></div>
											</div>

										</form>
									
									';
								$h .= "</div>";
							$h .= "</div>";

					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>"; // #perihelionThemeForm

		return $h;

	}
	
	public function themeList() {
		
		$themeArray = Theme::themeArray($_SESSION['siteID']);
		$actionURL = '/' . Lang::languageUrlPrefix() . 'designer/themes/create/';
		
		$h = "<div id=\"perihelionThemeList\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";

							$h .= "<div class=\"card\" >";

								$h .= "<div class=\"card-header\">";
									$h .= "<div class=\"card-title\">";
										$h .= Lang::getLang('themes') . " <a class=\"btn btn-secondary btn-sm float-right\" href=\"" . $actionURL . "\"><span class=\"fas fa-plus\"></span></a>";
									$h .= "</div>";
								$h .= "</div>";
							
								$h .= "<div class=\"card-body\">";

									$h .= "<div class=\"table-responsive\">";
										$h .= "<table class=\"table table-bordered table-striped table-hover\">";
											$h .= "<tr>";
												$h .= "<th class=\"text-center\">" . Lang::getLang('ID') . "</th>";
												$h .= "<th>" . Lang::getLang('name') . "</th>";
												$h .= "<th class=\"text-center\">" . Lang::getLang('themeCreationDateTime') . "</th>";
												$h .= "<th class=\"text-center\">" . Lang::getLang('selectedTheme') . "</th>";
												$h .= "<th class=\"text-center\">" . Lang::getLang('action') . "</th>";
											$h .= "</tr>";
											foreach($themeArray AS $themeID) {
												
												$theme = new Theme($themeID);
												$site = new Site($_SESSION['siteID']);

												$h .= "<tr>";
													$h .= "<td class=\"text-center\">" . $themeID . "</td>";
													$h .= "<td>" . $theme->themeName . "</td>";
													$h .= "<td class=\"text-center\">" . $theme->themeCreationDateTime . "</td>";
													$h .= "<td class=\"text-center\">";
														if ($themeID == $site->themeID) {
															$h .= "<span class=\"fas fa-check\" style=\"color:#000;\"> </span> " . Lang::getLang('selectedTheme');
														} else {
															$select_theme = '/' . Lang::languageUrlPrefix() . 'designer/themes/select/' . $themeID . '/';
															$h .= "<a class=\"btn btn-secondary btn-sm\" href=\"" . $select_theme . "\"><span class=\"\" style=\"color:#fff;\"></span> " . Lang::getLang('select') . "</a>";
														}
													$h .= "</td>";
													$h .= "<td class=\"text-center\"><a class=\"btn btn-secondary btn-sm\" href=\"/" . Lang::languageUrlPrefix() . "designer/themes/update/" . $themeID . "/\">" . Lang::getLang('update') . "</a></td>";
												$h .= "</tr>";
												
											}
										$h .= "</table>";
									$h .= "</div>";

								$h .= "</div>";
							$h .= "</div>";

					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>"; // #perihelionThemeList

		return $h;

	}

}

?>
