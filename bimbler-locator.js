
jQuery(document).ready(function ($) {
	
	var user_id = 0;
	var event_id = 0;
	var rwgps_id = 0;
	var nonce = '';
	var tracking = false;
	var run_update_ajax = false;
	var run_fetch_ajax = false;
	var updateTimer;
	
	var locator_map;
	
	// Counter to select colour for each marker. 
	var marker_count = 0;
	
	// Marker colour values. Reserve red for the ride leader and this user.
	var marker_colours = ['blue', 
	                      //'red', 
	                      'purple', 
	                      'yellow', 
	                      'green', 
	                      //'gray', 
	                      'orange', 
	                      //'white', 
	                      //'black', 
	                      'blue',
	                      'pink',
	                      'lightblue',
	                      //'brown'
	                      ];
	
	var person_markers = [];
	var me_marker;
	
	var brisbane = new google.maps.LatLng(-27.471010, 153.023453);
	var initialLocation = brisbane;
	
	// Set a default.
	var my_position = brisbane;
	
	window.showLocatorMap = function (e) {
	
		// If the map already exists, do nothing.
		if (locator_map) {
			return;
		}
		
		// Run once - create map and store global vars.
		if (canvas = document.getElementById('bimbler_mobile_locator_map_canvas')) {
			
			var markers = [];
		
		    // Get the event ID and user ID from the DIV metadata.
			event_id = canvas.getAttribute('data-event-id');
			user_id = canvas.getAttribute('data-user-id');
			rwgps_id = canvas.getAttribute('data-rwgps-id');
			nonce = canvas.getAttribute('data-nonce');
		
			console.log ('Event: ' + event_id + ', user ID ' + user_id);
	
			var options = {
		            zoom: 17,
		            center: initialLocation,
		            mapTypeId: google.maps.MapTypeId.ROADMAP,
		            //mapTypeControl: true
		        };
			
		    // Create the map.
		    var map = new google.maps.Map(canvas, options);
		    
		    locator_map = map;
	         
	/*	    // Add the start point to the map.
	         var marker = new google.maps.Marker({
	             position: initialLocation,
	             draggable: true,
	             animation: google.maps.Animation.DROP,
	             map: map,
	             title: 'The start point',
		         icon: new google.maps.MarkerImage("http://google.com/mapfiles/kml/paddle/go.png")
	         });
	         
	         var contentString = '<div id="content">'+
	         '<div id="siteNotice">'+
	         '</div>'+
	         '<h1 id="firstHeading" class="firstHeading">' + 'Start Point' + '</h1>'+
	         '<div id="bodyContent">'+
	         //'<p>X: ' + position.coords.longitude + ', Y: ' +  position.coords.latitude + '</p>' +
	         '</div>'+
	         '</div>';
	
		     var infowindow = new google.maps.InfoWindow({
		         content: contentString
		     });
	
		     google.maps.event.addListener(marker, 'click', function() {
		    	 infowindow.open(map,marker);
		     }); */
	     
		    
		    // Add the cycle layer to the map.
			var bikeLayer = new google.maps.BicyclingLayer();
		
			bikeLayer.setMap(map);
			  
			// And load the route map if it exists.
			if (rwgps_id) {
				//console.log ('Adding route map from RWGPS.');
				
		 		var ctaLayer = new google.maps.KmlLayer({
		    		  	url: 'http://ridewithgps.com/routes/' + rwgps_id + '.kml'
		    		    //url: 'http://ridewithgps.com/routes/6463068.kml'
		    		  });
		    		  ctaLayer.setMap(map);
			}
			
			// Allow Ajax to run, now that we're set up properly.
			run_update_ajax = true;
			run_fetch_ajax = true;

		}

		/*
		 *  Iterate over data.
			+---------------------+--------------+------+-----+-------------------+----------------+
			| Field               | Type         | Null | Key | Default           | Extra          |
			+---------------------+--------------+------+-----+-------------------+----------------+
			| id                  | mediumint(9) | NO   | PRI | NULL              | auto_increment |
			| time                | timestamp    | NO   |     | CURRENT_TIMESTAMP |                |
			| event               | bigint(20)   | NO   |     | NULL              |                |
			| user_id             | varchar(60)  | NO   |     | NULL              |                |
			| rsvp                | char(1)      | NO   |     | NULL              |                |
			| comment             | varchar(128) | YES  |     | NULL              |                |
			| attended            | char(1)      | YES  |     | NULL              |                |
			| no_show             | char(1)      | YES  |     | NULL              |                |
			| email_notifications | char(1)      | NO   |     | Y                 |                |
			| guests              | int(11)      | NO   |     | 0                 |                |
			+---------------------+--------------+------+-----+-------------------+----------------+
		 */
    	// Executed on a timer - refresh the markers for each user.  
		function update_markers (data) {
			
			// No RSVPs.
			if (!data) {
				return;
			}
			
			// Iterate each object returned.
			$.each (data,function (index, row) {
				
				console.dir (row);
				
				// Does this marker ID exist? If not, create. But only create if the user has a valid location - they've elected
				// to be tracked.
				// Make sure not to double-up on the current user - this will already have a marker. 
				if (!(row.user_id in person_markers) 
						&& (row.pos_lat && row.pos_lng)
						&& (row.user_id != user_id)) {

					var new_pos = new google.maps.LatLng(row.pos_lat, row.pos_lng);	

					console.log ('  Creating new marker for user ID ' + row.user_id);
					console.log ('    Row: ID ' + row.id + ', Event ID ' + row.event + ', User ' + row.user_id + ', Time ' + row.pos_time + ', current user ' + user_id);
					
					// Rotate through the marker colours.
					marker_colour = marker_colours[(marker_count++) % marker_colours.length];
			         
					var new_marker = new google.maps.Marker({
						position: new_pos,
						map: map,
						draggable: true, // TODO: Remove draggable.
						animation: google.maps.Animation.DROP,
						icon: new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/icons/" + marker_colour + ".png"),
						size: new google.maps.Size(42,68)
			         });

			         // Add the marker.
			         markers.push (new_marker);
			         
			         // Add the marker to the associative array.
			         person_markers[row.user_id] = new_marker;
			         
			         var contentString = '<div id="content">'+
			         '<div id="siteNotice">'+
			         '</div>'+
			         //'<h1 id="firstHeading" class="firstHeading">' + row.user_name + '</h1>'+
			         '<div id="bodyContent">'+
			         '<h4>' + row.user_name + '</h4>' +
			         '</div>'+
			         '</div>';

				     var infowindow = new google.maps.InfoWindow({
				         content: contentString
				     });

				     google.maps.event.addListener(new_marker, 'click', function() {
				    	 infowindow.open(map,new_marker);
				     });
				     
				     // Set the icon for the marker. This runs as an Ajax call.
				     //get_avatar (new_marker, row.user_id);
			         
					// initialLocation
				} else {
					
					// Only update other users' positions.
					if (row.user_id != user_id) {
					
						// Update existing marker location.
						//console.log ('Updating position for user ' + row.user_id);
	
						var pos;
						
						// Be defensive.
						if (person_markers[row.user_id]) {
							pos = person_markers[row.user_id].getPosition();	
						}

						var new_pos = new google.maps.LatLng(row.pos_lat, row.pos_lng);	
						
						// Update the marker if we need to.
						if (pos && (pos != new_pos)) {

							// User has deselected tracking - delete marker.
							if ((row.pos_lat == 0) && (row.pos_lng == 0)) {
								
								console.log ('User ' + row.user_id + ' is no longer tracking - deleting marker.');
								
								person_markers[row.user_id].setMap(null);
								person_markers[row.user_id] = null;
							} else {
								
								console.log ('  Updating marker for user ID ' + row.user_id);
		
								console.log ('  Position: ' + new_pos);
		
								person_markers[row.user_id].setPosition (new_pos);
							}
						}
					}
				}
			});
			
			//console.log ('  Current person_markers array: ' + person_markers);
			
			//console.log ('=========================================================');
		}
		

		function show_my_position () {
			
			if (!tracking) {
			
				return;
			}
			
			// If this device supports geolocation, show the current posision.
			if (navigator.geolocation) {
			     navigator.geolocation.getCurrentPosition(function (position) {

			    	 my_position = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

			    	 //console.log (my_position.toString());

			    	 // No marker exists - create a new one.
			    	 if (!me_marker) {
			    		 
			    		 console.log ("Creating new marker for current user's location.");
		
				         me_marker = new google.maps.Marker({
				             position: my_position,
				             animation: google.maps.Animation.DROP,
				             map: map,
				             title: 'You!'
				         });

				         // Pan to where we are now.
				         map.setCenter(my_position);
				         
				         var contentString = '<div id="content">'+
				         '<div id="siteNotice">'+
				         '</div>'+
				         //'<h1 id="firstHeading" class="firstHeading">' + row.user_name + '</h1>'+
				         '<div id="bodyContent">'+
				         '<h4>You!</h4>' +
				         '</div>'+
				         '</div>';

					     var infowindow = new google.maps.InfoWindow({
					         content: contentString
					     });

					     google.maps.event.addListener(me_marker, 'click', function() {
					    	 infowindow.open(map,me_marker);
					     });
			    	 } 
			    	 else { // Otherwise update the position of the existing marker.

			    		 //console.log ("Updating current user's location");
			    		 
			    		 me_marker.setPosition (my_position);
			    	 }
			     });
			}
		}
		
		function request(){
			
			// Prevent parallel Ajax calls.
			if (!run_fetch_ajax) {
				console.log ('Request Ajax already in progress.');
				return;
			}
        
			console.log ('Firing Locator Request Ajax');
			
			run_fetch_ajax = false;
			
			jQuery.ajax({
				type: "POST",
			     url: '/wp-admin/admin-ajax.php',
			     data: ({
			    	 action : 'locatorajax-submit',
			    	 nonce: nonce,
			    	 event : event_id 
			     	}
			    ),
			     success: function(response) {
 	       			console.log ('  Success: ' + response);
 	       			
 	       			update_markers (response);
 	       			
 	       			run_fetch_ajax = true;
			     },
			     error: function(response) {
  	       			console.log ('  Error: ' + response);
  	       			run_fetch_ajax = true;
 			     }
			});
		};

		window.update = function () {

			//console.log ('Update: tick.');
			
			if (!tracking) {
				
				return;
			}
			
			var wait = '<i class="fa fa-spinner fa-spin"></i>';
			
			var indicator = $("#bimbler-locator-indicator");
			
			// Set the indicator to an animation.
			indicator.html (wait);
			
			// First, update the current position and display the marker. Sets 'my_position'.
			show_my_position ();

			// Prevent parallel Ajax calls.
			if (!run_update_ajax) {
				console.log ('Update Ajax already in progress.');
				return;
			}
	        
			console.log ('Firing location updater Ajax.');

			if (!my_position) {
				console.log ('My position not set. Not updating.');
			} else {
			
				run_update_ajax = false;
				
				jQuery.ajax({
					type: "POST",
				     url: '/wp-admin/admin-ajax.php', 
				     data: ({
				    	 action : 	'locationupdateajax-submit',
				    	 event : 	event_id,
				    	 user_id: 	user_id,
				    	 nonce: 	nonce,
				    	 pos_lat: 	my_position.lat(),
				    	 pos_lng: 	my_position.lng()
				     	}
				    ),
				     success: function(response) {
				    	 if (response.status == 'success') {
				    		 console.log ('Success: ' + response);
				    	 } else {
				    		 console.log ('Success, with errors: ' + response);
				    	 }
	 	       			
	 	       			run_update_ajax = true;
				     },
				     error: function(response) {
	  	       			console.log ('Error: ' + response);
	  	       			
	  	       			run_update_ajax = true;
	 			     }
				});
			}

			//indicator.html ('');
		};

		
		window.update_null_location = function () {
			
			console.log ('Updating user with null location - turn off tracking.');
			
			jQuery.ajax({
				type: "POST",
			     url: '/wp-admin/admin-ajax.php',
			     data: ({
			    	 action : 	'locationupdateajax-submit',
			    	 event : 	event_id,
			    	 user_id: 	user_id,
			    	 nonce: 	nonce,
			    	 pos_lat: 	0,
			    	 pos_lng: 	0
			     	}
			    ),
			     success: function(response) {
			    	 if (response.status == 'success') {
			    		 console.log ('Success: ' + response);
			    	 } else {
			    		 console.log ('Success, with errors: ' + response);
			    	 }
 	       			
 	       			run_update_ajax = true;
			     },
			     error: function(response) {
  	       			console.log ('Error: ' + response);
  	       			
  	       			run_update_ajax = true;
 			     }
			});
			
		}
		
		function get_avatar(marker, user_id){

			console.log ('Firing Avatar Ajax');
			
			jQuery.ajax({
				type: "POST",
			     url: '/wp-admin/admin-ajax.php',
			     data: ({
			    	 action : 'avatarajax-submit',
			    	 user_id: user_id,
			    	 nonce: nonce,
			     	}
			    ),
			     success: function(response) {
 	       			//console.log ('Success: ' + response);
 	       			
 	       			//marker.setIcon (response);
 	       			
 	       			var icon = ({//new google.maps.Icon ({
 	       				url: response,
 	       				size: new google.maps.Size (20, 20)
 	       			});
 	       			
 	       			marker.setIcon (icon);
			     },
			     error: function(response) {
  	       			console.log ('Error: ' + response);
 			     }
			});
		};
		
		// Show the current user's location.
		//show_my_position ();
		
		// Get the initial set of positions.
		// TODO: remove comment-out.
//		request ();
	
		// Repeatedly fetch data on a timer.
		setInterval (
				function (){
					request ();
				}, 10*1000);  

		// Repeatedly update the current user's location on a timer.
		updateTimer = setInterval (
				function (){
					update ();
				}, 5*1000);
				
				 

	}

	$('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
		
		console.log ('bimbler-locator.js: ' + e.target.className.split(" ")[0] + ' clicked.');
		
		if ('bimbler_mobile_locator_tab' == e.target.className.split(" ")[0]) {
			
			showLocatorMap (e.target);
			
		} 
	})
	
	/*
	 * Handler for 'Track Me' toggle change.
	 */
	$('#bimbler-trackme-toggle').change(function(e) {	
		
		// Update the global. This will result in the position being updated
		// on a timer - nothing more to do.
		tracking = $(this).prop('checked');
		
		//console.log ('Tracking: ' + tracking);

		// Tracking turned on. 
		if (tracking) {
			//console.log ('Turning on tracking...');
			
			// TODO: See if this is really necessary.
			// Show the current user's location.
			update ();
			
		} else { // Tracking turned off.

			//console.log ('Turning off tracking...');
			
			// Delete our marker.
			if (me_marker) {
				
				me_marker.setMap(null);
				me_marker = null;
				my_position = null;
			}
			
			// Set the stored coords to (0,0).
			update_null_location();
			
			// Remove the animation.
			$("#bimbler-locator-indicator").html('');
		}
	})
	
	
});
