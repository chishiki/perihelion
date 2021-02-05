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

	public function addressManager($addressObject, $addressObjectID, $baseFormURL, $useLocationPicker = false) {

		$h = $this->addressForm($addressObject, $addressObjectID, $baseFormURL, $useLocationPicker);
		$h .= $this->addressList($addressObject, $addressObjectID, $baseFormURL);
		return $h;

	}

	public function addressForm($addressObject, $addressObjectID, $baseFormURL, $useLocationPicker = false) {
		
		$h = '<form id="address_manager_' . strtolower($addressObject) . '" class="address-manager-form" name="address-manager"  method="post" action="' . $baseFormURL . 'create/">';
	
			$h .= '<input type="hidden" name="addressObject" value="' . $addressObject . '">';
			$h .= '<input type="hidden" name="addressObjectID" value="' . $addressObjectID  . '">';

			$h .= '

				<div class="form-row">
				
					<div class="form-group col-12 col-sm-6 col-md-2">
						<label for="state">' . Lang::getLang('state') . '</label>
						<input type="text" class="form-control" name="state" value="">
					</div>
					
					<div class="form-group col-12 col-sm-6 col-md-2">
						<label for="city">' . Lang::getLang('city') . '</label>
						<input type="text" class="form-control" name="city" value="">
					</div>

					<div class="form-group col-12 col-sm-6 col-md-2">
						<label for="streetAddress1">' . Lang::getLang('streetAddress1') . '</label>
						<input type="text" class="form-control" name="streetAddress1" value="">
					</div>

					<div class="form-group col-12 col-sm-6 col-md-2">
						<label for="streetAddress2">' . Lang::getLang('streetAddress2') . '</label>
						<input type="text" class="form-control" name="streetAddress2" value="">
					</div>

					<div class="form-group col-12 col-sm-6 col-md-2">
						<label for="country">' . Lang::getLang('country') . '</label>
						' . $this->countryDropdown('JP') . '
					</div>

					<div class="form-group col-12 col-sm-6 col-md-2">
						<label for="postalCode">' . Lang::getLang('postalCode') . '</label>
						<input type="text" class="form-control" name="postalCode" value="">
					</div>
					
				</div>

				<hr />
				
				';

				if ($useLocationPicker) {

					$site = new Site($_SESSION['siteID']);

					$h .= '

						<div class="form-row">
							<div class="form-group col-12">
								<label for="locationInput">' . Lang::getLang('location') . '</label>
								<input type="text" id="locationInput" name="locationInput" class="form-control">
							</div>
						</div>
		
						<div class="form-row">
							<div class="form-group col-12">
								<div id="map-canvas"></div>
							</div>					
						</div>
		
						<div class="form-row">
							
							<div class="form-group col-12 col-sm-4 col-md-3">
								<label for="latitude">' . Lang::getLang('latitude') . '</label>
								<input type="text" id="latitude" name="latitude" class="form-control" placeholder="0.000000" value="">
							</div>
					
							<div class="form-group col-12 col-sm-4 col-md-3">
								<label for="longitude"">' . Lang::getLang('longitude') . '</label>
								<input type="text" id="longitude" name="longitude" class="form-control" placeholder="0.000000" value="">
							</div>
				
						</div>
		
						<script>
							$(\'#map-canvas\').locationpicker({
								location: {
									latitude: ' . $site->defaultLatitude . ',
									longitude: ' . $site->defaultLongitude . '
								},
								zoom: 15,
								radius: 100,
								mapTypeId: google.maps.MapTypeId.SATELLITE,
								mapTypeControl: true,
								inputBinding: {
									latitudeInput: $(\'#latitude\'),
									longitudeInput: $(\'#longitude\'),
									locationNameInput: $(\'#locationInput\')
								},
								enableAutocomplete: true
							});
						</script>
		
						<hr />
						
					';

				}

		$h .= '
				<div class="text-right">
					<button type="submit" class="btn btn-outline-success"><span class="fas fa-plus"></span> ' . Lang::getLang('addAddress') . '</button>
				</div>
			</form>
		';
		
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

							$h .= '<th class="text-center">' . Lang::getLang('state') . '</th>';
							$h .= '<th class="text-center">' . Lang::getLang('city') . '</th>';
							$h .= '<th>' . Lang::getLang('streetAddress') . '</th>';
							$h .= '<th class="text-center">' . Lang::getLang('country') . '</th>';
							$h .= '<th class="text-center">' . Lang::getLang('postalCode') . '</th>';
							$h .= '<th class="text-center">' . Lang::getLang('latitude') . '</th>';
							$h .= '<th class="text-center">' . Lang::getLang('longitude') . '</th>';
							$h .= '<th class="text-center">' . Lang::getLang('addressDefault') . '</th>';
							$h .= '<th class="text-center">' . Lang::getLang('delete') . '</th>';
						$h .= '</tr>';					
					
						if (!empty($addresses)) {

							foreach ($addresses as $addressID) {
								
								$a = new Address($addressID);
								$delete =  $baseFormURL . 'delete/' . $addressID . '/';

								$h .= '<tr>';

									$h .= '<td class="text-center">' . $a->state . '</td>';
									$h .= '<td class="text-center">' . $a->city . '</td>';
									$h .= '<td>' . $a->streetAddress1 . ($a->streetAddress2?' '.$a->streetAddress2:'') . '</td>';
									$h .= '<td class="text-center">' . $c->getCountry($a->country, $_SESSION['lang']) . '</td>';
									$h .= '<td class="text-center">' . $a->postalCode . '</td>';
									$h .= '<td class="text-center">' . $a->latitude . '</td>';
									$h .= '<td class="text-center">' . $a->longitude . '</td>';
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