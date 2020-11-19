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
		
		if ($action == 'update' && $carouselID) {
			$carousel = new Carousel($carouselID);
			$actionURL = '/' . Lang::languageUrlPrefix() . 'designer/carousels/update/' . $carouselID . '/';
			$carouselPanels = CarouselPanel::carouselPanelArray($carouselID);
			$carouselPanelHeading = 'updateCarousel';
		} else {
			$carousel = new Carousel();
			$actionURL = '/' . Lang::languageUrlPrefix() . 'designer/carousels/create/';
			$carouselPanels = array();
			$carouselPanelHeading = 'createCarousel';
		}
		if (!empty($this->inputArray)) {
			foreach ($this->inputArray AS $key => $value) {
				if (isset($carousel->$key)) { $carousel->$key = $value; }
			}
		}
		
		$h = "<div id=\"perihelionCarouselForm\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";

						$h .= "<div class=\"card\" >";
							
							$h .= "<div class=\"card-header\">";
								$h .= "<div class=\"card-title\">";
									$h .= Lang::getLang($carouselPanelHeading);
								$h .= "</div>";
							$h .= "</div>";

							$h .= "<div class=\"card-body\">";
								
								$h .= '<form role="form" class="form-horizontal" action="' . $actionURL . '" method="post" enctype="multipart/form-data">';

									if ($action == 'update' && $carouselID) { $h .= '<input type="hidden" name="carouselID" value="' . $carouselID . '">'; }

									$h .= '<h3 style="margin-top:0px;">Carousel</h3>';

									$h .= '<div class="card">

										<div class="row">
											<div class="col-md-6">
												<div class="form-group row">
													<label for="carouselTitleEnglish" class="col-form-label col-md-4">Title (English)</label>
													<div class="col-md-8"><input type="text" name="carouselTitleEnglish" class="form-control" id="carouselTitleEnglish" placeholder="Title (English)" value="' . $carousel->carouselTitleEnglish . '"></div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group row">
													<label for="carouselSubtitleEnglish" class="col-form-label col-md-4">Subtitle (English)</label>
													<div class="col-md-8"><input type="text" name="carouselSubtitleEnglish" class="form-control" id="carouselSubtitleEnglish" placeholder="Subtitle (English)" value="' . $carousel->carouselSubtitleEnglish . '"></div>
												</div>
											</div>
										</div>
										
										<hr />
										
										<div class="row">
											<div class="col-md-6">
												<div class="form-group row">
													<label for="carouselTitleJapanese" class="col-form-label col-md-4">Title (Japanese)</label>
													<div class="col-md-8"><input type="text" name="carouselTitleJapanese" class="form-control" id="carouselTitleJapanese" placeholder="Title (Japanese)" value="' . $carousel->carouselTitleJapanese . '"></div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group row">
													<label for="carouselSubtitleJapanese" class="col-form-label col-md-4">Subtitle (Japanese)</label>
													<div class="col-md-8"><input type="text" name="carouselSubtitleJapanese" class="form-control" id="carouselSubtitleJapanese" placeholder="Subtitle (Japanese)" value="' . $carousel->carouselSubtitleJapanese . '"></div>
												</div>
											</div>
										</div>
										
										<hr />
										
										<div class="row">
											<div class="col-md-6">
												<div class="form-group row">
													<label for="carouselObject" class="col-form-label col-md-4">Object</label>
													<div class="col-md-8"><input type="text" name="carouselObject" class="form-control" id="carouselObject" placeholder="Object" value="' . $carousel->carouselObject . '" readonly></div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group row">
													<label for="carouselObjectID" class="col-form-label col-md-3">ID</label>
													<div class="col-md-9"><input type="text" name="carouselObjectID" class="form-control" id="carouselObjectID" placeholder="ID" value="' . $carousel->carouselObjectID . '" readonly></div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="checkbox">
													<label for="carouselPublished" class="col-form-label">
													<input type="checkbox" name="carouselPublished" id="carouselPublished" value="1" ' . ($carousel->carouselPublished?' checked':'') . '>Published</label>
												</div>
											</div>
										</div>
		
									</div>';
		
									$h .= '<h3 style="margin-top:0px;">Panels</h3>';

									$h .= '<div class="card">';

										$h .= '<ul class="nav nav-tabs" role="tablist" style="margin-bottom:10px;">';
										
											if (!empty($carouselPanels)) {
												foreach ($carouselPanels AS $carouselPanelID) {
													$cp = new CarouselPanel($carouselPanelID);
													$h .= '<li role="presentation" class="nav-item">';
														$h .= '<a href="#panel' . $carouselPanelID . '" aria-controls="card' . $carouselPanelID . '" class="nav-link' . ($carouselPanels[0]==$carouselPanelID?" active":"") . '" role="tab" data-toggle="tab">';
															$h .= '#' . $carouselPanelID . ' ';
															$h .= '[' . $cp->carouselPanelDisplayOrder . ']';
															if ($cp->carouselPanelPublished) { $h .= ' &#10004;'; }
														$h .= '</a>';
													$h .= '</li>';
												}
											}
											
											$h .= '<li role="presentation" class="nav-item">';
												$h .= '<a href="#addCarouselPanel" class="nav-link' . (empty($carouselPanels)?" active":"") . '" data-toggle="tab">';
													$h .= '<span class="fas fa-plus"></span> ';
													$h .= Lang::getLang('addNewPanels');
												$h .= '</a>';
											$h .= '</li>';
											
										$h .= '</ul>';

										$h .= '<div class="tab-content">';
											
											
											if (!empty($carouselPanels)) {
												
												foreach ($carouselPanels AS $carouselPanelID) {
													
													$cp = new CarouselPanel($carouselPanelID); 
												
													$h .= '<input type="hidden" name="carouselPanelID[]" value="' . $carouselPanelID . '">';
												
													$h .= '<div role="tabpanel" class="tab-pane' . ($carouselPanels[0]==$carouselPanelID?" active":"") . '" id="card' . $carouselPanelID . '">';

														$h.= '
														
														<div class="row">
															<div class="col-sm-4">
																<div class="form-group row">
																	<label for="carouselPanelTitleEnglish[' . $carouselPanelID . ']" class="col-form-label col-sm-4">Title (English)</label>
																	<div class="col-sm-8"><input type="text" name="carouselPanelTitleEnglish[' . $carouselPanelID . ']" class="form-control" id="carouselPanelTitleEnglish-' . $carouselPanelID . '" placeholder="Title (English)" value="' . $cp->carouselPanelTitleEnglish . '"></div>
																</div>
															</div>
															<div class="col-sm-4">
																<div class="form-group row">
																	<label for="carouselPanelSubtitleEnglish[' . $carouselPanelID . ']" class="col-form-label col-sm-4">Subtitle (English)</label>
																	<div class="col-sm-8"><input type="text" name="carouselPanelSubtitleEnglish[' . $carouselPanelID . ']" class="form-control" id="carouselPanelSubtitleEnglish[' . $carouselPanelID . ']" placeholder="Subtitle (English)" value="' . $cp->carouselPanelSubtitleEnglish . '"></div>
																</div>
															</div>
															<div class="col-sm-4">
																<div class="form-group row">
																	<label for="carouselPanelAltEnglish[' . $carouselPanelID . ']" class="col-form-label col-sm-4">Alternate Text</label>
																	<div class="col-sm-8"><input type="text" name="carouselPanelAltEnglish[' . $carouselPanelID . ']" class="form-control" id="carouselPanelAltEnglish[' . $carouselPanelID . ']" placeholder="Alternate Text" value="' . $cp->carouselPanelAltEnglish . '"></div>
																</div>
															</div>
														</div>
														
														';
												
												
														$h .= '
														
														<div class="row">
															<div class="col-sm-4">
																<div class="form-group row">
																	<label for="carouselPanelTitleJapanese[' . $carouselPanelID . ']" class="col-form-label col-sm-4">Title (Japanese)</label>
																	<div class="col-sm-8"><input type="text" name="carouselPanelTitleJapanese[' . $carouselPanelID . ']" class="form-control" id="carouselPanelTitleJapanese[' . $carouselPanelID . ']" placeholder="Title (Japanese)" value="' . $cp->carouselPanelTitleJapanese . '"></div>
																</div>
															</div>
															<div class="col-sm-4">
																<div class="form-group row">
																	<label for="carouselPanelSubtitleJapanese[' . $carouselPanelID . ']" class="col-form-label col-sm-4">Subtitle (Japanese)</label>
																	<div class="col-sm-8">
																		<input type="text" name="carouselPanelSubtitleJapanese[' . $carouselPanelID . ']" class="form-control" id="carouselPanelSubtitleJapanese[' . $carouselPanelID . ']" placeholder="Subtitle (Japanese)" value="' . $cp->carouselPanelSubtitleJapanese . '">
																	</div>
																</div>
															</div>
															<div class="col-sm-4">
																<div class="form-group row">
																	<label for="carouselPanelAltJapanese[' . $carouselPanelID . ']" class="col-form-label col-sm-4">Alternate Text (Japanese)</label>
																	<div class="col-sm-8">
																		<input type="text" name="carouselPanelAltJapanese[' . $carouselPanelID . ']" class="form-control" id="carouselPanelAltJapanese-' . $carouselPanelID . '" placeholder="Alternate Text (Japanese)" value="' . $cp->carouselPanelAltJapanese . '">
																	</div>
																</div>
															</div>
														</div>

														';
												
												
														$h .= '
														
														<div class="row">
														
															<div class="col-sm-6">
																<div class="form-group row">
																	<label class="col-form-label col-sm-4" for="carouselPanelUrlEnglish[' . $carouselPanelID . ']">URL English</label>
																	<div class="col-sm-8">
																		<input type="text" id="carouselPanelUrlEnglish[' . $carouselPanelID . ']" name="carouselPanelUrlEnglish[' . $carouselPanelID . ']" class="form-control" id="carouselPanelUrlEnglish[' . $carouselPanelID . ']" placeholder="URL English" value="' . $cp->carouselPanelUrlEnglish . '">
																	</div>
																</div>
															</div>
															
															<div class="col-sm-6">
																<div class="form-group row">
																	<label class="col-sm-4 col-form-label" for="carouselPanelUrlJapanese[' . $carouselPanelID . ']">URL Japanese</label>
																	<div class="col-sm-8">
																		<input type="text" id="carouselPanelUrlJapanese[' . $carouselPanelID . ']" name="carouselPanelUrlJapanese[' . $carouselPanelID . ']" class="form-control" id="carouselPanelUrlJapanese[' . $carouselPanelID . ']" placeholder="URL Japanese" value="' . $cp->carouselPanelUrlJapanese . '">
																	</div>
																</div>
															</div>
																
														</div>

														';
												
												
														$h .= '
														
														<div class="row">
														
															<div class="col-sm-4">
																<div class="form-group row">
																	<label class="col-form-label col-sm-6" for="carouselPanelDisplayOrder[' . $carouselPanelID . ']">Display Order</label>
																	<div class="col-sm-6">';
																	
																		$displayOrderSelectName = 'carouselPanelDisplayOrder[' . $carouselPanelID . ']';
																		$h .= FormElements::numberDropdown($displayOrderSelectName, $cp->carouselPanelDisplayOrder, 0, 100, 'form-control form-control-sm', '0');
																	
																	$h .= '</div>
																</div>
															</div>
																
															<div class="col-sm-4">
																<div class="form-group row">
																	<label class="col-sm-8 col-form-label" for="carouselPanelPublished[' . $carouselPanelID . ']">Panel Published</label>
																	<div class="col-sm-4">
																		<input id="carouselPanelPublished[' . $carouselPanelID . ']" name="carouselPanelPublished[' . $carouselPanelID . ']" type="checkbox" value="1"' . ($cp->carouselPanelPublished?" checked":"") . '>
																	</div>
																</div>
															</div>

															<div class="col-sm-4">
																<div class="form-group row">
																	<label class="col-sm-8 col-form-label" for="deleteCarouselPanel[' . $carouselPanelID . ']">Delete This Panel</label>
																	<div class="col-sm-4">
																		<input id="deleteCarouselPanel[' . $carouselPanelID . ']" name="deleteCarouselPanel[' . $carouselPanelID . ']" type="checkbox" value="' . $carouselPanelID . '">
																	</div>
																</div>
															</div>

														</div>

														';
												
												
														$h .= '
														
														<div class="row">
															<div class="col-sm-12">
																<img src="/image/' . $cp->imageID . '/" class="img-fluid" style="margin:10px auto 10px auto;">
															</div>
														</div>
														
														';
												

													$h .= '</div> <!-- #panel' . $carouselPanelID . ' .tab-pane -->';
													
												}
												
											}
									
											$h .= '
											<div role="tabpanel" class="tab-pane' . (empty($carouselPanels)?" active":"") . '" id="addCarouselPanel">

												<div class="form-group row" style="margin-top:50px;margin-bottom:50px;">
													
													<div class="col-sm-6 offset-sm-3">
														<label class="btn btn-secondary btn-lg btn-block btn-file">
															<span id="perihelionImageManagerSubmitButtonText">' . Lang::getLang('selectImage') . '</span> 
															<input type="file" id="perihelionImages" name="perihelionImages[]" style="display:none;" accept="image/*">
														</label>
													</div>

												</div>
	
											</div> <!-- #addCarouselPanel.tab-pane -->';

										$h .= '</div>'; // .tab-content
										
									$h .= '</div>'; // .card	
					
									$h .= '
									<div class="form-group row">
										<div class="col-sm-4 offset-sm-8"><button type="submit" class="btn btn-primary btn-block" name="submit">' . Lang::getLang($action) . '</button></div>
									</div>
									';

								$h .= '</form>';
						
							$h .= "</div>"; // .card-body
						$h .= "</div>"; // .panel
						
					$h .= "</div>"; // #perihelionCarouselsCol
				$h .= "</div>"; // #perihelionCarouselsRow
			$h .= "</div>"; // #perihelionCarouselsContainer
		$h .= "</div>"; // #perihelionCarousels
		
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