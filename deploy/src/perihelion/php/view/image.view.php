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

	public function newImageManager(NewImageViewParameters $arg) {

		$manager = '';
		if ($arg->includeForm) { $manager .= $this->newImageForm($arg); }
		if ($arg->includeList) { $manager .= $this->newImageList($arg); }
		$cardView = new CardView('new_image_manager_card', $arg->cardContainerDivClasses, $arg->breadcrumbs, array('col-12'), $arg->cardHeader, $arg->navtabs.$manager, false);
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

		// PAGINATION
		// eg legacy: PaginationView::paginate($numberOfPages,$currentPage,'/designer/images/')

		$imageList = '

			<div id="new_image_list_container" class="' . implode(' ', $arg->listContainerDivClasses) . '">
	
				<div class="table-responsive">
				
					<table class="table table-striped table-sm">
			
						<thead class="thead-light">
			
							<tr>
								<th class="image-list-image text-center">' . Lang::getLang('image') . '</th>
								<th class="image-list-url text-center d-none d-md-table-cell">' . Lang::getLang('url') . '</th>
								<th class="image-list-original-name text-center d-none d-xl-table-cell">' . Lang::getLang('originalName') . '</th>
								<th class="image-list-created text-center d-none d-md-table-cell">' . Lang::getLang('date') . '</th>
								<th class="image-list-size text-center d-none d-sm-table-cell">' . Lang::getLang('size') . '</th>
								<th class="image-list-image-object text-center d-none' . ($arg->displayObjectInfo?' d-xl-table-cell':'') . '">' . Lang::getLang('object') . '</th>
								<th class="image-list-carousel-checkbox text-center d-none' . ($arg->displayCarouselCheckbox?' d-xl-table-cell':'') . '">' . Lang::getLang('carousel') . '</th>
								<th class="image-list-display-order text-center d-none' . ($arg->displayDisplayOrder?' d-xl-table-cell':'') . '">' . Lang::getLang('order') . '</th>
								<th class="image-list-default-radio text-center' . ($arg->displayDefaultRadio?'':' d-none') . '">' . Lang::getLang('main') . '</th>
								<th class="image-list-action text-center">' . Lang::getLang('action') . '</th>
							</tr>
						
						</thead>
						
						<body>' . $this->newImageListRows($arg) . '</body>
						
					</table>
				
				</div>
			
			</div>

		';

		// PAGINATION
		// eg legacy: PaginationView::paginate($numberOfPages,$currentPage,'/designer/images/')

		return $imageList;

	}

	public function newImageListRows(NewImageViewParameters $arg) {

		$listArg = new NewImageListParameters();
		$listArg->imageObject = $arg->imageObject;
		$listArg->imageObjectID = $arg->imageObjectID;
		$imageList = new NewImageList($listArg);
		$images = $imageList->images();

		$rows = '';

		foreach ($images as $imageID) {

			$image = new Image($imageID);
			$createdDT = new DateTime($image->created);
			$imageSize = $image->imageSize/1024;

			$defaultImage = false;
			if ($image->imageDisplayClassification=='mainImage') {
				$defaultImage = true;
			}

			$rows .= '
				<tr data-image-id="' . $imageID . '" data-image-object="' . $image->imageObject . '" data-image-object-id="' . $image->imageObjectID . '">
					<td class="image-list-image text-center align-middle">
						<a href="/image/' . $imageID . '/" target="blank">
							<img src="/image/' . $image->imageID . '/50/" style="width:50px;" alt="' . $image->imageOriginalName . '">
						</a>
					</td>
					<td class="image-list-url text-center d-none d-md-table-cell align-middle">
						<a href="/image/' . $imageID . '/" target="blank" style="text-transform:lowercase;">/image/' . $imageID . '/</a>
					</td>
					<td class="image-list-original-name text-left align-middle d-none d-xl-table-cell">' . $image->imageOriginalName . '</td>
					<td class="image-list-created text-center align-middle d-none d-md-table-cell">' . $createdDT->format('Y-m-d H:i:s') . '</td>
					<td class="image-list-size text-center align-middle d-none d-sm-table-cell">' . number_format($imageSize) . 'K</td>
					<td class="image-list-image-object text-center align-middle d-none' . ($arg->displayObjectInfo?' d-xl-table-cell':'') . '">' . $image->imageObject . '</td>
					<td class="image-list-carousel-checkbox text-center align-middle d-none' . ($arg->displayCarouselCheckbox?' d-xl-table-cell':'') . '">
						<button type="button" class="btn btn-secondary btn-sm">' . $image->imageDisplayInGallery . '</button>
					</td>
					<td class="image-list-display-order text-center align-middle d-none' . ($arg->displayDisplayOrder?' d-xl-table-cell':'') . '">' . $image->imageDisplayOrder . '</td>
					<td class="image-list-default-radio text-center align-middle' . ($arg->displayDefaultRadio?'':' d-none') . '">
						<input type="radio" name="mainImage" value="' . $imageID . '"' . ($defaultImage?' checked':'') . '>
					</td>
					<td class="image-list-action text-center align-middle">
						<button type="button" class="image-delete btn btn-danger btn-sm' . ($defaultImage?' disabled':'') . '"' . ($defaultImage?' disabled':'') . '><span class="fas fa-trash-alt"></span></button>
					</td>
				</tr>
			';

		}

		return $rows;





	}

}

?>