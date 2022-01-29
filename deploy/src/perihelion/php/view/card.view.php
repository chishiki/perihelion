<?php

class CardView {

	private $card;

	public function __construct($id, $containerClasses, $breadcrumbs, $colClasses, $header, $body, $collapsible = false, $collapsed = false) {

		$h = '<div id="' . $id . '" class="' . implode(' ',$containerClasses) . '">';
			$h .= $breadcrumbs;
			$h .= '<div class="row">';
				$h .= '<div class="' . implode(' ',$colClasses) . '">';
					$h .= '<div class="card">';
						$h .= '<div class="card-header"><h3>';
							if ($collapsible) { $h .= '<a data-toggle="collapse" href="#collapse_' . $id . '" aria-expanded="' . ($collapsed?'false':'true') . '" aria-controls="collapse_' . $id . '" id="collapse_heading_' . $id . '" class="d-block' . ($collapsed?' collapsed':'') . '">'; }
							$h .= $header;
							if ($collapsible) { $h .= '</a>'; }
						$h .= '</h3></div>';
						if ($collapsible) { $h .= '<div id="collapse_' . $id . '" class="collapse' . ($collapsed?'':' show') . '" aria-labelledby="collapse_heading_' . $id . '">'; }
							$h .= '<div class="card-body">' . $body . '</div>';
						if ($collapsible) { $h .= '</div>'; }
					$h .= '</div>';
				$h .= '</div>';
			$h .= '</div>';
		$h .= '</div>';

		$this->card = $h;

	}

	public function card() {
		return $this->card;
	}

}

?>