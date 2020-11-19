<?php

class PanelView {
	
	public function panel($id = '', $columns = array(), $panelTitle = '', $panelBody = '') {

		$panel = '
		
		<div id="' . $id . '">
			<div class="container">
				<div class="row">
					<div class="' . implode(' ',$columns) . '">
						<div class="card" >
							<div class="card-header">
								<div class="card-title">' . Lang::getLang($panelTitle) . '</div>
							</div>
							<div class="card-body">' . $panelBody . '</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		';
		
		return $panel;
	
	}
	
}

?>