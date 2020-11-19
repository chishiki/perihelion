<?php

class AddressView {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray, $inputArray, $errorArray) {
		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;
	}

	public function addressManager($addressObject, $addressObjectID, $baseFormURL) {

		$h = $this->addressForm($addressObject, $addressObjectID, $baseFormURL);
		$h .= $this->addressList($addressObject, $addressObjectID, $baseFormURL);
		return $h;

	}

	public function addressForm($addressObject, $addressObjectID, $baseFormURL) {
		
		$h = '<form id="address_manager_' . strtolower($addressObject) . '" class="address-manager-form" name="address-manager"  method="post" action="' . $baseFormURL . 'create/">';
	
			$h .= '<input type="hidden" name="addressObject" value="' . $addressObject . '">';
			$h .= '<input type="hidden" name="addressObjectID" value="' . $addressObjectID  . '">';

			$h .= '
				<div class="row">
					<div class="col-12 col-sm-6">

						<div class="form-row">
							<div class="form-group col-12">
								<label for="streetAddress1">' . Lang::getLang('streetAddress1') . '</label>
								<input type="text" class="form-control" name="streetAddress1" value="">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-12">
								<label for="streetAddress2">' . Lang::getLang('streetAddress2') . '</label>
								<input type="text" class="form-control" name="streetAddress2" value="">
							</div>
						</div>

					</div>

					<div class="col-12 col-sm-6">

						<div class="form-row">
							<div class="form-group col-12 col-sm-6">
								<label for="city">' . Lang::getLang('city') . '</label>
								<input type="text" class="form-control" name="city" value="">
							</div>
							<div class="form-group col-12 col-sm-6">
								<label for="state">' . Lang::getLang('state') . '</label>
								<input type="text" class="form-control" name="state" value="">
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-12 col-sm-6">
								<label for="country">' . Lang::getLang('country') . '</label>
								' . $this->countryDropdown('JP') . '
							</div>
		
							<div class="form-group col-12 col-sm-6">
								<label for="postalCode">' . Lang::getLang('postalCode') . '</label>
								<input type="text" class="form-control" name="postalCode" value="">
							</div>
						</div>

					</div>
				</div>

				<hr />

				<div class="text-right">
					<button type="submit" class="btn btn-outline-success"><span class="fas fa-plus"></span> ' . Lang::getLang('addAddress') . '</button>
				</div>
			';

		$h .= '</form>';
		
		return $h;
		
	}
	
	public function addressList($addressObject, $addressObjectID, $baseFormURL) {
		
		$addy = new Addresses($addressObject, $addressObjectID);
		$addresses = $addy->list();
		$c = new Countries();
		
		$h = '<hr />';
		
		$h .= '<div class="row">';
			$h .= '<div class="col-12">';
				$h .= '<div class="table-responsive">';
					$h .= '<table class="table table-striped">';
					
						$h .= '<tr>';
							$h .= '<th>' . Lang::getLang('streetAddress') . '</th>';
							$h .= '<th class="text-center">' . Lang::getLang('city') . '</th>';
							$h .= '<th class="text-center">' . Lang::getLang('state') . '</th>';
							$h .= '<th class="text-center">' . Lang::getLang('country') . '</th>';
							$h .= '<th class="text-center">' . Lang::getLang('postalCode') . '</th>';
							$h .= '<th class="text-center">' . Lang::getLang('addressDefault') . '</th>';
							$h .= '<th class="text-center">' . Lang::getLang('delete') . '</th>';
						$h .= '</tr>';					
					
						if (!empty($addresses)) {

							foreach ($addresses as $addressID) {
								
								$a = new Address($addressID);
								$delete =  $baseFormURL . 'delete/' . $addressID . '/';

								$h .= '<tr>';
									$h .= '<td>' . $a->streetAddress1 . ($a->streetAddress2?' '.$a->streetAddress2:'') . '</td>';
									$h .= '<td class="text-center">' . $a->city . '</td>';
									$h .= '<td class="text-center">' . $a->state . '</td>';
									$h .= '<td class="text-center">' . $c->getCountry($a->country, $_SESSION['lang']) . '</td>';
									$h .= '<td class="text-center">' . $a->postalCode . '</td>';
									$h .= '<td class="text-center">';
										if ($a->addressDefault) {
											$h .= '<span class="fas fa-check"></span>';
										} else {
											$h .= '<a class="btn btn-outline-secondary btn-sm" role="button" href="' . $baseFormURL . 'set-default/' . $addressID .'/">';
												$h .= '<span class="fas fa-check"></span>';
											$h .= '</a>';
										}
									$h .= '</td>';
									$h .= '<td class="text-center table-action-column">';
										$h .= '<a class="btn btn-danger btn-sm" href="' . $delete . '"><span class="fas fa-trash-alt" style="color:#fff;"></span></a>';
									$h .= '</td>';
								$h .= '</tr>';
								
							}
							
						}
						
					$h .= '</table>';
				$h .= '</div>';
			$h .= '</div>';
		$h .= '</div>';

		return $h;
		
	}
	
	private function countryDropdown($selectedCountry, $includeEmpty = true) {

		$c = new Countries();
		$countries = $c->list($_SESSION['lang']);
		
		$dropdown = '<select class="form-control" name="country">';
			if ($includeEmpty) { $dropdown .= '<option value="">--</option>'; }
			foreach ($countries AS $iso3166 => $country) {
				$dropdown .= '<option value="' . $iso3166 . '"' . ($selectedCountry==$iso3166?' selected':'') . '>' . $country . '</option>';
			}
		$dropdown .= '</select>';
		return $dropdown;

	}

}

?>