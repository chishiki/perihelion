<?php

final class PaginationView {
	
	public static function paginate($numberOfPages = 1, $currentPage = 1, $linkBaseURL = '') {
		
		$h = "";

		$firstPageURL = $linkBaseURL . "1/";
		if ($currentPage == 1) { $previousPageURL = "#"; } else { $previousPageURL = $linkBaseURL . ($currentPage - 1) . "/"; }
		if ($currentPage == $numberOfPages) { $nextPageURL = "#"; } else { $nextPageURL = $linkBaseURL . ($currentPage + 1) . "/"; }
		$lastPageURL = $linkBaseURL . $numberOfPages . "/";

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
			
			$h .= '<nav class="overflow-auto">';
				
				$h .= '<ul class="pagination pagination-sm d-flex">';
				
					$h .= '<li class="page-item flex-fill' . ($currentPage==1?' disabled':'') . '">';
						$h .= '<a class="page-link text-center" ' . ($currentPage==1?'tabindex="-1" ':'') . 'href="' . $firstPageURL . '">';
							$h .= '<span class="fas fa-fast-backward">&nbsp;</span>';
						$h .= '</a>';
					$h .= '</li>';
					
					$h .= '<li class="page-item flex-fill' . ($currentPage==1?' disabled':'') . '">';
						$h .= '<a class="page-link text-center" ' . ($currentPage==1?'tabindex="-1" ':'') . 'href="' . $previousPageURL . '">';
							$h .= '<span class="fas fa-step-backward"></span>';
						$h .= '</a>';
					$h .= '</li>';

					for ($page = $firstPageToLinkTo; $page <= $lastPageToLinkTo; $page++) {
						$class = '';
						if ($page==$currentPage) { $class = ' active'; }
						$h .= '<li class="page-item flex-fill' . ($page==$currentPage?' active':'') . '">';
							$h .= '<a class="page-link text-center" href="' . $lang . $linkBaseURL . $page . '/">' . $page . '</a>';
						$h .= '</li>';
					}

					$h .= '<li class="page-item flex-fill' . ($currentPage==$numberOfPages?' disabled':'') . '">';
						$h .= '<a class="page-link text-center" ' . ($currentPage==$numberOfPages?'tabindex="-1" ':'') . 'href="' . $nextPageURL . '">';
							$h .= '<span class="fas fa-step-forward"></span>';
						$h .= '</a>';
					$h .= '</li>';
					
					$h .= '<li class="page-item flex-fill' . ($currentPage==$numberOfPages?' disabled':'') . '">';
						$h .= '<a class="page-link text-center" ' . ($currentPage==$numberOfPages?'tabindex="-1" ':'') . 'href="' . $lastPageURL . '">';
							$h .= '<span class="fas fa-fast-forward"></span>';
						$h .= '</a>';
					$h .= '</li>';
				
				$h .= '</ul>';
				
			$h .= '</nav>';
			
		}
		
		return $h;

	}

}

?>