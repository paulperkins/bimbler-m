/**
 * Bimbler Mobile Maps
 *
 * @author    Paul Perkins <paul@paulperkins.net>
 * @license   GPL-2.0+
 * @link      http://bimblers.com/plugins
 * @copyright 2015 Paul Perkins
 */

jQuery(document).ready(function ($) {


	// Populate the DIV which is part of the 'Our Next Ride' front-page post.
	if (document.getElementById('bimbler-next-ride-map')) {

		console.log ('Got map element');
	
		var brisbane = new google.maps.LatLng(-27.471010, 153.023453);

		var map_div = document.getElementById('bimbler-next-ride-map');
			
	    // Get the RWGPS ID.
		//var rwgps_id = document.getElementById('bimbler-next-ride-map').getAttribute('data-rwgps-id');
		var rwgps_id = map_div.getAttribute('data-rwgps-id');
	
		//console.log ('RWGPS: ' + rwgps_id);

	    var options = {
	            zoom: 17,
	            center: brisbane,
	            //disableDefaultUI: true,
	            //draggable: false,
	            scrollwheel: false, 
	            //disableDoubleClickZoom: true
	        };
		
	    // init map
	    //var ride_map = new google.maps.Map(document.getElementById('bimbler-next-ride-map'), options);
	    var ride_map = new google.maps.Map(map_div, options);
	    
	    if (rwgps_id) {
		    var ctaLayer = new google.maps.KmlLayer({
			    url: 'http://ridewithgps.com/routes/' + rwgps_id + '.kml',
	    		    suppressInfoWindows: true,
	    		  });
		    
	    	ctaLayer.setMap(ride_map);
	    }

		var venue_address_enc = map_div.getAttribute('data-venue-address');

		if (venue_address_enc) {

			var venue_address = decodeURIComponent(venue_address_enc);

			var geocoder= new google.maps.Geocoder();
			
			geocoder.geocode( 
				{ 'address': venue_address }, 
				function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {

						var marker = new google.maps.Marker(
							{
								map: ride_map,
								animation: google.maps.Animation.DROP,
								position: results[0].geometry.location
							}
						);

						var venue_name_enc = map_div.getAttribute('data-venue-name');

						if (venue_name_enc) {

							var venue_name = decodeURIComponent(venue_name_enc);

							var contentString = '<div id="content">'+
								'<p>' + venue_name + '</p>';

							var infowindow = new google.maps.InfoWindow({
								content: contentString
								});

							infowindow.open(ride_map, marker);

							marker.addListener('click', function() {
								infowindow.open(ride_map, marker);
							});								
						}

						ride_map.setCenter(marker.getPosition());
					}
				}
			);
			
		}

	} else {

				console.log ('NOT Got map element');

	}	
	
	// Detect if we're running on a mobile device, and adjust the map DIV if so to make it visible.
	if (navigator.userAgent.indexOf('iPhone') != -1 || navigator.userAgent.indexOf('Android') != -1 ) {

		var mapdiv = document.getElementById('bimbler-next-ride-map');
		
		if (mapdiv) {
			mapdiv.style.width = '100%';
			mapdiv.style.height = '100%';
		}
	}


});

