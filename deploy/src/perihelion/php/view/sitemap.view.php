<?php

class SitemapXmlView {

	public $xml;
	
	public function __construct() {

		$site = new Site($_SESSION['siteID']);
		$siteURL = 'https://' . $site->siteURL;
			
		$this->xml = '<?xml version="1.0" encoding="UTF-8"?>';
		$this->xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

			// HOME
			$this->xml .= '<url><loc>' . $siteURL . '/</loc></url>';
			
			// PAGES
			$publishedPages = (new Page)->getPublishedPageArray();
			foreach ($publishedPages AS $value) { $this->xml .= '<url><loc>' . $siteURL . '/' . $value . '/</loc></url>'; }

			// GENERAL
			$this->xml .= '<url><loc>' . $siteURL . '/tos/</loc></url>';
			$this->xml .= '<url><loc>' . $siteURL . '/privacy/</loc></url>';
			$this->xml .= '<url><loc>' . $siteURL . '/contact/</loc></url>';

		$this->xml .= '</urlset>';

	}

}


?>