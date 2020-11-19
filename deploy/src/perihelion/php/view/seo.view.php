<?php

class SeoView {
	
	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
	}

	public function seoForm($seoID = null) {
		
		if ($seoID) {
			$seo = new SEO($seoID);
			$action = 'update';
			$formURL = '/' . Lang::languageUrlPrefix() . 'designer/seo/update/' . $seoID . '/';
		} else {
			$seo = new SEO();
			$action = 'create';
			$formURL = '/' . Lang::languageUrlPrefix() . 'designer/seo/create/';
		}
		if (!empty($this->inputArray)) { foreach ($this->inputArray AS $key => $value) { if (isset($seo->$key)) { $seo->$key = $value; } } }
		
		$h = '<div id="perihelion_seo_form">';
			$h .= '<div class="container">';
				$h .= '<div class="row">';
					$h .= '<div class="col-sm-12">';

							$h .= '<div class="card" >';
								$h .= '<div class="card-header"><div class="card-title">' . Lang::getLang('seo') . '</div></div>';
								$h .= '<div class="card-body">';

									$h .= '<form method="post" action="' . $formURL . '">';

										if ($seoID) { $h .= '<input type="hidden" name="seoID" value="' . $seoID  . '">'; }

										$h .= '<div class="form-group row">';
											$h .= '<label class="col-sm-4 col-form-label" for="seoURL">' . Lang::getLang('seoURL') . '</label>';
											$h .= '<div class="col-sm-6">';
												$h .= '<input type="text" id="seoURL" name="seoURL" class="form-control" placeholder="desired url" value="' . $seo->seoURL . '">';
											$h .= '</div>';
										$h .= '</div>';
										
										$h .= '<div class="form-group row">';
											$h .= '<label class="col-sm-4 col-form-label" for="systemURL">' . Lang::getLang('systemURL') . '</label>';
											$h .= '<div class="col-sm-6">';
												$h .= '<input type="text" id="systemURL" name="systemURL" class="form-control" placeholder="system url" value="' . $seo->systemURL . '">';
											$h .= '</div>';
										$h .= '</div>';
										
										$h .= '<hr />';
										
										$h .= '<div class="form-group row">';
											$h .= '<label class="col-sm-4 col-form-label" for="seoTitleEnglish">' . Lang::getLang('seoTitleEnglish') . '</label>';
											$h .= '<div class="col-sm-6">';
												$h .= '<input type="text" id="seoTitleEnglish" name="seoTitleEnglish" class="form-control" placeholder="Business Name" value="' . $seo->seoTitleEnglish . '">';
											$h .= '</div>';
										$h .= '</div>';
										
										$h .= '<div class="form-group row">';
											$h .= '<label class="col-sm-4 col-form-label" for="seoKeywordsEnglish">' . Lang::getLang('seoKeywordsEnglish') . '</label>';
											$h .= '<div class="col-sm-6">';
												$h .= '<input type="text" id="seoKeywordsEnglish" name="seoKeywordsEnglish" class="form-control" placeholder="property, real estate, management..." value="' . $seo->seoKeywordsEnglish . '">';
											$h .= '</div>';
										$h .= '</div>';
										
										$h .= '<div class="form-group row">';
											$h .= '<label class="col-sm-4 col-form-label" for="seoDescriptionEnglish">' . Lang::getLang('seoDescriptionEnglish') . '</label>';
											$h .= '<div class="col-sm-6">';
												$h .= '<textarea id="seoDescriptionEnglish" name="seoDescriptionEnglish" class="form-control" placeholder="Description..." value="">' . $seo->seoDescriptionEnglish . '</textarea>';
											$h .= '</div>';
										$h .= '</div>';

										$h .= '<hr />';
										
										$h .= '<div class="form-group row">';
											$h .= '<label class="col-sm-4 col-form-label" for="seoTitleJapanese">' . Lang::getLang('seoTitleJapanese') . '</label>';
											$h .= '<div class="col-sm-6">';
												$h .= '<input type="text" id="seoTitleJapanese" name="seoTitleJapanese" class="form-control" placeholder="desired-url" value="' . $seo->seoTitleJapanese . '">';
											$h .= '</div>';
										$h .= '</div>';
										
										$h .= '<div class="form-group row">';
											$h .= '<label class="col-sm-4 col-form-label" for="seoKeywordsJapanese">' . Lang::getLang('seoKeywordsJapanese') . '</label>';
											$h .= '<div class="col-sm-6">';
												$h .= '<input type="text" id="seoKeywordsJapanese" name="seoKeywordsJapanese" class="form-control" placeholder="desired-url" value="' . $seo->seoKeywordsJapanese . '">';
											$h .= '</div>';
										$h .= '</div>';
										
										$h .= '<div class="form-group row">';
											$h .= '<label class="col-sm-4 col-form-label" for="seoDescriptionJapanese">' . Lang::getLang('seoDescriptionJapanese') . '</label>';
											$h .= '<div class="col-sm-6">';
												$h .= '<textarea id="seoDescriptionJapanese" name="seoDescriptionJapanese" class="form-control" placeholder="Description..." value="">' . $seo->seoDescriptionJapanese . '</textarea>';
											$h .= '</div>';
										$h .= '</div>';
										
										$h .= '<div class="form-group row">';
											$h .= '<div class="col-sm-6 offset-sm-4"><button type="submit" class="btn btn-primary float-right" name="submit">' . Lang::getLang($action) . '</button></div>';
										$h .= '</div>';
										
									$h .= '</form>';

								$h .= '</div>';
							$h .= '</div>';

					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';
		$h .= '</div>';

		return $h;

	}
	
	public function seoList() {
		
		$seoArray = SEO::seoArray($_SESSION['siteID']);
		$actionURL = '/' . Lang::languageUrlPrefix() . 'designer/seo/create/';
		
		$h = '<div id="perihelion_seo_list">';
			$h .= '<div class="container">';
				$h .= '<div class="row">';
					$h .= '<div class="col-sm-12">';

							$h .= '<div class="card" >';

								$h .= '<div class="card-header">';
									$h .= '<div class="card-title">';
										$h .= Lang::getLang('seo') . ' <a class="btn btn-secondary btn-sm float-right" href="' . $actionURL . '"><span class="fas fa-plus"></span></a>';
									$h .= '</div>';
								$h .= '</div>';

								$h .= '<div class="card-body">';

									$h .= '<div class="table-responsive">';
										$h .= '<table class="table table-bordered table-striped">';
											
												$h .= '<tr>';
													$h .= '<th class="text-center">' . Lang::getLang('ID') . '</th>';
													$h .= '<th>' . Lang::getLang('url') . '</th>';
													$h .= '<th class="text-center">' . Lang::getLang('creator') . '</th>';
													$h .= '<th class="text-center">' . Lang::getLang('created') . '</th>';
													$h .= '<th class="text-center">' . Lang::getLang('action') . '</th>';
												$h .= '</tr>';

												foreach($seoArray AS $seoID) {
													$seo = new SEO($seoID);
													$creator = new User($seo->seoSetByUserID);
													$h .= '<tr>';
														$h .= '<td class="text-center">' . $seoID . '</td>';
														$h .= '<td>' . $seo->seoURL . ' &rArr; ' . $seo->systemURL . '</td>';
														$h .= '<td class="text-center">' . $creator->getUserDisplayName() . '</td>';
														$h .= '<td class="text-center">' . $seo->seoSetDateTime . '</td>';
														$h .= '<td class="text-center"><a class="btn btn-secondary btn-sm" href="/' . Lang::languageUrlPrefix() . 'designer/seo/update/' . $seoID . '/">' . Lang::getLang('update') . '</a></td>';
													$h .= '</tr>';
												}

										$h .= '</table>';
									$h .= '</div>';
									
								$h .= '</div>';
							$h .= '</div>';

					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';
		$h .= '</div>';

		return $h;

	}

}

?>