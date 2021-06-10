<?php

final class ImageView {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray = array(), $inputArray = array(),  $errorArray = array()) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
	}
	
	public function imageList() {
		
		$imageArray = Image::imageArray();
		
		$h = "<div id=\"perihelionImages\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";

							$h .= "<div class=\"card\" >";
								$h .= "<div class=\"card-header\"><div class=\"card-title\">" . Lang::getLang('images') . "</div></div>";
								$h .= "<div class=\"card-body\">";

									$h .= "<div class=\"list-group\">";
										foreach($imageArray AS $imageID) {
											$image = new Image($imageID);
											$h .= "<a class=\"list-group-item\" href=\"/manager/images/update/" . $imageID . "/\">" . $image->imageOriginalName . "</a>";
										}
									$h .= "</div>";

								$h .= "</div>";
							$h .= "</div>";

					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";

		return $h;

	}

	public function imageManager($imageObject, $imageObjectID = null, $currentPage = 1) {

		switch ($imageObject) {

			case ('Content'):
				
				$content = new Content($imageObjectID);
				$panelTitle = Lang::getLang('updateContent') . " - " . $content->title() . " - " . Lang::getLang('images');
				$hiddenFieldName = 'contentID';
				$images = Image::getObjectImageArray('Content',$imageObjectID);
				$baseFormURL = "/" . Lang::languageUrlPrefix() . "designer/content/update/" . $imageObjectID . "/images/";
				break;

			case ('Project'):
			
				$project = new Project($imageObjectID);
				$panelTitle = Lang::getLang('updateProject') . " - " . $project->projectName() . " - " . Lang::getLang('images');
				$hiddenFieldName = 'projectID';
				$images = Image::getObjectImageArray('Project',$imageObjectID);
				$baseFormURL = "/" . Lang::languageUrlPrefix() . "staff/projects/update/" . $imageObjectID . "/images/";
				break;

			case ('Site'):
			
				$site = new Site($imageObjectID);
				$panelTitle = Lang::getLang('images');
				$hiddenFieldName = 'siteID';
				$images = Image::imageArray();
				$baseFormURL = "/" . Lang::languageUrlPrefix() . "designer/images/";
				break;

			case ('Theme'):
			
				$theme = new Theme($imageObjectID);
				$panelTitle = Lang::getLang('updateTheme') . " - " . $theme->themeName . " - " . Lang::getLang('images');
				$hiddenFieldName = 'themeID';
				$images = Image::getObjectImageArray('Theme',$imageObjectID);
				$baseFormURL = "/" . Lang::languageUrlPrefix() . "designer/themes/update/" . $imageObjectID . "/images/";
				break;
				
			case ('User'):
			
				$user = new User($imageObjectID);
				$panelTitle = Lang::getLang('updateUser') . " - " . $project->userDisplayName() . " - " . Lang::getLang('images');
				$hiddenFieldName = 'userID';
				$images = Image::getObjectImageArray('User',$imageObjectID);
				$baseFormURL = "/" . Lang::languageUrlPrefix() . "manager/users/update/" . $imageObjectID . "/images/";
				break;
			
		}

		// pagination
		$count = count($images);
		$perPage = 25;
		$numberOfPages = ceil($count/$perPage);
		if ($currentPage > $numberOfPages) { $currentPage = 1; }
		$startAt = ($currentPage - 1) * 25;
		$limit = "$startAt, $perPage";

		if ($imageObject == 'Site') {
			$images = Image::imageArray($limit);
		} else {
			$images = Image::getObjectImageArray($imageObject,$imageObjectID,$limit);
		}

		$h = "<div id=\"perihelionImageManager\">";
			$h .= "<div class=\"container\">";
				$h .= "<div class=\"row\">";
					$h .= "<div class=\"col-sm-12\">";
						$h .= "<div class=\"card\" >";
						
							$h .= "<div class=\"card-header\">";
								$h .= "<div class=\"card-title clearfix\">" . $panelTitle . "</div>";
							$h .= "</div>";
							
							$h .= "<div class=\"card-body\">";
								
								$h .= "<div class=\"row\">";
									$h .= "<div class=\"col-md-12 text-right\">";
										$h .= PaginationView::paginate($numberOfPages,$currentPage,'/designer/images/');
									$h .= "</div>";
								$h .= "</div>";
								
								$h .= "<form id=\"perihelionImageManagerForm\" name=\"perihelionImageManagerForm\"  method=\"post\" action=\"" . $baseFormURL . "\" enctype=\"multipart/form-data\">";

									$h .= "<input type=\"hidden\" name=\"" . $hiddenFieldName . "\" value=\"" . $imageObjectID  . "\">";

									if ($imageObject == 'Property') { $h .= StaffView::propertyButtonGroup($imageObjectID,'images'); }

									$h .= "<div class=\"form-group row\">";
									
										$h .= "<div class=\"col-sm-2 offset-sm-8\">";
										
											$h .= "<label class=\"btn btn-secondary btn-block btn-file\">";
												$h .= "<span id=\"perihelionImageManagerSubmitButtonText\">" . Lang::getLang('Select Images') . "</span> ";
												$h .= "<input type=\"file\" id=\"perihelionImages\" name=\"perihelionImages[]\" style=\"display:none;\" accept=\"image/*\" multiple>";
											$h .= "</label>";
											
										$h .= "</div>";

										$h .= "<div class=\"col-sm-2\">";
										
											$h .= "<button type=\"submit\" name=\"perihelionImageManagerSubmit\" id=\"perihelionImageManagerSubmit\" class=\"btn btn-primary btn-block\" disabled=\"true\">";
												$h .= "<span id=\"perihelionImageManagerSubmitIcon\" class=\"fas fa-upload\"></span> ";
												$h .= "<span id=\"perihelionImageManagerSubmitText\">" . Lang::getLang('uploadImages') . "</span>";
											$h .= "</button>";
											
										$h .= "</div>";
										
									$h .= "</div>";

								$h .= "</form>";

								if (!empty($images)) {
									$h .= "<div class=\"row\">";
										$h .= "<div class=\"col-12\">";
											$h .= "<div class=\"table-responsive\">";
												$h .= "<table class=\"table table-striped\">";
												
												
													$h .= "<tr>";
														$h .= "<th>" . Lang::getLang('image') . "</th>";
														$h .= "<th class=\"hidden-xs\">" . Lang::getLang('url') . "</th>";
														$h .= "<th class=\"hidden-xs hidden-sm\">" . Lang::getLang('originalName') . "</th>";
														$h .= "<th class=\"hidden-xs\">" . Lang::getLang('date') . "</th>";
														$h .= "<th class=\"text-right hidden-xs\">" . Lang::getLang('size') . "</th>";
														if ($imageObject == 'Site') {
															$h .= "<th class=\"text-center hidden-xs\">" . Lang::getLang('object') . "</th>";
														}
														if ($imageObject != 'Site') {
															$h .= "<th class=\"text-center\">" . Lang::getLang('carousel') . "</th>";
															$h .= "<th class=\"text-center\">" . Lang::getLang('order') . "</th>";
															$h .= "<th class=\"text-center\">" . Lang::getLang('main') . "</th>";
														}
														$h .= "<th class=\"text-center\">" . Lang::getLang('action') . "</th>";
													$h .= "</tr>";
												
													foreach ($images as $imageID) {
														
														$image = new Image($imageID);
														
														
	
															$remove_from_carousel = $baseFormURL . "remove-from-carousel/" . $imageID . "/";
															$add_to_carousel = $baseFormURL . "add-to-carousel/" . $imageID . "/";
															$make_main_image = $baseFormURL . "make-main-image/" . $imageID . "/";
															$delete = $baseFormURL . "delete/" . $imageID . "/";
											
															if ($image->imageSize < 1024) { $size = 1; } else { $size = $image->imageSize / 1024;}
														
															$h .= "<tr>";
																$h .= "<td><a href=\"/image/" . $image->imageID . "/\" target=\"blank\" style=\"vertical-align:middle;\"><img src=\"/image/" . $image->imageID . "/90/\" style=\"width:90px;\"></a></td>";
																$h .= "<td class=\"hidden-xs\" style=\"vertical-align:middle;\"><a href=\"/image/" . $image->imageID . "/\" target=\"blank\" style=\"text-transform:lowercase;\">/image/" . $image->imageID . "/</a></td>";
																$h .= "<td class=\"hidden-xs hidden-sm\" style=\"vertical-align:middle;\">" . $image->imageOriginalName . "</td>";
																$h .= "<td class=\"hidden-xs\" style=\"vertical-align:middle;\">" . date('Y-m-d',strtotime($image->imageSubmissionDateTime)) . "</td>";
																$h .= "<td class=\"text-right hidden-xs\" style=\"vertical-align:middle;\">" . number_format($size) . "K</td>";

																if ($imageObject == 'Site') {
																	$h .= "<td class=\"hidden-xs\" style=\"vertical-align:middle;\">";
																		if ($image->imageObject == 'Property') {
																			$p = new Property($image->imageObjectID);
																			$h .= "<a href=\"/" . Lang::languageUrlPrefix() . "staff/properties/update/" . $image->imageObjectID . "/images/\">" . $p->name() . "</a>";
																		} else {
																			$h .= $image->imageObject . " [" . $image->imageObjectID . "]";
																		}
																	$h .= "</td>";
																}
																
																if ($imageObject != 'Site') {
																	
																	$h .= "<td class=\"text-center\" style=\"vertical-align:middle;\">";
																		
																		if ($image->imageDisplayInGallery) {
																			
																			$h .= "<button class=\"btn btn-success btn-sm disabled\"><span class=\"fas fa-plus\"></span></button>";
																			$h .= "<a class=\"btn btn-secondary btn-sm\" href=\"" . $remove_from_carousel . "\"><span class=\"fas fa-minus\"></span></a>";
																			
																		} else {
																			
																			$h .= "<a class=\"btn btn-secondary btn-sm\" href=\"" . $add_to_carousel . "\"><span class=\"fas fa-plus\"></span></a>";
																			$h .= "<button class=\"btn btn-warning btn-sm disabled\"><span class=\"fas fa-minus\"></span></button>";
																			
																		}
																		

																	$h .= "</td>";
																	
																	$h .= "<td class=\"text-center\" style=\"vertical-align:middle;\">";
																		$h .= "<select name=\"imageDisplayOrder\" onchange=\"this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);\">";
																			for ($i = 0; $i <= 100; $i++) {
																				$imageDisplayOrderURL = $baseFormURL . "set-display-order/" . $imageID . "/$i/";
																				$h .= "<option value=\"" . $imageDisplayOrderURL . "\"" . ($i==$image->imageDisplayOrder?" selected":"") . ">" . $i . "</option>";
																			}
																		$h .= "</select>";
																	$h .= "</td>";

																	$h .= "<td class=\"text-center\" style=\"vertical-align:middle;\">";
																		if ($image->imageDisplayClassification != 'mainImage') {
																			$h .= "<a class=\"btn btn-primary btn-sm\" href=\"" . $make_main_image . "\"><span class=\"fas fa-check\" style=\"color:#fff;\"></span></a>";
																		} else {
																			$h .= "<span class=\"fas fa-check\" style=\"color:#000;\"></span>";
																		}
																	$h .= "</td>";

																}
																
																$h .= "<td class=\"text-center\" style=\"vertical-align:middle;\">";
																	$filter = array('index','Property','Carousel');
																	if (!in_array($image->imageObject,$filter)) {
																		$h .= "<a class=\"btn btn-danger btn-sm\" href=\"" . $delete . "\"><span class=\"fas fa-trash-alt\" style=\"color:#fff;\"></span></a>";
																	}
																$h .= "</td>";
		
															$h .= "</tr>";
															
														
													}
												
												$h .= "</table>";
											$h .= "</div>";
										$h .= "</div>";
									$h .= "</div>";
								}
								
								$h .= "<div class=\"row\">";
									$h .= "<div class=\"col-md-12 text-right\">";
										$h .= PaginationView::paginate($numberOfPages,$currentPage,'/designer/images/');
									$h .= "</div>";
								$h .= "</div>";

							$h .= "</div>"; // .card-body
						$h .= "</div>"; // .panel
					$h .= "</div>";
				$h .= "</div>";
			$h .= "</div>";
		$h .= "</div>";

		return $h;

	}
	
	public static function formImageManager($imageObject, $imageObjectID) {

		$imageArray = Image::getObjectImageArray($imageObject, $imageObjectID);
		
		$h = "<div class=\"row\"><label class=\"col-form-label col-sm-12\">" . Lang::getLang('images') . "</label></div>";
		
		$h .= "<div id=\"list\" class=\"row\">";
		
			
		
			foreach ($imageArray AS $imageID) {
				
				$image = new Image($imageID);
				$imageDisplayInGallery = $image->imageDisplayInGallery;
				$imageDisplayOrder = $image->imageDisplayOrder;
				
				$h .= "<div class=\"item col-6 col-sm-4 col-md-3\">";
					 $h .= "<div style=\"border:1px solid #ccc;padding:3px;margin-bottom:10px;\">";
						
						$h .= "<img class=\"img-thumbnail\" src=\"/image/" . $imageID . "/\">";

						$h .= "<div class=\"text-" . ($image->imageDisplayClassification=="mainImage"?"success":"warning") . "\">";
							$h .= '<input type="radio" name="mainImageID" value="' . $imageID . '"';
								if ($image->imageDisplayClassification == 'mainImage') { $h .= ' checked="checked"'; }
							$h .= '>&nbsp;' . Lang::getLang('mainImage');
						$h .= "</div><hr style=\"margin:0px;\" />";
						
						$h .= '<div class="text-' . ($image->imageDisplayInGallery?'success':'warning') . '">';
							$h .= '<input type="checkbox" name="displayImageInGallery[]" value="' . $imageID . '"' . ($image->imageDisplayInGallery?' checked':'') . '>&nbsp;' . Lang::getLang('displayInGallery');
						$h .= "</div><hr style=\"margin:0px;\" />";
						
						$h .= "<div class=\"text-info\" style=\"padding:3px;\">";
							$h .= FormElements::numberDropdown('imageDisplayOrder[' . $imageID . ']', $image->imageDisplayOrder, 0, 100, 'form-control', 'displayOrder');
						$h .= "</div><hr style=\"margin:0px;\" />";
						
						$h .= '<div class="text-danger">';
							$h .= '<input type="checkbox" name="deleteImage[]" value="' . $imageID . '">&nbsp;' . Lang::getLang('deleteImage');
						$h .= '</div>';
						
					$h .= '</div>';
				$h .= '</div>';

			}
			

		$h .= "</div>";

		return $h;

	}
	
	public static function freshPerihelionImageFormGroup() {
	
		$h = "<div class=\"form-group row\">";
									
			$h .= "<div class=\"col-sm-2 offset-sm-8\">";
			
				$h .= "<label class=\"btn btn-secondary btn-block btn-file\">";
					$h .= "<span id=\"freshPerihelionImagesSelectPromptText\">" . Lang::getLang('Select Images') . "</span> ";
					$h .= "<input type=\"file\" id=\"freshPerihelionImages\" name=\"freshPerihelionImages[]\" style=\"display:none;\" accept=\"image/*\" multiple>";
				$h .= "</label>";
				
			$h .= "</div>";

			$h .= "<div class=\"col-sm-2\">";
			
				$h .= "<button type=\"submit\" name=\"freshPerihelionImagesSubmit\" id=\"freshPerihelionImagesSubmit\" class=\"btn btn-primary btn-block\" disabled=\"true\">";
					$h .= "<span id=\"freshPerihelionImagesSubmitIcon\" class=\"fas fa-upload\"></span> ";
					$h .= "<span id=\"freshPerihelionImagesSubmitText\">" . Lang::getLang('uploadImages') . "</span>";
				$h .= "</button>";
				
			$h .= "</div>";
			
		$h .= "</div>";
	
		return $h;
		
	}

	public function newImageManager(NewImageViewParameters $arg) {

		$manager = '';
		if ($arg->includeForm) { $manager .= $this->newImageForm($arg); }
		if ($arg->includeList) { $manager .= $this->newImageList($arg); }
		$cardView = new CardView('new_image_manager_card', $arg->cardContainer, $arg->breadcrumbs, array('col-12'), $arg->cardHeader, $arg->navtabs.$manager, false);
		return $cardView->card();

	}

	public function newImageForm(NewImageViewParameters $arg) {

		$imageForm = '

			<div id="new_image_form_container" class="' . implode(' ', $arg->formContainerDivClasses) . '">
				
				<form id="new_image_form" method="post" action="' . $arg->formURL . '" enctype="multipart/form-data">
				
					<input type="hidden" name="imageObject" value="' . ($arg->imageObject?$arg->imageObject:'') . '">
					<input type="hidden" name="imageObjectID" value="' . ($arg->imageObjectID?$arg->imageObjectID:0) . '">
				
					<div class="form-group row">
				
						<div id="new_image_select" class="' . implode(' ', $arg->formSelectDivClasses) . '">
				
							<label id="new_image_select_label" class="btn btn-secondary btn-block btn-file">
								<span id="new_image_select_prompt">' . Lang::getLang('selectImages') . '</span> 
								<input id="new_image_select_input" class="d-none" type="file" name="images-to-upload[]" accept="image/*"' . ($arg->allowCapture?' capture="camera"':'') . ($arg->allowMultiple?' multiple':'') . '>
							</label>
				
						</div>
				
						<div id="new_image_submit" class="' . implode(' ', $arg->formSubmitDivClasses) . '">
				
							<button type="submit" id="new_image_submit_button" name="submitted-images" class="btn btn-primary btn-block" disabled="true">
								<span id="new_image_submit_icon" class="fas fa-upload"></span> 
								<span id="new_image_submit_text">' . Lang::getLang('uploadImages') . '</span>
							</button>
				
						</div>
						
					</div>
				
				</form>
			
			</div>
		
		';

		return $imageForm;

	}

	public function newImageList(NewImageViewParameters $arg) {

		// pagination
		/*
		$count = count($images);
		$perPage = 25;
		$numberOfPages = ceil($count/$perPage);
		if ($currentPage > $numberOfPages) { $currentPage = 1; }
		$startAt = ($currentPage - 1) * 25;
		$limit = "$startAt, $perPage";

		*/

		$listArg = new NewImageListParameters();
		$listArg->imageObject = $arg->imageObject;
		$listArg->imageObjectID = $arg->imageObjectID;
		$imageList = new NewImageList($listArg);
		$images = $imageList->images();

		// PAGINATION
		// eg legacy: PaginationView::paginate($numberOfPages,$currentPage,'/designer/images/')

		$imageList = '

			<div id="new_image_list_container" class="' . implode(' ', $arg->listContainerDivClasses) . '">
	
				<div class="table-responsive">
				
					<table class="table table-striped">
			
						<thead class="thead-light">
			
							<tr>
								<th class="text-center">' . Lang::getLang('image') . '</th>
								<th class="text-center">' . Lang::getLang('url') . '</th>
								<th class="text-center">' . Lang::getLang('originalName') . '</th>
								<th class="text-center">' . Lang::getLang('date') . '</th>
								<th class="text-center">' . Lang::getLang('size') . '</th>
								<!--<th class="text-center">' . Lang::getLang('object') . '</th>-->
								<!--<th class="text-center">' . Lang::getLang('carousel') . '</th>-->
								<!--<th class="text-center">' . Lang::getLang('order') . '</th>-->
								<th class="text-center">' . Lang::getLang('main') . '</th>
								<th class="text-center">' . Lang::getLang('action') . '</th>
							</tr>
						
						</thead>
						
						<body>' . $this->newImageListRows($images) . '</body>
						
					</table>
				
				</div>
			
			</div>

		';

		// PAGINATION
		// eg legacy: PaginationView::paginate($numberOfPages,$currentPage,'/designer/images/')

		return $imageList;

	}

	public function newImageListRows($images) {

		foreach ($images as $imageID) {

			$image = new Image($imageID);

			$createdDT = new DateTime($image->created);

			$imageSize = $image->imageSize/1024;

			$rows .= '
				<tr>
					<td class="text-center"><a href="/image/' . $imageID . '/" target="blank"><img src="/image/' . $image->imageID . '/90/" style="width:90px;" alt="' . $image->imageOriginalName . '"></a></td>
					<td class="text-center"><a href="/image/' . $imageID . '/" target="blank" style="text-transform:lowercase;">/image/' . $imageID . '/</a></td>
					<td class="text-center">' . $image->imageOriginalName . '</td>
					<td class="text-center">' . $createdDT->format('Y-m-d H:i:s') . '</td>
					<td class="text-center">' . number_format($imageSize) . 'K</td>
					<!--<td class="text-center">' . $image->imageObject . '</td>-->
					<!--<td class="text-center"><button type="button" class="btn btn-secondary btn-sm">' . $image->imageDisplayInGallery . '</button></td>-->
					<!--<td class="text-center">' . $image->imageDisplayOrder . '</td>-->
					<td class="text-center"><input type="radio" name="mainImage" value="' . $imageID . '"' . ($image->imageDisplayClassification=='mainImage'?' checked':'') . '></td>
					<td class="text-center"><button type="button" class="btn btn-danger btn-sm"><span class="fas fa-trash-alt"></span></button></td>
				</tr>
			';

		}

		return $rows;





	}

}

?>