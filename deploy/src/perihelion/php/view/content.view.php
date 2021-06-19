<?php

final class ContentView {
	
	private $urlArray;
	private $inputArray;
	public $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
	}

	public function contentForm($action, $contentID = null) {

		$content = new Content($contentID);
		$actionURL = '/' . Lang::languageUrlPrefix() . 'designer/content/' . $action . '/' . ($contentID?$contentID.'/':'');

		if (!empty($this->inputArray)) {
			foreach ($this->inputArray AS $key => $value) { if (isset($content->$key)) { $content->$key = $value; } }
		}

		$h = '<div id="perihelion_content_form">';
			$h .= '<div class="container">';
				$h .= '<div class="row">';
					$h .= '<div class="col-sm-12">';

							$h .= '<div class="card" >';
								$h .= '<div class="card-header">';
									$h .= '<div class="card-title">';
										$h .= '<a href="/' . Lang::prefix() . 'designer/content/">' . Lang::getLang('contentList') . '</a> &rArr; ';
										if ($action == 'update') {
											$h .= Lang::getLang('updateContent') . '  &rArr; ' . $content->title();
											if ($content->entrySeoURL != '') {
												$h .= '<a class="btn btn-secondary btn-sm float-right" href="/' . Lang::prefix() . $content->entrySeoURL . '/" target="_blank"><span class="fas fa-eye' . ($content->entryPublished?"":"-slash") . '"></span></a>';
											}
										} else { $h .= Lang::getLang('createContent'); }
									$h .= '</div>';
								$h .= '</div>';
								$h .= '<div class="card-body">';

								$h .= self::contentButtonGroup($contentID,$this->urlArray[4]);
								
								$h .= '
								
										<form role="form" class="form-horizontal" action="' . $actionURL . '" method="post">
										
											' . ($action=='update'?'<input type="hidden" name="contentID" value="'.$contentID.'">':'') . '
										
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="entryTitleEnglish">' . Lang::getLang('entryTitleEnglish') . '</label>
												<div class="col-sm-10"><input type="text" name="entryTitleEnglish" class="form-control col-sm-10" placeholder="title" value="' . $content->entryTitleEnglish . '"></div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="contentMetaKeywordsEnglish">' . Lang::getLang('contentMetaKeywordsEnglish') . '</label>
												<div class="col-sm-10"><input type="text" name="contentMetaKeywordsEnglish" class="form-control col-sm-10" placeholder="Keywords (English)" value="' . $content->contentMetaKeywordsEnglish . '"></div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="contentMetaDescriptionEnglish">' . Lang::getLang('contentMetaDescriptionEnglish') . '</label>
												<div class="col-sm-10"><input type="text" name="contentMetaDescriptionEnglish" class="form-control col-sm-10" placeholder="Description (English)" value="' . $content->contentMetaDescriptionEnglish . '"></div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="entryContentEnglish">' . Lang::getLang('entryContentEnglish') . '</label>
												<div class="col-sm-10"><textarea class="form-control" rows="10" name="entryContentEnglish" placeholder="Content (English)">' . $content->entryContentEnglish . '</textarea></div>
											</div>
											
											<hr />
											
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="entryTitleJapanese">' . Lang::getLang('entryTitleJapanese') . '</label>
												<div class="col-sm-10"><input type="text" name="entryTitleJapanese" class="form-control col-sm-10" placeholder="タイトル" value="' . $content->entryTitleJapanese . '"></div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="contentMetaKeywordsJapanese">' . Lang::getLang('contentMetaKeywordsJapanese') . '</label>
												<div class="col-sm-10"><input type="text" name="contentMetaKeywordsJapanese" class="form-control col-sm-10" placeholder="キーワード" value="' . $content->contentMetaKeywordsJapanese . '"></div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="contentMetaDescriptionJapanese">' . Lang::getLang('contentMetaDescriptionJapanese') . '</label>
												<div class="col-sm-10"><input type="text" name="contentMetaDescriptionJapanese" class="form-control col-sm-10" placeholder="表記内容" value="' . $content->contentMetaDescriptionJapanese . '"></div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="entryContentJapanese">' . Lang::getLang('entryContentJapanese') . '</label>
												<div class="col-sm-10"><textarea class="form-control" rows="10" name="entryContentJapanese" placeholder="内容">' . $content->entryContentJapanese . '</textarea></div>
											</div>
											
											<hr />
											
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="entryPublished">' . Lang::getLang('entryPublished') . '</label>
												<div class="col-sm-10"><input name="entryPublished" type="checkbox" value="1"' . ($content->entryPublished?" checked":"") . '></div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="contentCategoryType">' . Lang::getLang('contentCategoryType') . '</label>
												<div class="col-sm-10">
													<select class="form-control" name="contentCategoryType">
														<option value="page"' . ($content->contentCategoryType=='page'?" selected":"") . '>' . Lang::getLang('page') . '</option>
														<option value="other"' . ($content->contentCategoryType=='other'?" selected":"") . '>' . Lang::getLang('other') . '</option>
													</select>
												</div>
											</div>
											
											<hr />
											
											<div class="form-group row">
												<label class="col-sm-2 col-form-label" for="entrySeoURL">SEO URL</label>
												<div class="col-sm-10"><input type="text" name="entrySeoURL" class="form-control col-sm-10" placeholder="alphanumeric and hyphens only " value="' . $content->entrySeoURL . '"></div>
											</div>
											
											<hr />

											<div class="form-group row">
												<div class="col-sm-12"><button type="submit" class="btn btn-primary float-right" name="submit">' . Lang::getLang($action) . '</button></div>
											</div>
											
										</form>
										
								';

								$h .= '</div>';
							$h .= '</div>';

					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';
		$h .= '</div>';

		return $h;

	}
	
	public function contentList() {
		
		$contentArray = Content::contentArray($_SESSION['siteID']);
		$actionURL = '/' . Lang::languageUrlPrefix() . 'designer/content/create/';
		
		$h = '<div id="perihelion_content_list">';
			$h .= '<div class="container">';
				$h .= '<div class="row">';
					$h .= '<div class="col-sm-12">';
						$h .= '<div class="card" >';
							
							$h .= '<div class="card-header">';
								$h .= '<div class="card-title">';
									$h .= Lang::getLang('content') . ' <a class="btn btn-secondary btn-sm float-right" href="' . $actionURL . '"><span class="fas fa-plus"></span></a>';
								$h .= '</div>';
							$h .= '</div>';
							
							$h .= '<div class="card-body">';
								$h .= '<div class="table-responsive">';
									$h .= '<table class="table table-bordered table-hover table-striped table-sm">';
										
											$h .= '<tr>';
												$h .= '<th class="th_content_content">' . Lang::getLang('content') . '</th>';
												$h .= '<th class="th_content_category text-center">' . Lang::getLang('category') . '</th>';
												$h .= '<th class="th_content_created text-center">' . Lang::getLang('created') . '</th>';
												$h .= '<th class="th_content_published text-center">' . Lang::getLang('published') . '</th>';
												$h .= '<th class="th_content_served text-center">' . Lang::getLang('served') . '</th>';
												$h .= '<th class="th_content_action text-center">' . Lang::getLang('action') . '</th>';
											$h .= '</tr>';
											
											foreach($contentArray AS $contentID) {
												$content = new Content($contentID);
												$author = new User($content->creator);
												$contentCategory = new ContentCategory($content->contentCategoryID);
												$category = $contentCategory->contentCategoryEnglish;
												$h .= '<tr>';
													$h .= '<td>';
														if (!empty($content->entrySeoURL)) {
															$h .= '<a class="btn btn-secondary btn-block btn-sm  clearfix" href="/' . Lang::languageUrlPrefix() . $content->entrySeoURL . '/" style="text-align:left;padding-left:5px;font-size:12px;font-weight:700;" target="_blank">' . $content->title() . '<span class="fas fa-external-link-alt float-right" style="margin:5px;"></span></a>';
														} else {
															$h .= $content->title() . '<span class="float-right" style="font-size:12px;">' . Lang::getLang('requiresSeoURL') . '<span class="fas fa-exclamation-triangle" style="margin:5px;color:#999;"></span></span>';
														}
													$h .= '</td>';
													$h .= '<td class="text-center">' . (!empty($category)?$category:'') . '</td>';
													$h .= '<td class="text-center">' . $content->created . '</td>';
													$h .= '<td class="text-center">' . ($content->entryPublished?"&#10004;":"") . '</td>';
													$h .= '<td class="text-center">' . number_format($content->entryViews) . '</td>';
													$h .= '<td class="text-center">';
														if ($content->contentLock) {
															$h .= '<span class="fas fa-lock"></span>';
														} else {
															$h .= '<a class="btn btn-primary btn-block btn-sm" href="/' . Lang::languageUrlPrefix() . 'designer/content/update/' . $contentID . '/">' . Lang::getLang('update') . '</a>';
														}
													$h .= '</td>';
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

	public function easyContent() {
		
		$contentID = Content::publishedContentID($this->urlArray[0]);
		if ($contentID) {
			$content = new Content($contentID);
			return $content->content();
		}
		
	}

	public function contentImagesForm($contentID) {
		
		$content = new Content($contentID);

		$formAction = '/' . Lang::prefix() . 'designer/content/update/' . $contentID . '/images/';
		
		$h = '<div id="perihelion_designer_content_images">';
			$h .= '<div class="container">';
				$h .= '<div class="row">';
					$h .= '<div class="col-sm-12">';
						$h .= '<div class="card" >';
						
							$h .= '<div class="card-header">';
								$h .= '<div class="card-title clearfix">' . Lang::getLang('updateContent') . ' - ' . $content->title() . ' - ' . Lang::getLang('images') . '</div>';
							$h .= '</div>';
							
							$h .= '<div class="card-body">';
								
								$h .= self::contentButtonGroup($contentID,$this->urlArray[4]);

								$h .= '<form id="perihelionDesignerContentImagesForm" name="perihelionDesignerContentImagesForm"  method="post" action="' . $formAction . '" enctype="multipart/form-data">';
									$h .= '<input type="hidden" name="contentID" value="' . $contentID . '">';

									$h .= '<div class="form-group row">';
									
										$h .= '<div class="col-sm-2 offset-sm-8">';
										
											$h .= '<label class="btn btn-secondary btn-block btn-file">';
												$h .= '<span id="perihelionDesignerContentImagesSubmitButtonText">' . Lang::getLang('Select Images') . '</span> ';
												$h .= '<input type="file" id="contentImages" name="contentImages[]" style="display:none;" accept="image/*" multiple>';
											$h .= '</label>';
											
										$h .= '</div>';

										$h .= '<div class="col-sm-2">';
										
											$h .= '<button type="submit" name="perihelionDesignerContentImagesSubmit" id="perihelionDesignerContentImagesSubmit" class="btn btn-primary btn-block" disabled="true">';
												$h .= '<span id="perihelionDesignerContentImagesSubmitIcon" class="fas fa-upload"></span> ';
												$h .= '<span id="perihelionDesignerContentImagesSubmitText">' . Lang::getLang('uploadImages') . '</span>';
											$h .= '</button>';
											
										$h .= '</div>';
										
									$h .= '</div>';

								$h .= '</form>';

								$images = Image::getObjectImageArray('Content',$contentID);
								
								if (!empty($images)) {
									$h .= '<div class="row">';
										$h .= '<div class="col-12">';
											$h .= '<div class="table-responsive">';
												$h .= '<table class="table table-striped">';

													$h .= '<tr>';
														$h .= '<th>' . Lang::getLang('image') . '</th>';
														$h .= '<th class="hidden-xs">' . Lang::getLang('url') . '</th>';
														$h .= '<th class="hidden-xs hidden-sm">' . Lang::getLang('originalName') . '</th>';
														$h .= '<th class="hidden-xs">' . Lang::getLang('date') . '</th>';
														$h .= '<th class="text-right hidden-xs">' . Lang::getLang('size') . '</th>';
														$h .= '<th class="text-center">' . Lang::getLang('main') . '</th>';
														$h .= '<th class="text-center">' . Lang::getLang('carousel') . '</th>';
														$h .= '<th class="text-center">' . Lang::getLang('order') . '</th>';
														$h .= '<th class="text-center">' . Lang::getLang('delete') . '</th>';
													$h .= '</tr>';
												
													foreach ($images as $imageID) {
														$image = new Image($imageID);
														
														if ($image->imageSize < 1024) { $size = 1; } else { $size = $image->imageSize / 1024;}
														
														$h .= '<tr>';
															$h .= '<td style="vertical-align:middle;"><a href="/image/' . $image->imageID . '/" target="blank"><img src="/image/' . $image->imageID . '/90/" style="width:90px;"></a></td>';
															$h .= '<td class="hidden-xs" style="vertical-align:middle;"><a href="/image/' . $image->imageID . '/" target="blank">/image/' . $image->imageID . '/</a></td>';
															$h .= '<td class="hidden-xs hidden-sm" style="vertical-align:middle;">' . $image->imageOriginalName . '</td>';
															$h .= '<td class="hidden-xs" style="vertical-align:middle;">' . date('Y-m-d',strtotime($image->imageSubmissionDateTime)) . '</td>';
															$h .= '<td class="text-right hidden-xs" style="vertical-align:middle;">' . number_format($size) . 'K</td>';

															$h .= '<td class="text-center" style="vertical-align:middle;">';
																if ($image->imageDisplayClassification != 'mainImage') {
																	$h .= '<a class="btn btn-secondary btn-sm" href="/' .  Lang::prefix() . 'designer/content/update/' . $contentID . '/images/make-main-image/' . $imageID . '/"><span class="fas fa-check" style="color:#fff;"></span></a>';
																} else {
																	$h .= '<span class="fas fa-check" style="color:#000;"></span>';
																}
															$h .= '</td>';
															
															$h .= '<td class="text-center" style="vertical-align:middle;">';
																if ($image->imageDisplayInGallery) {
																	$h .= '<a class="btn btn-secondary btn-sm" href="/' .  Lang::prefix() . 'designer/content/update/' . $contentID . '/images/remove-from-carousel/' . $imageID . '/"><span class="fas fa-check" style="color:#000;"></span></a>';
																} else {
																	$h .= '<a class="btn btn-secondary btn-sm" href="/' .  Lang::prefix() . 'designer/content/update/' . $contentID . '/images/add-to-carousel/' . $imageID . '/"><span class="fas fa-check" style="color:#fff;"></span></a>';
																}
															$h .= '</td>';
															
															$h .= '<td class="text-center" style="vertical-align:middle;">';
																$h .= self::contentImageDisplayOrderDropdown($contentID, $image->imageID, $image->imageDisplayOrder);
															$h .= '</td>';

															$h .= '<td class="text-center" style="vertical-align:middle;">';
																$h .= '<a class="btn btn-danger btn-sm" href="/' .  Lang::prefix() . 'designer/content/update/' . $contentID . '/images/delete/' . $imageID . '/"><span class="fas fa-trash-alt" style="color:#fff;"></span></a>';
															$h .= '</td>';
	
														$h .= '</tr>';
													}
												
												$h .= '</table>';
											$h .= '</div>';
										$h .= '</div>';
									$h .= '</div>';
								}

							$h .= '</div>';
						$h .= '</div>';
					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';
		$h .= '</div>';

		return $h;

	}

	private static function contentButtonGroup($contentID,$section) {

		$url = '/' . Lang::prefix() . 'designer/content/update/' . $contentID . '/';

        $h = '<div id="perihelion_content_button_group">';
          $h .= '<div class="row">';
              $h .= '<div class="col-md-12">';
        		$h .= '<div class="btn-group" role="group" aria-label="...">';								
        			$h .= '<a href="' . $url . '" class="btn btn-secondary' . ($section==''?" active":"") . '"' . (!$contentID?" disabled":"") . '><span class="fas fa-pencil-alt"></span></a>';
        			$h .= '<a href="' . $url . 'images/" class="btn btn-secondary' . ($section=='images'?" active":"") . '"' . (!$contentID?" disabled":"") . '>' . Lang::getLang('images') . '</a>';
        		$h .= '</div>';
        	$h .= '</div>';
          $h .= '</div>';
        $h .= '</div>';
		$h .= '<hr />';

		return $h;
		
	}

	private static function contentImageDisplayOrderDropdown($contentID, $objectID, $displayOrder) {

		$h = '<select name="imageDisplayOrder" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">';
			for ($i = 0; $i <= 100; $i++) {
				$url = '/' . Lang::prefix() . 'designer/content/update/' . $contentID . '/images/set-display-order/' . $objectID . '/' . $i . '/';
				$h .= '<option value="' . $url . '"' . ($i==$displayOrder?" selected":"") . '>' . $i . '</option>';
			}
		$h .= '</select>';
		return $h;
		
	}
	
	
	
}

?>