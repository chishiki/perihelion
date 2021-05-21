<?php

final class CarouselView {

	private $loc;
	private $input;
	private $errors;
	
	public function __construct($loc = array(), $input = array(),  $errors = array()) {
		
		$this->loc = $loc;
		$this->input = $input;
		$this->errors = $errors;
		
	}
	
	public function carouselList() {

		$table = '
		
			<div class="row">
				<div class="col-12 col-sm-6 offset-sm-6 col-md-3 offset-md-9 mb-3">
					<a class="btn btn-block btn-outline-success btn-sm" href="/' . Lang::languageUrlPrefix() . 'designer/carousels/create/">
						<span class="fas fa-plus"></span> ' . Lang::getLang('create') . '
					</a>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered table-striped">
					<thead class="thead-light">
						<tr>
							<th class="text-center">' . Lang::getLang('id') . '</th>
							<th class="text-center">' . Lang::getLang('creator') . '</th>
							<th class="text-center">' . Lang::getLang('created') . '</th>
							<th class="text-center">' . Lang::getLang('title') . '</th>
							<th class="text-center">' . Lang::getLang('object') . '</th>
							<th class="text-center">' . Lang::getLang('objectID') . '</th>
							<th class="text-center">' . Lang::getLang('action') . '</th>
						</tr>
					</thead>
					<tbody>' . $this->carouselListRows() . '</tbody>
				</table>
			</div>

		';

		$card = new CardView('perihelion_carousel_list', array('container'), '', array('col-12'), Lang::getLang('carousels'), $table, false);
		return $card->card();

	}

	private function carouselListRows() {

		$carouselArray = Carousel::carouselArray();

		$rows = '';
		foreach($carouselArray AS $carouselID) {

			$carousel = new Carousel($carouselID);
			$dt = new DateTime($carousel->carouselCreationDateTime);
			$author = new User($carousel->carouselCreatedByUserID);

			$rows .= '
				<tr>
					<td class="text-center">' . $carouselID . '</td>
					<td class="text-center">' . $author->getUserDisplayName() . '</td>
					<td class="text-center">' . $dt->format('Y-m-d') . '</td>
					<td class="text-center">' . $carousel->title() . '</td>
					<td class="text-center">' . $carousel->carouselObject . '</td>
					<td class="text-center">' . ($carousel->carouselObjectID?$carousel->carouselObjectID:'') . '</td>
					<td class="text-center">
						<a class="btn btn-block btn-outline-primary btn-sm" href="/' . Lang::languageUrlPrefix() . 'designer/carousels/update/' . $carouselID . '/">' . Lang::getLang('update') . '</a>
					</td>
				</tr>
			';

		}

		return $rows;

	}

	public function carouselForm($action, $carouselID = null) {
		
		$carousel = new Carousel($carouselID);
		$actionURL = '/' . Lang::languageUrlPrefix() . 'designer/carousels/' . $action . '/' . ($action=='update'?$carouselID.'/':'');
		$carouselPanels = CarouselPanel::carouselPanelArray($carouselID);
		$carouselPanelHeading = 'carouselSettings';

		if (!empty($this->input)) {
			foreach ($this->input AS $key => $value) { if (isset($carousel->$key)) { $carousel->$key = $value; } }
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
		$carouselFormCard = new CardView('perihelionCarouselForm', array('container'), '', array('col-12'), $carouselFormHeader, $carouselForm, ($action=='create'?false:true));
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

	public static function displayCarousel($carouselID) {

		$carousel = new Carousel($carouselID);
		$carouselPanels = CarouselPanel::carouselPanelArray($carouselID, true);
		$numberOfPanels = count($carouselPanels);

		$indicators = '';
		if ($numberOfPanels > 1) {
			$indicators = '<ol class="carousel-indicators hidden-xs hidden-sm">';
			for ($i = 0; $i < $numberOfPanels; $i++) {
				$indicators .= '<li data-target="#perihelion_carousel" data-slide-to="' . $i . '" class="' . ($i==0?'active':'') . '"></li>';
			}
			$indicators .= '</ol>';
		}

		$panels = '<div class="carousel-inner">';

			for ($i = 0; $i < $numberOfPanels; $i++) {

				$cp = new CarouselPanel($carouselPanels[$i]);

				$imageID = $cp->imageID;
				$alt = $cp->alt();
				$title = $cp->title();
				$subtitle = $cp->subtitle();
				$url = $cp->url();

				$panels .= '<div class="carousel-item' . ($i==0?' active':'') . '">';
					if (!empty($url)) { $panels .= '<a href="' . $url  . '">'; }
						$panels .= '<img src="/image/' . $imageID . '/" class="d-block w-100" alt="' . $alt . '" />';
					if (!empty($url)) { $panels .= '</a>'; }
					if (!empty($title) || !empty($subtitle)) {
						$panels .= '<div class="carousel-caption d-none d-md-block">';
							if (!empty($title)) { $panels .= '<h5>' . $title . '</h5>'; }
							if (!empty($subtitle)) { $panels .= '<p>' . $subtitle . '</p>'; }
						$panels .= '</div>';
					}
				$panels .= '</div>';

			}

		$panels .= '</div>';

		$controls = '';
		if ($numberOfPanels > 1) {
			$controls = '
				<a class="carousel-control-prev" href="#perihelion_carousel" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="carousel-control-next" href="#perihelion_carousel" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>
			';
		}

		$c = '
			<div class="">
				<div class="container">
					<div id="perihelion_carousel" class="carousel slide" data-ride="carousel">
					' . $indicators . '
					<div class="carousel-inner">' . $panels . '</div>
					' . $controls . '
					</div>
				</div>
			</div>
		';
		
		return $c;

	}

}


?>