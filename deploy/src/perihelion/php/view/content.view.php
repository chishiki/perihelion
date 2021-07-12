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

		$userCKEditor = Config::read('ckeditor');
		$contentEnglishEditorScript = '';
		$contentJapaneseEditorScript = '';
		if ($userCKEditor) {
			$contentEnglishEditorScript = '
				<script>
					CKEDITOR.replace(\'content_english\', {
						language: \'' . $_SESSION['lang'] . '\',
						contentsCss: \'/perihelion/vendor/twbs/bootstrap/dist/css/bootstrap.min.css\'
					});
				</script>
			';
			$contentJapaneseEditorScript = '
				<script>
					CKEDITOR.replace(\'content_japanese\', {
						language: \'' . $_SESSION['lang'] . '\',
						contentsCss: \'/perihelion/vendor/twbs/bootstrap/dist/css/bootstrap.min.css\'
					});
				</script>
			';
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

								$h .= $this->designerContentFormTabs($action, $contentID, 'content');
								
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
												<div class="col-sm-10">
													<textarea class="form-control' . ($userCKEditor?' ckeditor':'') . '" rows="10" id="content_english" name="entryContentEnglish" placeholder="Content (English)">' . $content->entryContentEnglish . '</textarea>
												</div>
											</div>
											
											' . $contentEnglishEditorScript . '
											
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
												<div class="col-sm-10">
													<textarea class="form-control' . ($userCKEditor?' ckeditor':'') . '" rows="10" id="content_japanese" name="entryContentJapanese" placeholder="内容">' . $content->entryContentJapanese . '</textarea>
												</div>
											</div>
											
											' . $contentJapaneseEditorScript . '
											
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
												<div class="col-sm-10"><input type="text" name="entrySeoURL" class="form-control col-sm-10" placeholder="alphanumeric and hyphens only " value="' . $content->entrySeoURL . '" required></div>
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
	
	public function contentList(ContentListParameters $arg) {

		$actionURL = '/' . Lang::languageUrlPrefix() . 'designer/content/create/';

		$contentList = '

				<div class="row mb-3">
					<div id="new_content_link" class="col-12 col-sm-6 offset-sm-6 col-md-4 offset-md-8 col-lg-3 offset-lg-9">
						<a href="' . $actionURL . '" class="btn btn-primary btn-block">
							<span class="fas fa-plus"></span> ' . Lang::getLang('createContent') . '
						</a>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped table-sm">
						<thead class="thead-light">
							<tr>
								<th class="th_content_content">' . Lang::getLang('content') . '</th>
								<th class="th_content_category text-center">' . Lang::getLang('category') . '</th>
								<th class="th_content_created text-center">' . Lang::getLang('created') . '</th>
								<th class="th_content_published text-center">' . Lang::getLang('published') . '</th>
								<th class="th_content_served text-center">' . Lang::getLang('served') . '</th>
								<th class="th_content_action text-center">' . Lang::getLang('action') . '</th>
							</tr>
						</thead>
						<tbody>' . $this->contentListRows($arg) . '</tbody>
					</table>
				</div>

		';

		$cardView = new CardView('perihelion_content_list', array('container'), '', array('col-12'), Lang::getLang('contentList'), $contentList);
		return $cardView->card();

	}

	private function contentListRows(ContentListParameters $arg) {

		$cl = new ContentList($arg);
		$contentArray = $cl->content();

		$rows = '';

		foreach($contentArray AS $contentID) {

			$content = new Content($contentID);
			$contentCategory = new ContentCategory($content->contentCategoryID);
			$category = $contentCategory->contentCategoryEnglish;

			if (!empty($content->entrySeoURL)) {
				$contentLink = '<a class="btn btn-secondary btn-block btn-sm  clearfix" href="/' . Lang::languageUrlPrefix() . $content->entrySeoURL . '/" style="text-align:left;padding-left:5px;font-size:12px;font-weight:700;" target="_blank">' . $content->title() . '<span class="fas fa-external-link-alt float-right" style="margin:5px;"></span></a>';
			} else {
				$contentLink = $content->title() . '<span class="float-right" style="font-size:12px;">' . Lang::getLang('requiresSeoURL') . '<span class="fas fa-exclamation-triangle" style="margin:5px;color:#999;"></span></span>';
			}

			if ($content->contentLock) {
				$action = '<span class="fas fa-lock"></span>';
			} else {
				$action = '<a class="btn btn-primary btn-block btn-sm" href="/' . Lang::languageUrlPrefix() . 'designer/content/update/' . $contentID . '/">' . Lang::getLang('update') . '</a>';
			}

			$rows .= '
				<tr>
					<td>' . $contentLink . '</td>
					<td class="text-center">' . (!empty($category)?$category:'') . '</td>
					<td class="text-center">' . $content->created . '</td>
					<td class="text-center">' . ($content->entryPublished?'&#10004;':'') . '</td>
					<td class="text-center">' . number_format($content->entryViews) . '</td>
					<td class="text-center">' . $action . '</td>
				</tr>
			';

		}

		return $rows;

	}

	public function easyContent() {
		
		$contentID = Content::publishedContentID($this->urlArray[0]);
		if ($contentID) {
			$content = new Content($contentID);
			return $content->content();
		}
		
	}

	public function designerContentFormTabs($type = 'create', $contentID = null, $activeTab = 'content') {

		$contentFormURL = '#';
		$updateOnly = true;

		if ($type == 'update' && ctype_digit($contentID)) {
			$contentFormURL = '/' . Lang::prefix() . 'designer/content/update/' . $contentID . '/';
			$updateOnly = false;
		}

		$t = '

			<ul id="designer_content_form_nav_tabs" class="nav nav-tabs">
				<li class="nav-item">
					<a class="nav-link' . ($activeTab=='content'?' active':'') . '" href="' . $contentFormURL . '">' . Lang::getLang('content') . '</a>
				</li>
				<li class="nav-item">
					<a class="nav-link' . ($updateOnly?' disabled':'') . ($activeTab=='images'?' active':'') . '" href="' . $contentFormURL . 'images/"' . ($updateOnly?' tabindex="-1"':'') . '>' . Lang::getLang('images') . '</a>
				</li>
			</ul>
			
		';

		return $t;

	}
	
}

?>