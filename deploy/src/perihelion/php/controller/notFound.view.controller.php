<?php

class NotFoundViewController {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {
		
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
		
	}
	
	public function getView() {
		
	    $html = '<div class="container">';
	       $html .= '<div class="row">';
	           $html .= '<div class="col-12">';
	               $html .= '<div class="text-center">';
	                   $html .= '<h1 class="mt-5">404</h1>';
	                   $html .= '<h3 class="mt-3 mb-3">The requested page was not found.</h3>';
	                   $html .= '<div class="mb-5">';
	                       $html .= '<a class="btn btn-secondary" href="/' . Lang::prefix() . '">' . Lang::getLang('home') . '</a> ';
	                       $html .= '<a class="btn btn-secondary" href="/' . Lang::prefix() . 'contact/">' . Lang::getLang('contact') . '</a>';
	                   $html .= '</div>';
	               $html .= '</div>';
	           $html .= '</div>';
	       $html .= '</div>';
	    $html .= '</div>';
	    
		
	    return $html;

	}
	
}

?>