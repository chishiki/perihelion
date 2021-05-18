<?php

class CarouselView {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray = array(), $inputArray = array(),  $errorArray = array()) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
	}
	
	public function carouselList() {
		
		$carouselArray = Carousel::carouselArray();

		$h = "<div id=\"perihelionCarousels\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";

							$h .= "<div class=\"card\" >";
								
								$h .= "<div class=\"card-header\">";
									$h .= "<div class=\"card-title\">";
										$h .= Lang::getLang('carousels');
										$h .= " <a class=\"btn btn-secondary btn-sm float-right\" href=\"/" . Lang::languageUrlPrefix() . "designer/carousels/create/\"><span class=\"fas fa-plus\"></span></a>";
									$h .= "</div>";
								$h .= "</div>";
								
								$h .= "<div class=\"card-body\">";

									$h .= "<div class=\"table-responsive\">";
										$h .= "<table class=\"table table-bordered table-striped\">";
											
											$h .= "<tr>";
												$h .= "<th>" . Lang::getLang('id') . "</th>";
												$h .= "<th>" . Lang::getLang('creator') . "</th>";
												$h .= "<th>" . Lang::getLang('created') . "</th>";
												$h .= "<th>" . Lang::getLang('title') . "</th>";
												$h .= "<th>" . Lang::getLang('object') . "</th>";
												$h .= "<th>" . Lang::getLang('objectID') . "</th>";
												$h .= "<th class=\"text-center\">" . Lang::getLang('action') . "</th>";
											$h .= "</tr>";

											foreach($carouselArray AS $carouselID) {
												
												$carousel = new Carousel($carouselID);
												$author = new User($carousel->carouselCreatedByUserID);
												
												$h .= "<tr>";
													$h .= "<td>" . $carouselID . "</td>";
													$h .= "<td>" . $author->getUserDisplayName() . "</td>";
													$h .= "<td>" . date('Y-m-d',strtotime($carousel->carouselCreationDateTime)) . "</td>";
													$h .= "<td>" . $carousel->title() . "</td>";
													$h .= "<td>" . $carousel->carouselObject . "</td>";
													$h .= "<td>" . $carousel->carouselObjectID . "</td>";
													$h .= "<td class=\"text-center\"><a class=\"btn btn-secondary btn-sm\" href=\"/" . Lang::languageUrlPrefix() . "designer/carousels/update/" . $carouselID . "/\">" . Lang::getLang('update') . "</a></td>";
												$h .= "</tr>";
												
											}

										$h .= "</table>";
									$h .= "</div>";

								$h .= "</div>";
							$h .= "</div>";

					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";

		return $h;

	}

	public function carouselForm($action, $carouselID = null) {
		
		$carousel = new Carousel($carouselID);
		$actionURL = '/' . Lang::languageUrlPrefix() . 'designer/carousels/' . $action . '/' . ($action=='update'?$carouselID.'/':'');
		$carouselPanels = CarouselPanel::carouselPanelArray($carouselID);
		$carouselPanelHeading = 'carouselSettings';

		if (!empty($this->inputArray)) {
			foreach ($this->inputArray AS $key => $value) { if (isset($carousel->$key)) { $carousel->$key = $value; } }
		}

		$h = '<form role="form" class="form-horizontal" action="' . $actionURL . '" method="post" enctype="multipart/form-data">';

		// CAROUSEL FORM

		$carouselFormHeader = Lang::getLang($carouselPanelHeading);
		$carouselForm = ($action=='update'&&$carouselID?'<input type="hidden" name="carouselID" value="' . $carouselID . '">':'');

		$fgtpTitleEnglish = new FormGroupTextParameters(array('col-12','col-md-6'),'carouselTitleEnglish','text','carousel_title_english','carouselTitleEnglish','',$carousel->carouselTitleEnglish,false,false);
		$fgtpSubtitleEnglish = new FormGroupTextParameters(array('col-12','col-md-6'),'carouselSubtitleEnglish','text','carousel_subtitle_english','carouselSubtitleEnglish','',$carousel->carouselSubtitleEnglish,false,false);

		$fgtpTitleJapanese = new FormGroupTextParameters(array('col-12','col-md-6'),'carouselTitleJapanese','text','carousel_title_japanese','carouselTitleJapanese','',$carousel->carouselTitleJapanese,false,false);
		$fgtpSubtitleJapanese = new FormGroupTextParameters(array('col-12','col-md-6'),'carouselSubtitleJapanese','text','carousel_subtitle_japanese','carouselSubtitleJapanese','',$carousel->carouselSubtitleJapanese,false,false);

		$fgtpObject = new FormGroupTextParameters(array('col-12','col-md-6'),'carouselObject','text','carousel_object','carouselObject','',$carousel->carouselObject,true,false);
		$fgtpObjectID = new FormGroupTextParameters(array('col-12','col-md-3'),'carouselObjectID','text','carousel_object_id','carouselObjectID','',$carousel->carouselObjectID,true,false);
		$fgciCarouselPublished = new FormGroupCheckboxInlineParameters(array('col-12','col-md-3'),'carouselPublished','carousel_published','carouselPublished',1,($carousel->carouselPublished?true:false),false);

		$carouselForm .= '
			<div class="form-row">' . FormElements::formGroupText($fgtpTitleEnglish) . FormElements::formGroupText($fgtpSubtitleEnglish) . '</div>
			<hr />
			<div class="form-row">' . FormElements::formGroupText($fgtpTitleJapanese) . FormElements::formGroupText($fgtpSubtitleJapanese) . '</div>
			<hr />
			<div class="form-row">' . FormElements::formGroupText($fgtpObject) . FormElements::formGroupText($fgtpObjectID) . FormElements::formGroupCheckboxInline($fgciCarouselPublished) . '</div>
		';
		$carouselFormCard = new CardView('perihelionCarouselForm', array('container'), '', array('col-12'), $carouselFormHeader, $carouselForm, false);
		$h .= $carouselFormCard->card();

		$carouselPanelFormHeader = Lang::getLang('carouselPanelManager');

		// CAROUSEL PANEL FORM TABS

		$carouselPanelItems = array();
		if (!empty($carouselPanels)) {
			foreach ($carouselPanels AS $carouselPanelID) {
				$cp = new CarouselPanel($carouselPanelID);
				$item = '<li class="nav-item">';
					$item .= '<a href="#panel' . $carouselPanelID . '" class="nav-link' . ($carouselPanels[0]==$carouselPanelID?" active":"") . '" role="tab" data-toggle="tab">';
						$item .= '#' . $carouselPanelID . ' [' . $cp->carouselPanelDisplayOrder . ']' . ($cp->carouselPanelPublished?' &#10004;':'');
					$item .= '</a>';
				$item .= '</li>';
				$carouselPanelItems[] = $item;
			}
		}
		$item = '<li class="nav-item">';
			$item .= '<a href="#addCarouselPanel" class="nav-link' . (empty($carouselPanels)?" active":"") . '" data-toggle="tab">';
				$item .= '<span class="fas fa-plus"></span> '. Lang::getLang('addNewPanels');
			$item .= '</a>';
		$item .= '</li>';
		$carouselPanelItems[] = $item;
		$carouselPanelTabs = '<ul class="nav nav-tabs" role="tablist">' . implode('',$carouselPanelItems) . '</ul>';

		// CAROUSEL PANEL FORM PANES
		$carouselPanelPaneItems = array();
		if (!empty($carouselPanels)) {

			foreach($carouselPanels as $carouselPanelID) {

				$cp = new CarouselPanel($carouselPanelID);

				$fgtpPanelTitleEnglish = new FormGroupTextParameters(array('col-12','col-md-4'),'carouselPanelTitleEnglish','text','carousel_panel_title_english_'.$carouselPanelID,'carouselPanelTitleEnglish['.$carouselPanelID.']','',$cp->carouselPanelTitleEnglish,false,false);
				$fgtpPanelSubtitleEnglish = new FormGroupTextParameters(array('col-12','col-md-4'),'carouselPanelSubtitleEnglish','text','carousel_panel_subtitle_english_'.$carouselPanelID,'carouselPanelSubtitleEnglish['.$carouselPanelID.']','',$cp->carouselPanelSubtitleEnglish,false,false);
				$fgtpPanelAltEnglish = new FormGroupTextParameters(array('col-12','col-md-4'),'carouselPanelAltEnglish','text','carousel_panel_alt_english_'.$carouselPanelID,'carouselPanelAltEnglish['.$carouselPanelID.']','',$cp->carouselPanelAltEnglish,false,false);
				$fgtpPanelUrlEnglish = new FormGroupTextParameters(array('col-12'),'carouselPanelUrlEnglish','text','carousel_panel_url_english_'.$carouselPanelID,'carouselPanelUrlEnglish['.$carouselPanelID.']','',$cp->carouselPanelUrlEnglish,false,false);

				$fgtpPanelTitleJapanese = new FormGroupTextParameters(array('col-12','col-md-4'),'carouselPanelTitleJapanese','text','carousel_panel_title_japanese_'.$carouselPanelID,'carouselPanelTitleJapanese['.$carouselPanelID.']','',$cp->carouselPanelTitleJapanese,false,false);
				$fgtpPanelSubtitleJapanese = new FormGroupTextParameters(array('col-12','col-md-4'),'carouselPanelSubtitleJapanese','text','carousel_panel_subtitle_japanese_'.$carouselPanelID,'carouselPanelSubtitleJapanese['.$carouselPanelID.']','',$cp->carouselPanelSubtitleJapanese,false,false);
				$fgtpPanelAltJapanese = new FormGroupTextParameters(array('col-12','col-md-4'),'carouselPanelAltJapanese','text','carousel_panel_alt_japanese_'.$carouselPanelID,'carouselPanelAltJapanese['.$carouselPanelID.']','',$cp->carouselPanelAltJapanese,false,false);
				$fgtpPanelUrlJapanese = new FormGroupTextParameters(array('col-12'),'carouselPanelUrlJapanese','text','carousel_panel_url_japanese_'.$carouselPanelID,'carouselPanelUrlJapanese['.$carouselPanelID.']','',$cp->carouselPanelUrlJapanese,false,false);

				$fgtpPanelDisplayOrder = new FormGroupNumberDropdownParameters(array('col-12','col-md-4'),'carouselPanelDisplayOrder','carousel_panel_display_order_'.$carouselPanelID,'carouselPanelDisplayOrder['.$carouselPanelID.']',$cp->carouselPanelDisplayOrder,0,100,null,'0');
				$fgciPanelPublished = new FormGroupCheckboxInlineParameters(array('col-12','col-md-4'),'carouselPanelPublished','carousel_panel_published_'.$carouselPanelID,'carouselPanelPublished[' . $carouselPanelID . ']',1,($cp->carouselPanelPublished?true:false),false);
				$fgciPanelDelete = new FormGroupCheckboxInlineParameters(array('col-12','col-md-4'),'deleteCarouselPanel','carousel_panel_delete_'.$carouselPanelID,'deleteCarouselPanel[' . $carouselPanelID . ']',1,false,false);

				$pane = '<input type="hidden" name="carouselPanelID[]" value="' . $carouselPanelID . '">';
				$pane .= '<div id="panel' . $carouselPanelID . '" class="tab-pane fade' . ($carouselPanels[0]==$carouselPanelID?' show active':'') . '" role="tabpanel">';
					$pane .= '<div class="form-group"><div class="col-12"><img src="/image/' . $cp->imageID . '/" class="img-fluid" style="margin:10px auto 10px auto;"></div></div>';
					$pane .= '<hr />';
					$pane .= '<div class="form-row">' . FormElements::formGroupText($fgtpPanelTitleEnglish) . FormElements::formGroupText($fgtpPanelSubtitleEnglish) . FormElements::formGroupText($fgtpPanelAltEnglish) . '</div>';
					$pane .= '<div class="form-row">' . FormElements::formGroupText($fgtpPanelUrlEnglish) . '</div>';
					$pane .= '<hr />';
					$pane .= '<div class="form-row">' . FormElements::formGroupText($fgtpPanelTitleJapanese) . FormElements::formGroupText($fgtpPanelSubtitleJapanese) . FormElements::formGroupText($fgtpPanelAltJapanese) . '</div>';
					$pane .= '<div class="form-row">' . FormElements::formGroupText($fgtpPanelUrlJapanese) . '</div>';
					$pane .= '<hr />';
					$pane .= '<div class="form-row">' . FormElements::formGroupNumberDropdown($fgtpPanelDisplayOrder) . FormElements::formGroupCheckboxInline($fgciPanelPublished) . FormElements::formGroupCheckboxInline($fgciPanelDelete) . '</div>';
				$pane .= '</div>';

				$carouselPanelPaneItems[] = $pane;

			}

		}
		$pane = '
			<div id="addCarouselPanel" class="tab-pane fade' . (empty($carouselPanels)?' show active':'') . '" role="tabpanel">
				<div class="form-row" style="margin-top:50px;margin-bottom:50px;">
					<div class="form-group col-12 col-sm-6 offset-sm-3">
						<label class="btn btn-secondary btn-lg btn-block btn-file">
							<span id="perihelionImageManagerSubmitButtonText">' . Lang::getLang('selectImage') . '</span> 
							<input type="file" id="perihelionImages" name="perihelionImages[]" style="display:none;" accept="image/*">
						</label>
					</div>
				</div>
			</div>
		';
		$carouselPanelPaneItems[] = $pane;
		$carouselPanelPanes = '<div class="tab-content" id="carousel-panel-panes">' . implode('', $carouselPanelPaneItems) . '</div>';
		$carouselPanelForm = $carouselPanelTabs . $carouselPanelPanes;

		$carouselFormCard = new CardView('perihelionCarouselPanelForm', array('container','mt-3'), '', array('col-12'), $carouselPanelFormHeader, $carouselPanelForm, false);
		$h .= $carouselFormCard->card();

		$h .= '
		<div class="container mt-3">
			<div class="form-row">
				<div class="form-group col-12 col-sm-4 offset-sm-8"><button type="submit" class="btn btn-primary btn-block" name="submit">' . Lang::getLang($action) . '</button></div>
			</div>
		</div>
		';

		$h .= '</form>';

		
		return $h;

	}

	public static function carousel($carouselObject, $carouselObjectID) {
		
		$h = "";
		$imageArray = Image::getObjectImageArray($carouselObject, $carouselObjectID);
		$carouselArray = array();
		foreach ($imageArray as $imageID) {
			$image = new Image($imageID);
			if ($image->imageDisplayInGallery) { $carouselArray[] = $imageID; }
		}
		$panelCount = count($carouselArray);
		
		if ($panelCount == 1) {
			
			$h .= "<div class=\"container\" style=\"margin-bottom:20px;\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-md-12 text-center\">";
						$h .= "<img src=\"/image/" . $carouselArray[0] . "/\">";
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
			
		} elseif ($panelCount > 1) {
			
			$h .= "<div class=\"container\" style=\"margin-bottom:20px;\">";
			
				$h .= "<div class=\"row\">";
				
					$h .= "<div class=\"col-md-12\">";
				
						$h .= "<div id=\"perihelion-carousel\" class=\"carousel slide\" data-ride=\"carousel\">";
						
							$h .= "<!-- Indicators -->";
							
							$h .= "<ol class=\"carousel-indicators  hidden-xs\">";
								for ($i = 0; $i < $panelCount; $i++) {
									$h .= "<li data-target=\"#perihelion-carousel\" data-slide-to=\"" . $i . "\"" . ($i==0?" class=\"active\"":"") . "></li>";
								}
							$h .= "</ol>";

							$h .= "<!-- Wrapper for slides -->";
							
							$h .= "<div class=\"carousel-inner\" role=\"listbox\">";
								for ($i = 0; $i < $panelCount; $i++) {
									$h .= "<div class=\"item" . ($i==0?" active":"") . "\">";
										$h .= "<img src=\"/image/" . $carouselArray[$i] . "/\">";
									$h .= "</div>";
								}
							$h .= "</div>";

							$h .= "<!-- Controls -->";
							
							$h .= "<a class=\"left carousel-control\" href=\"#perihelion-carousel\" role=\"button\" data-slide=\"prev\">";
								$h .= "<span class=\"fas fa-chevron-left\" aria-hidden=\"true\"></span>";
								$h .= "<span class=\"sr-only\">Previous</span>";
							$h .= "</a>";
							
							$h .= "<a class=\"right carousel-control\" href=\"#perihelion-carousel\" role=\"button\" data-slide=\"next\">";
								$h .= "<span class=\"fas fa-chevron-right\" aria-hidden=\"true\"></span>";
								$h .= "<span class=\"sr-only\">Next</span>";
							$h .= "</a>";
							
						$h .= "</div>";
						
					$h .= "</div>";
					
				$h .= "</div>";
				
			$h .= "</div>";

		}
		
		return $h;

	}

	public static function displayCarousel($carouselID) {

		$carousel = new Carousel($carouselID);
		$panels = CarouselPanel::carouselPanelArray($carouselID, true);
		$numberOfPanels = count($panels);
		
		$h = "<div class=\"";
			// if () { $h .= "hidden-xs"; }
			// if () { $h .= "carouselContainer"; }
		$h .= "\"> <!-- START CAROUSEL CONTAINER -->";

			$h .= "<div class=\"container\">";
		
				$h .= "<div id=\"myCarousel\" class=\"carousel slide\" data-ride=\"carousel\">";
					
					if ($numberOfPanels >= 2) { 
						$h .= "<ol class=\"carousel-indicators hidden-xs hidden-sm\">";
							$h .= "<li data-target=\"#myCarousel\" data-slide-to=\"0\" class=\"active\"></li>";
							for ($i = 1; $i < $numberOfPanels; $i++) { $h .= "<li data-target=\"#myCarousel\" data-slide-to=\"" . $i . "\"></li>"; }
						$h .= "</ol>";
					}
					
					$h .= "<div class=\"carousel-inner\">";
					
						$i = 0;
						foreach ($panels AS $carouselPanelID) {
							
							$cp = new CarouselPanel($carouselPanelID);
							
							$imageID = $cp->imageID;
							$alt = $cp->alt();
							$title = $cp->title();
							$subtitle = $cp->subtitle();
							$url = $cp->url();

							$h .= "<div class=\"" . ($i==0?"active ":"") . "item\">";
								
								if ($url != '') { $h .= "<a href=\"" . $url  . "\">"; }
									$h .= "<img src=\"/image/" . $imageID . "/\" style=\"width:100%;\" class=\"full\" alt=\"" . $alt . "\" />";
								if ($url != '') { $h .= "</a>"; }

								if ($title && $subtitle) {
									$h .= '<div class="carousel-caption">';
										$h .= '<h3 class="carouselPanelTitle">' . $title . '</h3>';
										$h .= '<h3 class="carouselPanelSubtitle">' . $subtitle . '</h3>';
									$h .= '</div>';
								}
								
							$h .= '</div>';
							$i = $i + 1;
						}
					$h .= '</div>';
					
					if ($numberOfPanels >= 2) { 
						$h .= '<!-- Carousel nav -->';
						$h .= '<a class="carousel-control left" href="#myCarousel" data-slide="prev"><span class="fas fa-chevron-left"></span></a>';
						$h .= '<a class="carousel-control right" href="#myCarousel" data-slide="next"><span class="fas fa-chevron-right"></span></a>';
					}
					
				$h .= '</div> <!-- END .carousel-inner -->';
				
			$h .= "</div> <!-- END #myCarousel -->";

		$h .= "</div> <!-- END CAROUSEL CONTAINER -->";
		
		return $h;

	}

}


?>