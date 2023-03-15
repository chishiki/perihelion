<?php

/*
class Sitemap {

	private $xml;
	
	public function __construct() {
		
		$siteURL = Config::read('site.url');

		$this->xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$this->xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
		
			$this->xml .= "\t<url><loc>http://" . $siteURL . "/</loc></url>\n";
			$this->xml .= "\t<url><loc>http://" . $siteURL . "/login/</loc></url>\n";
			$this->xml .= "\t<url><loc>http://" . $siteURL . "/register/</loc></url>\n";
			$this->xml .= "\t<url><loc>http://" . $siteURL . "/account-recovery/</loc></url>\n";
			
			$query = "SELECT * FROM perihelion_Content WHERE contentPublished = 1 AND includeOnSitemap = 1 ORDER BY contentSubmissionDateTime DESC";
			$core = Core::getInstance();
			$statement = $core->database->prepare($query);
			$statement->execute();
			while ($row = $statement->fetch()) {
				if ($row['contentURL']) {
					$this->xml .= "\t<url><loc>http://" . $siteURL . "/" . htmlspecialchars($row['contentURL']) . "/</loc></url>\n";
				}
			}

		$this->xml .= "</urlset>";

	}
	
	public function getSitemap() {
		return $this->xml;
	}
	
}
*/

?>