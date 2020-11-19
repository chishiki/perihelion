<?php

class PaginationView {
	
	public static function paginate($numberOfPages = 1, $currentPage = 1, $linkBaseURL = '') {
		
		$h = "";
		$lang = "";
		if ($_SESSION['lang'] == 'ja') { $lang = "/ja"; }
		
		$firstPageURL = $lang . $linkBaseURL . "1/";
		if ($currentPage == 1) { $previousPageURL = "#"; } else { $previousPageURL = $lang . $linkBaseURL . ($currentPage - 1) . "/"; }
		if ($currentPage == $numberOfPages) { $nextPageURL = "#"; } else { $nextPageURL = $lang . $linkBaseURL . ($currentPage + 1) . "/"; }
		$lastPageURL = $lang . $linkBaseURL . $numberOfPages . "/";

		if ($numberOfPages <= 5) { // five pages or less
			
			$firstPageToLinkTo = 1;
			$lastPageToLinkTo = $numberOfPages;

		} else  { // more than five pages
			
			// first numbered link to display
			if ($currentPage == $numberOfPages) { // IF last page
				$firstPageToLinkTo = $currentPage - 4; // 
			} elseif ($currentPage == ($numberOfPages - 1)) { // IF next-to-last page
				$firstPageToLinkTo = $currentPage - 3; // THEN first page number to display is current page minus 3
			} else {
				if ($currentPage > 3) { // IF current page is more than 3
					$firstPageToLinkTo = $currentPage - 2; // THEN first page number to display is current page minus 2
				} else {
					$firstPageToLinkTo = 1;
				}
			}
			
			// last numbered link to display
			if ($currentPage >= ($numberOfPages - 2)) {
				$lastPageToLinkTo = $numberOfPages;
			} else {
				if ($currentPage == 1) {
					$lastPageToLinkTo = $currentPage + 4;
				} elseif ($currentPage == 2) {
					$lastPageToLinkTo = $currentPage + 3;
				} else {
					$lastPageToLinkTo = $currentPage + 2;
				}
			}
		}

		if ($numberOfPages > 1) {
			
			$h .= "<nav>";
				
				$h .= "<ul class=\"pagination pagination-sm\">";
				
					$h .= "<li" . ($currentPage==1?' class="disabled"':'') . ">";
						$h .= "<a href=\"" . $firstPageURL . "\"><span class=\"fas fa-fast-backward\"></span></a>";
					$h .= "</li>";
					
					$h .= "<li" . ($currentPage==1?' class="disabled"':'') . ">";
						$h .= "<a href=\"" . $previousPageURL . "\"><span class=\"fas fa-step-backward\"></span></a>";
					$h .= "</li>";

					for ($page = $firstPageToLinkTo; $page <= $lastPageToLinkTo; $page++) {
						$class = "";
						if ($page==$currentPage) { $class = 'active'; }
						elseif ($page==$firstPageToLinkTo||$page==$lastPageToLinkTo||($currentPage==1&&$page==4)) { $class = 'hidden-xs'; }
						$h .= "<li class=\"" . $class . "\"><a href=\"" . $lang . $linkBaseURL . $page . "/\">" . $page . "</a></li>";
					}

					$h .= "<li" . ($currentPage==$numberOfPages?' class="disabled"':'') . ">";
						$h .= "<a href=\"" . $nextPageURL . "\"><span class=\"fas fa-step-forward\"></span></a>";
					$h .= "</li>";
					
					$h .= "<li" . ($currentPage==$numberOfPages?' class="disabled"':'') . ">";
						$h .= "<a href=\"" . $lastPageURL . "\"><span class=\"fas fa-fast-forward\"></span></a>";
					$h .= "</li>";
				
				$h .= "</ul>";
				
			$h .= "</nav>";
			
		}
		
		return $h;

	}

}

?>