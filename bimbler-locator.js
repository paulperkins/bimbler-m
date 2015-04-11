
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
	var person_objects = [];
	var me_marker;
	var me_icon;
	
	var brisbane = new google.maps.LatLng(-27.471010, 153.023453);
	var initialLocation = brisbane;
	
	// Set a default.
	var my_position = brisbane;
	var my_speed = 0;
	var my_heading = 0;
	var my_timestamp = 0;
	
	var canvas;
	
	
	window.drawWhosWho = function (e) {
		
		var content = '';
		
		content = '<div stlye="display: block;">';
		
		style = 'margin-left: auto; margin-right: auto; display: block;';
		
		$.each (person_objects,function (index, row) {
		
			if (row) {
				content += '<div style="width:50px; display:inline-block;" data-user-id="' + row.user_id + '" class="bimbler-whoswho-marker">';
				content += '	<div style="' + style + '"><img src="http://maps.google.com/mapfiles/ms/icons/' + row.colour + '.png"></img></div>';  
				content += '	<div style="' + style + '">' + row.user_name + '</div>';  
				content += '</div>';
			}
		});
		
		content += '</div>';
		
		$("#bimbler-whos-who").html (content);
	}
	
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
	         
		    // Add the start point to the map.
/*	         var marker = new google.maps.Marker({
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
				
				//console.dir (row);

				// Get the age of the record - if too old then don't create pointer.
				var pointer_age = (my_timestamp - row.pos_time) / 60; // secs -> minutes.
				
				//console.log ('My time: ' + my_timestamp + ', row time: ' + row.pos_time + ', age: ' + pointer_age.toFixed(2) + ' mins.');
				//console.log ('Pointer age: ' + pointer_age);
				
				// Does this marker ID exist? If not, create. But only create if the user has a valid location - they've elected
				// to be tracked.
				// Make sure not to double-up on the current user - this will already have a marker. 
				if (!(row.user_id in person_markers) 
						&& (row.pos_lat && row.pos_lng)
						&& ((row.pos_lat != 0) && (row.pos_lng != 0))
						&& (row.user_id != user_id)
						&& ((my_timestamp > 0) && (pointer_age < 60))) {

					var new_pos = new google.maps.LatLng(row.pos_lat, row.pos_lng);	

					console.log ('  Creating new marker for user ID ' + row.user_id);
					console.log ('    Row: ID ' + row.id + ', Event ID ' + row.event + ', User ' + row.user_id + ', Age ' + pointer_age.toFixed(2) + ', current user ' + user_id);
					
					// Rotate through the marker colours.
					marker_colour = marker_colours[(marker_count++) % marker_colours.length];
					
		    		 var person_icon = {
			        		 path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
			        		 fillColor: marker_colour,
			        		 fillOpacity: 0.8,
			        		 strokeWeight: 1,
			        		 scale: 5
			        	 };
			         
					var new_marker = new google.maps.Marker({
						position: new_pos,
						icon: person_icon,
						map: map,
						//draggable: true, // TODO: Remove draggable.
						animation: google.maps.Animation.DROP,
						//icon: new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/icons/" + marker_colour + ".png"),
						//size: new google.maps.Size(42,68)
			         });

			         // Add the marker.
			         markers.push (new_marker);
			         
			         // Add the marker to the associative array.
			         person_markers[row.user_id] = new_marker;
			         
			         var person = {
				         user_id: 	row.user_id,
				         colour: 	marker_colour,
				         user_name: row.user_name,
				         marker: new_marker
			         };
			         
			         person_objects[row.user_id] = person;

			         
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
				     
				     drawWhosWho ();
			         
				} else { // Not a new record - update/delete existing markers.
					
					// Get the timestamp from our own row.
					if (row.user_id == user_id) {
						
						my_timestamp = row.pos_time;
						
					}
					
					// Only update other users' positions.
					if (row.user_id != user_id) {
					
						// Update existing marker location.
						//console.log ('Updating position for user ' + row.user_id);
	
						var pos;
						
						// Be defensive.
						if (person_markers[row.user_id]) {
							pos = person_markers[row.user_id].getPosition();	
						}
						
						//console.dir (new_pos);
						
						// Get the age of the record - if too old then delete pointer.
						var pointer_age = (my_timestamp - row.pos_time) / 60; // secs -> minutes.
						
						console.log ('  User ' + row.user_id + ' pos (' + row.pos_lat + ',' + row.pos_lng + '), age: ' + pointer_age.toFixed(2) + ' mins.');
						//console.log ('Pointer age: ' + pointer_age);

						var new_pos = new google.maps.LatLng(row.pos_lat, row.pos_lng);

						// Compare timestamp with my_timestamp - if too old, remove marker.
						// This gives the limitation that the current user has be be tracked in order to 
						// see other users' positions.
						if ((my_timestamp > 0) && (pointer_age > 60) && person_markers[row.user_id] && (row.pos_lat != 0) && (row.pos_lng != 0)) { 	// 60 minutes old or more gets deleted.
							
							console.log ('User ' + row.user_id + ' has stale position data - deleting marker.');
							
							person_markers[row.user_id].setMap(null);

							delete person_markers[row.user_id];
							delete person_objects[row.user_id];
							
						     drawWhosWho ();

							
						} else if ((row.pos_lat == 0) && (row.pos_lng == 0) && person_markers[row.user_id]) { 	// User has selected to stop tracking.

							// User has deselected tracking - delete marker.
							// TODO: Compare timestamp with my_timestamp - if too old, remove marker.
							// This gives the limitation that the current user has be be tracked in order to 
							// see other users' positions.
								
							console.log ('User ' + row.user_id + ' is no longer tracking - deleting marker.');
							
							person_markers[row.user_id].setMap(null);

							delete person_markers[row.user_id];
							delete person_objects[row.user_id];
							
						     drawWhosWho ();

								
						} else if (pos && // Update the marker if we need to.
									(new_pos.lat && new_pos.lng) &&
									(pos.lat() != new_pos.lat()) &&
									(pos.lng() != new_pos.lng()))  { 
							
							// All good - update the marker.
								
							console.log ('  Updating marker for user ID ' + row.user_id + ' -> ' + new_pos + ', hdg ' + parseInt(row.pos_hdg));
		
							person_markers[row.user_id].setPosition (new_pos);
							
							// Update rotation.
				    		var this_icon = person_markers[row.user_id].getIcon();
				    		 
				    		this_icon.rotation = parseInt(row.pos_hdg);
				    		 
				    		person_markers[row.user_id].setIcon (this_icon);
							
						} // else... not sure!
					}
				}
			});
		}
		

		function show_my_position () {
			
			if (!tracking) {
			
				return;
			}
			
			// If this device supports geolocation, show the current posision.
			if (navigator.geolocation) {
			     navigator.geolocation.getCurrentPosition(function (position) {
			    	 
			    	 //console.dir (position);
			    	 
			    	 my_position = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			    	 my_speed = position.coords.speed + 0;// / 3600 / 1000; // m/s -> km/hr

			    	 my_heading = parseInt(position.coords.heading); // Turn string to number.

			    	 if (!position.coords.heading) {
			    		 my_heading = 0;
			    	 }

			    	 // No marker exists - create a new one.
			    	 if (!me_marker) {
			    		 
			    		 console.log ("Creating new marker for current user's location.");

			    		 me_icon = {
			        		 path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
			        		 fillColor: 'red',
			        		 fillOpacity: 0.8,
			        		 strokeWeight: 1,
			        		 rotation: my_heading,
			        		 scale: 5
			        	 };

				         me_marker = new google.maps.Marker({
				        	 icon: me_icon,
				             position: my_position,
				             animation: google.maps.Animation.DROP,
				             map: map,
				             title: 'You!'
				         });

				         // Pan to where we are now.
				         map.setCenter(my_position);
				         
				         var person = {
						         user_id: 	user_id,
						         colour: 	'red',
						         user_name: 'You',
						         marker: me_marker
					         };
					         
					     person_objects[user_id] = person;

				         
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
					     
					     drawWhosWho ();

			    	 } 
			    	 else { // Otherwise update the position of the existing marker.

			    		 //console.log ("Updating current user's location");
			    		 
			    		 me_marker.setPosition (my_position);
			    		 
			    		 var this_icon = me_marker.getIcon();
			    		 
			    		 this_icon.rotation = my_heading;
			    		 
			    		 me_marker.setIcon (this_icon);
			    		 
			    		 //$("#bimbler-debug-output").html ('Pos: (' + position.coords.latitude.toString().substring (0,10) + ', ' + position.coords.longitude.toString().substring (0,10) + '), hdg: ' + my_heading + ', spd: ' + my_speed);
			    		 $("#bimbler-debug-output").html ('Pos: (' + position.coords.latitude.toFixed(6) + 
			    				 							', ' + position.coords.longitude.toFixed(6) + 
			    				 							'), Hdg: ' + my_heading.toFixed(0) + 
			    				 							', Spd: ' + my_speed.toFixed(1));
			    	 }
			     });
			}
		}
		
		function request(){
			
			// No tracking map (not started, not RSVPd, etc.) - do nothing.
			if (!canvas) {
				return;
			}

			// Prevent parallel Ajax calls.
			if (!run_fetch_ajax) {
				console.log ('Request Ajax already in progress.');
				
				var wait = '<i class="fa fa-signal text-danger"></i>';
				
				$("#bimbler-locator-indicator").html (wait);

				return;
			}
        
			console.log ('Firing Locator Request Ajax');
			
			// TODO: Remove commenting-out.
			//run_fetch_ajax = false;
			
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
		
		window.centreMap = function (m) {
			
	    	locator_map.panTo(m.getPosition());

		}

		window.update = function () {

			//console.log ('Update: tick.');
			
			if (!tracking) {
				
				return;
			}
			
			// No tracking map (not started, not RSVPd, etc.) - do nothing.
			if (!canvas) {
				return;
			}
			
			//var wait = '<i class="fa fa-spinner fa-spin"></i>';
			var wait = '<i class="fa fa-signal text-success"></i>';
			
			var indicator = $("#bimbler-locator-indicator");
			
			// Set the indicator to an animation.
			indicator.html (wait);
			
			// First, update the current position and display the marker. Sets 'my_position'.
			show_my_position ();

			// Prevent parallel Ajax calls.
			if (!run_update_ajax) {
				console.log ('Update Ajax already in progress.');
				
				var wait = '<i class="fa fa-signal text-danger"></i>';
				
				$("#bimbler-locator-indicator").html (wait);

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
				    	 pos_lng: 	my_position.lng(),
				    	 pos_spd: 	my_speed,
				    	 pos_hdg: 	my_heading,
				    	 pos_time:	my_timestamp
				     	}
				    ),
				     success: function(response) {
				    	 if (response.status == 'success') {
				    		 console.log ('Success: ' + response);
				    	 } else {
				    		 console.log ('Success, with errors: ');
				    		 console.dir (response);

				    		var wait = '<i class="fa fa-signal text-danger"></i>';
								
							$("#bimbler-locator-indicator").html (wait);
				    	 }
	 	       			
	 	       			run_update_ajax = true;
				     },
				     error: function(response) {
	  	       			console.log ('Error: ' + response);

	  	       			var wait = '<i class="fa fa-signal text-danger"></i>';
						
						$("#bimbler-locator-indicator").html (wait);
	  	       			
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
			    		 console.log ('Null update success: ' + response);
			    	 } else {
			    		 console.log ('Null update success, with errors: ');
			    		 console.dir (response);
			    		 
			    		var wait = '<i class="fa fa-signal text-danger"></i>';
						
						$("#bimbler-locator-indicator").html (wait);

			    	 }
 	       			
 	       			run_update_ajax = true;
			     },
			     error: function(response) {
  	       			console.log ('Null Update Error: ' + response);

  	       			var wait = '<i class="fa fa-signal text-danger"></i>';
					
					$("#bimbler-locator-indicator").html (wait);
  	       			
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

	$('body').on('click', '.bimbler-whoswho-marker', function () {

		var this_user_id = $(this).attr('data-user-id');

	    centreMap (person_objects[this_user_id].marker);
	    
	});


	
	/*
	 * Handler for 'Track Me' toggle change.
	 */
	$('#bimbler-trackme-toggle').change(function(e) {	
		
		// Update the global.
		tracking = $(this).prop('checked');
		
		// Tracking turned on. 
		if (tracking) {
			// Global updated. This will result in the position being updated
			// on a timer - nothing more to do.

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
			
			// Remove indiators.
			$("#bimbler-locator-indicator").html('');
			$("#bimbler-debug-output").html('');
		}
	})
	
	
});
