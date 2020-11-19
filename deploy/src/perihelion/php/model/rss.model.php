<?php

class RSS {

	public function getFeed($urlArray) {

		$currentDate = date('Y-m-d H:i:s');
		$site = new Site($_SESSION['siteID']);
		$atomLink = 'http://' . $site->siteURL . '/rss/';

		$rss = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
		
		$rss .= "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
		
			$rss .= "\t<site>\n";

				$rss .= "\t\t<atom:link href=\"" . $atomLink . "\" rel=\"self\" type=\"application/rss+xml\" />\n";
				$rss .= "\t\t<title>". $site->getTitle() . "</title>\n";
				$rss .= "\t\t<link>http://" . $site->siteURL . "/</link>\n";
				$rss .= "\t\t<description>". $site->getDescription() . "</description>\n";
				$rss .= "\t\t<language>en</language>\n";

				$content = Content::contentArray();
				foreach ($content AS $contentID) {
					
					$c = new Content($propertyID);
					$s = new Site($c->siteID);
					$title = htmlspecialchars($c->title());
					$pubdate = date('r', strtotime($p->propertyDateTimeAdded));
					$url = 'http://' . $s->siteURL . '/content/' . $c->entryPublishStartDateNameSeoUrl . '/';
					$description = Utilities::feedificate($c->description());

					$rss .= "\t\t<item>\n";
						$rss .= "\t\t\t<title>" . $title . "</title>\n";
						$rss .= "\t\t\t<link>" . $url . "</link>\n";
						$rss .= "\t\t\t<guid>" . $url . "</guid>\n";
						$rss .= "\t\t\t<pubDate>" . $pubdate . "</pubDate>\n";
						$rss .= "\t\t\t<category>Content</category>\n";
						$rss .= "\t\t\t<description><![CDATA[" . $description . "]]></description>\n";
					$rss .= "\t\t</item>\n";
					
				}

			$rss .= "\t</site>\n";
			
		$rss .= "</rss>";
		
		return $rss;

	}

}

?>