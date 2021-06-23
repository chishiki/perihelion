<?php

class RSS {

	public function getFeed($urlArray):string {

		$site = new Site($_SESSION['siteID']);

		$rss = '<?xml version="1.0" encoding="UTF-8" ?>
			<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
				<site>
					<atom:link href="https://' . $site->siteURL . '/rss/" rel="self" type="application/rss+xml" />
					<title>' . $site->getTitle() . '</title>
					<link>https://' . $site->siteURL . '/</link>
					<description>' . htmlspecialchars($site->getDescription()) . '</description>
					<language>' . $_SESSION['lang'] . '</language>
					' . $this->feedItems() . '
				</site>
			</rss>
		';
		
		return $rss;

	}

	private function feedItems():string {

		$dt = new DateTime();
		$site = new Site($_SESSION['siteID']);

		$arg = new ContentListParameters();
		$arg->contentPublished = 1;
		// $arg->contentPublishedDateCheck = $dt->format('Y-m-d');
		$arg->contentCategoryType = 'page';
		$arg->orderBy = array('entryPublishStartDate' => 'DESC');
		$cl = new ContentList($arg);
		$contentList = $cl->content();

		$items = '';

		foreach ($contentList AS $contentID) {

			$content = new Content($contentID);
			$contentDT = new DateTime($content->entryPublishStartDate);
			$url = 'https://' . $site->siteURL . '/' . $content->entrySeoURL . '/';
			$items .= '
				<item>
					<title>' . htmlspecialchars($content->title()) . '</title>
					<link>' . $url . '</link>
					<guid>' . $url . '</guid>
					<pubDate>' . $contentDT->format('r') . '</pubDate>
					<category>Content</category>
					<description><![CDATA[' . Utilities::feedificate($content->content(false)) . ']]></description>
				</item>
			';

		}

		return $items;


	}

}

?>