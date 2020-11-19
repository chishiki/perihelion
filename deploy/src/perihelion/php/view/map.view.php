<?php

class MapView {

	private $urlArray;
	private $inputArray;
	private $errorArray;
	
	public function __construct($urlArray = array(), $inputArray = array(),  $errorArray = array()) {

		$this->urlArray = $urlArray;
		$this->inputArray = $inputArray;
		$this->errorArray = $errorArray;

	}
	
	public function map($properties = array()) {
		
		$h = '<div id="map-canvas" class="google-map-canvas map-view"></div>';
		$h .= self::perihelionIndexMapNew($properties);
		return $h;
		
	}
	
	public static function perihelionPropertyMap($propertyID = 0) {

		$siteID = $_SESSION['siteID'];
		$site = new Site($siteID);
		$mapZoom = $site->propertyMapZoom;
		$mapType = $site->propertyMapType;
		$mapCenterCoordinates = $site->propertyMapCenterCoordinates;

		$property = new Property($propertyID);
		if ($property->propertyMapZoom) { $mapZoom = $property->propertyMapZoom; } else { $mapZoom = 13; }
		$mapType = 'HYBRID';
		$mapCenterCoordinates = '42.858655,140.704880';


		$h = "\n\n\t<script type=\"text/javascript\">\n";

			$h .= "\t\tvar locations = [\n";
				$h .= self::perihelionMapPropertyTile($propertyID);
				$mapCenterCoordinates = $property->propertyLatitude . ',' . $property->propertyLongitude;
			$h .= "\n\t\t\t];\n";

			$h .= "
				var mapOptions = {
					zoom: " . $mapZoom . ",
					scrollwheel: false,
					center: new google.maps.LatLng(" . $mapCenterCoordinates . "),
					mapTypeId: google.maps.MapTypeId." . $mapType . "
				};
				map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
				var infowindow = new google.maps.InfoWindow(maxWidth=400);
			";

				$h .= "
					var marker, i;
					var bounds = new google.maps.LatLngBounds();
					
					for (i = 0; i < locations.length; i++) {  
					  marker = new google.maps.Marker({
						position: new google.maps.LatLng(locations[i][1], locations[i][2]),
						map: map
					  });
					  google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function() {
						  infowindow.setContent(locations[i][0]);
						  infowindow.open(map, marker);
						}
					  })(marker, i));
				";

				$h .= "\n\t\t\t\t}\n";

		$h .= "\t</script>\n";
		
		return $h;
		
	}

	public static function javaScriptGoogleMapsPopulatePropertiesUsingQuery($pq) {

		$properties = Property::search($pq);
		
		$mapType = 'ROADMAP';
		$mapType = 'SATELLITE';
		$mapZoom = 17;
		$mapCenterCoordinates = '42.856926,140.710627';
		
		
		
		
		
		$h = "\t<script type=\"text/javascript\">\n";
		
		
			$h .= "\t\tvar locations = [\n";

				foreach ($properties AS $thisPropertyID) { $h .= self::perihelionMapPropertyTile($thisPropertyID); }
				
			$h .= "\n\t\t\t];\n";
			
			
			$h .= "
			
				var mapOptions = {
					zoom: " . $mapZoom . ",
					scrollwheel: false,
					center: new google.maps.LatLng(" . $mapCenterCoordinates . "),
					mapTypeId: google.maps.MapTypeId." . $mapType . "
				};
				map = new google.maps.Map(document.getElementById('search-results-map-canvas'),mapOptions);
				var infowindow = new google.maps.InfoWindow(maxWidth=400);
				
			";
			
			$h .= "
				google.maps.event.trigger(map, 'resize');
				map.setZoom( map.getZoom() );
				
			";

			if (count($properties) > 0) {
				
				$h .= "
				
					var marker, i;
					var bounds = new google.maps.LatLngBounds();
					
					for (i = 0; i < locations.length; i++) {  
					  marker = new google.maps.Marker({
						position: new google.maps.LatLng(locations[i][1], locations[i][2]),
						map: map
					  });
					  google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function() {
						  infowindow.setContent(locations[i][0]);
						  infowindow.open(map, marker);
						}
					  })(marker, i));
					  
					  if (locations[i][1] > 0) {
						bounds.extend(marker.position); 
					  }
					}
					
					map.fitBounds(bounds); 
					
				";
			}
		
		$h .= "\t</script>\n";
		
		$h .= "
		<script>

			$('a[href=\"#propertyMap\"]').on('shown', function() {   // When tab is displayed...
				var map = googleMaps[0],
					center = map.getCenter();
				google.maps.event.trigger(map, 'resize');         // fixes map display
				map.setCenter(center);                            // centers map correctly
			});

		</script>
		
		";
		
		return $h;
	
	}

	public static function perihelionIndexMap($properties = array()) {

		$site = new Site($_SESSION['siteID']);
		$mapZoom = $site->propertyMapZoom;
		$mapType = $site->propertyMapType;
		$mapCenterCoordinates = $site->propertyMapCenterCoordinates;

		$h = "\n\n<!-- START perihelionIndexMap -->\n\n";
		$h .= "<script type=\"text/javascript\">\n\n";
		
			$h .= "\tvar map;\n";
			$h .= "\tvar infoWindow;\n\n";
			
			$h .= "\tfunction initMap() {\n\n";

				$h .= "\t\tvar locations = [\n\n";
					foreach ($properties AS $thisPropertyID) { $h .= self::perihelionMapPropertyTile($thisPropertyID); }
				$h .= "\n\t\t];\n";

				$h .= "
		var mapOptions = {
			zoom: " . $mapZoom . ",
			scrollwheel: false,
			center: new google.maps.LatLng(" . $mapCenterCoordinates . "),
			mapTypeId: google.maps.MapTypeId." . $mapType . "
		};
		map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
		var infowindow = new google.maps.InfoWindow(maxWidth=400);
				";

			$h .= "
				
		var marker, i;
		var bounds = new google.maps.LatLngBounds();

		for (i = 0; i < locations.length; i++) { 
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(locations[i][1], locations[i][2]),
				map: map
			});
			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					infowindow.setContent(locations[i][0]);
					infowindow.open(map, marker);
				}
			})(marker, i));
			";

			if (count($properties) > 1) {
				// $h .= "if (locations[i][1] > 0) { bounds.extend(marker.position); }\n";
				// $h .= "map.fitBounds(bounds);\n\n";
			}

				$h .= "\n\t\t}\n\n";

			$h .= "\t}\n\n";

		$h .= "</script>\n\n";
		
		$h .= "<script async defer src=\"https://maps.googleapis.com/maps/api/js?key=" . Map::googleApiKey() . "&callback=initMap\"></script>\n\n";
		
		$h .= "<!-- END perihelionIndexMap -->\n\n";
		
		return $h;

	}

	public static function perihelionIndexMapNew($properties = array()) {

		$site = new Site($_SESSION['siteID']);

		$propertyLocations = '';
		foreach ($properties AS $thisPropertyID) { $propertyLocations .= self::perihelionMapPropertyTile($thisPropertyID); }

		$mapAreas = self::perihelionMapDrawPropertyArea();

		$h = "
		
		<!-- START perihelionIndexMap -->
		<script type=\"text/javascript\">
		
			var map;
			var infoWindow;
			
			function initMap() {
				
				var locations = [\n\n" . $propertyLocations . "\n\t\t];

				var mapOptions = {
					zoom: " . $site->propertyMapZoom . ",
					scrollwheel: false,
					center: new google.maps.LatLng(" . $site->propertyMapCenterCoordinates . "),
					mapTypeId: google.maps.MapTypeId." . $site->propertyMapType . "
				};
				
				map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
				
				var infowindow = new google.maps.InfoWindow(maxWidth=400);
				
				" . $mapAreas . "
				
				var marker, i;
				var markers = [];
				var bounds = new google.maps.LatLngBounds();

				for (i = 0; i < locations.length; i++) {

					marker = new google.maps.Marker({
						position: new google.maps.LatLng(locations[i][1], locations[i][2]),
						map: map
					});
					google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function() {
							infowindow.setContent(locations[i][0]);
							infowindow.open(map, marker);
						}
					})(marker, i));
					
					markers.push(marker);

				}

				var markerCluster = new MarkerClusterer(
					map, 
					markers, 
					{imagePath: '/zeni/vendor/googlemaps/v3-utility-library/blob/master/markerclusterer/images/m'}
				);

			}
			
		</script>
		
		<script type=\"text/javascript\" src=\"/zeni/vendor/googlemaps/v3-utility-library/master/markerclusterer/src/markerclusterer.js\"></script>
		<script async defer src=\"https://maps.googleapis.com/maps/api/js?key=" . Map::googleApiKey() . "&callback=initMap\"></script>
		
		<!-- END perihelionIndexMap -->
		
		";
		
		return $h;

	}

	public static function perihelionMapPropertyTile($propertyID) {
	
		$site = new Site($_SESSION['siteID']);
		$property = new Property($propertyID);
		$propertySite = new Site($property->siteID);
		$type = new PropertyType($property->propertyTypeID);
		$propertyURL = $property->portalURL();
		$propertyType = $type->getPropertyTypeName();
		$propertyNumberOfBedrooms = number_format($property->propertyNumberOfBedrooms);
		$propertyFloorArea = number_format($property->propertyFloorArea) . '&#13217;';
		$propertyLandSize = number_format($property->propertyLandSize) . '&#13217;';
		$propertyIsForSale = $property->propertyIsForSale;
		$propertyIsLand = $property->propertyIsLand;
		$propertyPrice =  '&yen;' . number_format($property->propertyPrice);
		if ($property->propertyPrice == 0) { $propertyPrice = 'ASK'; }
		$propertyName = $property->name();

		$h = "\t\t\t['";
		
		$h .= "<div class=\"maplet\">";
			$h .= "<a href=\"" . $propertyURL . "\">";
				$h .= '<div>';
					$h .= $propertyName;
					$h .= '<br />';
					$h .= '<img src="/image/' . Image::getMainImageID('Property',$propertyID) . '/150/">';
				$h .= '</div>';
			$h .= "</a>";
			$h .= "<p>";
				$h .= '- ' . Lang::getLang('propertyType') . ': ' . $propertyType . '<br />';
				if ($propertyIsLand == 0) {
					if ($propertyNumberOfBedrooms > 0) { $h .= '- ' . Lang::getLang('beds') . ': ' . $propertyNumberOfBedrooms . '<br />'; }
					if ($propertyFloorArea > 0) { $h .= '- ' . Lang::getLang('floorArea') . ': ' . $propertyFloorArea . '<br />'; }
				} elseif ($propertyIsLand == 1) {
					if ($propertyLandSize > 0 ) { $h .= '- ' . Lang::getLang('landSize') . ': ' . $propertyLandSize . '<br />'; }
				}
				if ($property->propertyUnderNegotiation) { 
					$h .= '- ' . Lang::getLang('propertyUnderNegotiation');
				} elseif ($property->propertySold) { 
					$h .= '- ' . Lang::getLang('propertySold');
				} else {
					$h .= '- ' . Lang::getLang('propertyPrice') . ': ' . $propertyPrice;
				}
			$h .= "</p>";
		$h .= "</div>";

		$h .= "', " . $property->propertyLatitude . ", " . $property->propertyLongitude . "],\n";
		if ($propertyID) { $mapCenterCoordinates = $property->propertyLatitude . ',' . $property->propertyLongitude; }	
		
		return $h;
		
	}
	
	public static function perihelionMapDrawPropertyArea($propertyAreaID) {
		
		$area = new PropertyArea($propertyAreaID);
		$naming_prefix = str_replace('-', '', $area->propertyAreaSeoUrl);
		
		$h = "
		
		// ======= " . $area->getPropertyAreaName() . " ======= //
		
		var " . $naming_prefix . "Coords = [
			" . str_replace("\n","",$area->propertyAreaCoordinates) . "
		];
		
		var " . $naming_prefix . "Area = new google.maps.Polygon({
			paths: " . $naming_prefix . "Coords,
			strokeColor: '#337AB7',
			strokeOpacity: 0.5,
			strokeWeight: 3,
			fillColor: '#FFFFFF',
			fillOpacity: 0.2
		});
		
		" . $naming_prefix . "Area.setMap(map);

		google.maps.event.addListener(" . $naming_prefix . "Area, 'click', function(event) {
			var pos = event.latLng;
			infowindow.setPosition(pos);
			infowindow.setContent('<a href=\"/" . Lang::prefix() . "area/" . $area->propertyAreaSeoUrl . "/\">" . $area->getPropertyAreaName() . "</a>');
			infowindow.open(map, " . $naming_prefix . "Area);
		});
		
		";
		
		return $h;

	}
	
	public static function perihelionMapStyles() {

		$h = '
			styles: [
				{
					"featureType": "administrative",
					"elementType": "labels.text.fill",
					"stylers": [{"color": "#444444"}]
				},
				{
					"featureType": "landscape",
					"elementType": "all",
					"stylers": [{"color": "#f2f2f2"}]
				},
				{
					"featureType": "poi",
					"elementType": "all",
					"stylers": [{"visibility": "off"}]
				},
				{
					"featureType": "road",
					"elementType": "all",
					"stylers": [{"saturation": -100},{"lightness": 45}]
				},
				{
					"featureType": "road.highway",
					"elementType": "all",
					"stylers": [{"visibility": "simplified"}]
				},
				{
					"featureType": "road.arterial",
					"elementType": "labels.icon",
					"stylers": [{"visibility": "off"}]
				},
				{
					"featureType": "transit",
					"elementType": "all",
					"stylers": [{"visibility": "off"}]
				},
				{
					"featureType": "water",
					"elementType": "all",
					"stylers": [{"color": "#0088f6"},{"visibility": "on"}]
				},
				{
					"featureType": "water",
					"elementType": "geometry.fill",
					"stylers": [{"color": "#1e73be"}]
				}
			]
		';
		
		return $h;

	}
	
	public static function perihelionPropertyAreaManagerMap($propertyAreaID) {

		$site = new Site($_SESSION['siteID']);
		$mapZoom = $site->propertyMapZoom;
		$mapType = $site->propertyMapType;
		$mapCenterCoordinates = $site->propertyMapCenterCoordinates;

		$area = new PropertyArea($propertyAreaID);
		$naming_prefix = str_replace('-', '', $area->propertyAreaSeoUrl);

		$h = "

		<!-- START perihelionPropertyAreaManagerMap -->
		
		<div id=\"map-canvas\" class=\"google-map-canvas map-view\"></div>';
		
		<script type=\"text/javascript\">
			
			var map;
			var paths;
			function initMap() {

				map = new google.maps.Map(document.getElementById('map-canvas'));

				var propertyAreaCoords = [
					" . str_replace("\n","",$area->propertyAreaCoordinates) . "
				];
				var propertyArea = new google.maps.Polygon({
					paths: propertyAreaCoords,
					strokeColor: '#337AB7',
					strokeOpacity: 0.5,
					strokeWeight: 3,
					fillColor: '#FFFFFF',
					fillOpacity: 0.2,
					editable: true,
					// draggable: true
				});
				propertyArea.setMap(map);

				var bounds = new google.maps.LatLngBounds();
				propertyArea.getPath().forEach(function (path, index) {
					bounds.extend(path);
				});
				map.fitBounds(bounds);
				
				var setCoordinates = function() {
					c = '';
					for (var i = 0; i < propertyArea.getPath().getLength(); i++) {
						c += '{lat: ' + propertyArea.getPath().getAt(i).lat().toFixed(6) + ', lng: ' + propertyArea.getPath().getAt(i).lng().toFixed(6) + '},';
					}
					document.getElementById(\"propertyAreaCoordinates\").value = c.slice(0,-1);
				}
				
				propertyArea.getPaths().forEach(function(path, index){
					google.maps.event.addListener(path, 'insert_at', function(){ setCoordinates(); });
					google.maps.event.addListener(path, 'remove_at', function(){ setCoordinates(); });
					google.maps.event.addListener(path, 'set_at', function(){ setCoordinates(); });
				});
				google.maps.event.addListener(propertyArea, 'dragend', function(){ setCoordinates(); });
	
			}
			
		</script>
		
		<script async defer src=\"https://maps.googleapis.com/maps/api/js?key=" . Map::googleApiKey() . "&callback=initMap\"></script>
		
		<!-- END perihelionPropertyAreaManagerMap -->
		
		";
		
		return $h;

	}

	
	
	
}

?>