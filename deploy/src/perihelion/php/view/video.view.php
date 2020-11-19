<?php

class VideoView {

	public static function videoModal($propertyVideoURL) {
		
		$videoID = null;
		$videoType = null;
		$modal = null;
		
		if ($videoID = Video::isYouTubeVideo($propertyVideoURL)) { $videoType = 'yt'; }
		elseif ($videoID = Video::isVimeoVideo($propertyVideoURL)) { $videoType = 'v'; }
		
		if ($videoID && $videoType) {
			if ($videoType == 'yt') {
				$modal = '<iframe src="http://www.youtube.com/embed/' . $videoID . '?html5=1" width="100%" frameborder="0" border="0" cellspacing="0" style="border-style:none;width:100%;height:190px;" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			} elseif ($videoType == 'v') {
				$modal = '<iframe src="//player.vimeo.com/video/' . $videoID . '?title=0&byline=0&portrait=0" width="100%" frameborder="0" border="0" cellspacing="0" style="border-style:none;width:100%;height:190px;" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			}
		}
		
		return $modal;
		
	}
	
}

?>