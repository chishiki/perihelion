<?php

class IndexViewController {

	private $urlArray;
	private $inputArray;
	private $moduleArray;
	private $errorArray;
	private $messageArray;
	
	public function __construct($urlArray, $inputArray, $moduleArray, $errorArray, $messageArray) {

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->moduleArray = $moduleArray;
		$this->errorArray = $errorArray;
		$this->messageArray = $messageArray;

	}
	
	public function getView() {

		$site = new Site($_SESSION['siteID']);

		$h = '';
		
		$carouselID = Carousel::getCarouselID();
		
		if ($carouselID) { $h .= CarouselView::displayCarousel($carouselID); }

		if ($site->siteIndexContentID) {
			$content = new Content($site->siteIndexContentID);
			if ($content->entryPublished) {

        // These could come from the content.view content.model contentClassesArray @see ContentView

        $h .= '<div id="contentClassesArray-id" class="container" >'; // container is only needed class here
        $h .= '<hr/>'; // todo: remove and add a space
        $h .= '<div class="row">';
        $h .= '<div class="col-12" >';
        $h .= $content->content();
        $h .= '</div>';
        $h .= '</div>';
        $h .= '</div>';

      }
		}
		
		foreach ($this->moduleArray AS $moduleName) {
			$moduleIndexView = ModuleUtilities::moduleToClassName($moduleName, 'IndexView');
			if (class_exists($moduleIndexView)) {
				$view = new $moduleIndexView($this->urlArray);
				$h .= $view->getView();
			}
		}

		if ($site->siteFooterContentID) {
			$footerContent = new Content($site->siteFooterContentID);
			$h .= $footerContent->content();
		}
		
		return $h;
		
	}
	
}

?>