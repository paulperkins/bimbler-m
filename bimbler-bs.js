/**
 * Bimbler Mobile
 *
 * @author    Paul Perkins <paul@paulperkins.net>
 * @license   GPL-2.0+
 * @link      http://bimblers.com/plugins
 * @copyright 2015 Paul Perkins
 */

jQuery(document).ready(function ($) {
	
	var event_maps = {};
	var event_markers = {};
	var event_rwgps_maps = {};

	// Stop the slide animation for tabs.
    $("a[data-role=tab]").each(function () {
        var anchor = $(this);
        anchor.bind("click", function () {
            $.mobile.changePage(anchor.attr("href"), {
                transition: "none",
                changeHash: false
            });
            return false;
        });
    });

    // Enable slide animation for page to page navigation.
    $("div[data-role=page]").bind("pagebeforeshow", function (e, data) {
        $.mobile.silentScroll(0);
        $.mobile.changePage.defaults.transition = 'slide';
    });
    
    // Stop fixed toolbars from being toggled.
    /*$(function(){
    	  $('[data-role=header],[data-role=footer]').fixedtoolbar({ tapToggle:false });
    });*/

    function setMap(event_address, map_div, event_id) {
    	var myOptions = {
    	    zoom: 17,
    	    center: event_address,
    	    mapTypeId: google.maps.MapTypeId.ROADMAP
    	};

    	var map = new google.maps.Map(document.getElementById(map_div), myOptions);
      
    	var marker = new google.maps.Marker(
    		{
    			map: map,
    			position: event_address
    		}
    	);
    	
    	// Store the map object in a global associative array to enable re-sizing later.
    	event_maps[event_id] = map;
    	event_markers[event_id] = marker;

    }

    window.renderVenueMap = function (address, map_div, event_id) {

    	var address = address || null;
    	
    	var geocoder= new google.maps.Geocoder();
    	
    	geocoder.geocode( 
    		{ 'address': address }, 
    		function(results, status) {
    			if (status == google.maps.GeocoderStatus.OK) {
    				setMap(results[0].geometry.location, map_div, event_id);
    			}
    		}
    	);
    }

    window.centreVenueMap = function (map, marker) {

    	map.setCenter(marker.getPosition());
    	
    }
    
    window.showVenueMap = function (target) {
    	
		var event_id = target.getAttribute ('data-bimbler-event-id');
		
		var gmap_id = 'tribe-events-gmap-' + event_id;
		
		//var gmap = document.getElementById(gmap_id);
		var gmap = $('#' + gmap_id)[0];
		
		if (gmap) {
		
			var venue_address = decodeURIComponent(gmap.getAttribute('data-venue-address'));
			
			// Create the map if it doesn't already exist.
			if (!event_maps[event_id]) {
				
				renderVenueMap('"' + venue_address + '"', gmap_id, event_id);
				
			}
		}

		// Resize and re-centre the map.
		if (event_maps[event_id]) {

			google.maps.event.trigger(event_maps[event_id], 'resize');
			
			centreVenueMap (event_maps[event_id], event_markers[event_id]);
		}
    }

    /*
     * Handler to slowly scroll to top when tab is clicked.
     */
    $('a[data-toggle="pill"]').on('click', function (e) {
    	$("html, body").animate({ scrollTop: 0 }, "slow");
	});

	
	$('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
//		e.target // newly activated tab
//		e.relatedTarget // previous active tab
		
//		console.log ('Tab shown: ' + e.target.className);
		
		console.log ('bimbler-bs.js: ' + e.target.className.split(" ")[0] + ' clicked.');
		
		if ('bimbler_mobile_summary_tab' == e.target.className.split(" ")[0]) {
			
			showVenueMap (e.target);
			
		} 
	})
	
	/*
	 * Handler for user RSVP clicks.
	 */
	$(".rsvp-button").click(function() {
	    var $btn = $(this);
	    //$btn.button('loading');
	    
	    // Get the data we need.
	    var container = document.getElementById('bimbler-rsvp-control');
	    //console.dir (container);
	    
	    var user_id = container.getAttribute('data-user-id');
	    var event_id = container.getAttribute('data-event-id');
	    var nonce = container.getAttribute('data-nonce'); 

	    var rsvp = $btn[0].getAttribute('data-rsvp');
	    	
	    //console.log ('Calling Ajax...');
	    
        $.post(
        		'/wp-admin/admin-ajax.php',
        		{
        			action: 	'user-rsvpajax-submit',
        			rsvp:   	rsvp,
        			event_id: 	event_id,
        			user_id:	user_id,
        			nonce: 		nonce
        		}
        )
        .done (function (response) {
			
			//$btn.button('reset');
			
			if ('error' == response.status) {
				
				toastrMsg ('error', 'Error', 'Unknown error - could not process RSVP: ' + response.text);
				
			} else if ('invalid_nonce' == response.status) {

				toastrMsg ('error', 'Error', 'Could not process your RSVP. Please re-start the app and try again (invalid token).');
				
			} else {

				//toastrMsg ('success', 'Yes RSVP processed', 'This is just a test - RSVP not updated!');
				//toastrMsg ('success', 'RSVP processed', response.text);
				
				var rsvp_message = document.getElementById('bimbler-your-rsvp');
				
				if (rsvp_message) {
					if ('Y' == rsvp) {
						rsvp_message.innerHTML = '<p>You have RSVPd \'Yes\'.</p>';
					} else {
						rsvp_message.innerHTML = '<p>You have RSVPd \'No\'.</p>';
					}
				}

				if (el = document.getElementById('bimbler-rsvp-yes-count')) {
					el.innerHTML = response.yes_rsvp_count;
				}

				if (el = document.getElementById('bimbler-rsvp-no-count')) {
					el.innerHTML = response.no_rsvp_count;
				}

				// Hide and un-hide the relevant RSVP DIVs.

				// Always hide the user's previous RSVP, if there was one.
				if (el = document.getElementById ('this_user_rsvp')) {
					el.style.display = 'none';
				}
				
				if ('Y' == rsvp) {
					// Un-hide the empty Yes RSVP.
					if (el = document.getElementById ('hidden_yes_rsvp')) {
						el.style.display = 'block';
					}
					
					// Hide an empty No RSVP.
					if (el = document.getElementById ('hidden_no_rsvp')) {
						el.style.display = 'none';
					}
					
					// Disable and enable the buttons.
					if (el = document.getElementById ('bimbler-rsvp-yes')) {
						el.prop = ('disabled', true);
						el.classList.add ('btn-disabled');
						el.setAttribute ('disabled', true);
					}
					
					if (el = document.getElementById ('bimbler-rsvp-no')) {
						el.prop = ('disabled', false);
						el.classList.remove ('btn-disabled');
						el.removeAttribute ('disabled');
					}
					
				} else { 
					// Un-hide the empty No RSVP.
					if (el = document.getElementById ('hidden_no_rsvp')) {
						el.style.display = 'block';
					}
					
					// Hide an empty Yes RSVP.
					if (el = document.getElementById ('hidden_yes_rsvp')) {
						el.style.display = 'none';
					}
					
					// Disable and enable the buttons.
					if (el = document.getElementById ('bimbler-rsvp-yes')) {
						el.prop = ('disabled', false);
						el.classList.remove ('btn-disabled');
						el.removeAttribute ('disabled');
					}
					
					if (el = document.getElementById ('bimbler-rsvp-no')) {
						el.prop = ('disabled', true);
						el.classList.add ('btn-disabled');
						el.setAttribute ('disabled', true);
					}
				}
        	}
        	
        })
        .fail (function (response) {
        	// Log the error message.
			//document.getElementById('bimbler-ajax-error-message').innerHTML = '<pre>' + response.responseText + '</pre>'; 
			//console.log ('AJAX completed with errors.');
			
			toastrMsg ('error', 'System Error', 'Could not process RSVP: ' + response.text);
			
			$btn.button('reset');
        })  
	});
	
	
	
	/*
	 * Handler for 'attended' indicator clicks.
	 */
	$('.rsvp-checkin-container').click (function () {

		var rsvp_id = $(this).attr('id');
		var event_id = $(this).attr('data-event-id');

		var wait = '<div class="rsvp-checkin-indicator-wait"><i class="fa fa-spinner fa-spin"></i></div>';
		
		var indicator = $("#rsvp-checkin-indicator-" + rsvp_id);
		
		// Set the indicator to an animation.
		indicator.html (wait);

        $.post(
        		'/wp-admin/admin-ajax.php',
        		{
        			action: 	'checkinajax-submit', 
        			container: 	rsvp_id,
        			event_id:	event_id
        		},
        		function (response) {
        			//console.log (response);
        			
        			if ('success' == response.status) {
        				indicator.html(response.indicator);
        				
        				if (response.attendee_count) {

        					if (yes = $("#bimbler-rsvp-yes-count")) {

        						yes.html(response.attendee_count + ' / ' + response.rsvp_count); 
        					}
        				} 
        			}
        		}
        );
	});

	/*
	 * Handler for event comments being posted.
	 */
	$(".comment-post-button").click(function() {
	    var $btn = $(this);
	    $btn.button('loading');
	    
	    // Get the data we need.
	    var event_id = $btn[0].getAttribute('data-event-id');
	    //var comment = $btn[0].getAttribute('data-nonce'); 
	    var nonce = $btn[0].getAttribute('data-nonce');
	    var comment = document.getElementById('bimbler-comment').value;
	    
	    //console.log ('Event: ' + event_id);
	    //console.log ('Nonce: ' + nonce);
	    //console.log ('Comment: "' + comment + '"');
	    	
	    console.log ('Calling Ajax...');
	    
        $.post(
        		'/wp-admin/admin-ajax.php',
        		{
        			action: 	'commentajax-submit',
        			event_id: 	event_id,
        			comment:	comment,
        			nonce: 		nonce
        			//input:  form_contents 
        		}
        )
        .done (function (response) {
			console.log ('AJAX completed.');
			
			console.dir (response);
			
			if ('error' == response.status) {
				
				toastrMsg ('error', 'Error', 'Unknown error - could not process comment: ' + response.text);
				
			} else if ('invalid_nonce' == response.status) {

				toastrMsg ('error', 'Error', 'Could not process your comment. Please re-start the app and try again (invalid token).');
				
			} else if ('no_comment' == response.status) {

				toastrMsg ('error', 'Error', 'Please enter a comment!');
				
			} else {

				//toastrMsg ('success', 'Comment created', 'Thanks for your comment!');
				
				
				document.getElementById('bimbler-comment').value = '';
				
				// Un-hide the empty comment.
				if (el = document.getElementById ('bimbler-empty-comment')) {
					el.style.display = 'block';
				}
				
				// Set the comment's text to that of the new comment.
				if (el = document.getElementById ('bimbler-new-comment')) {
					el.innerHTML = comment;
				}

        	}
        	
			$btn.button('reset');
        })
        .fail (function (response) {
        	// Log the error message.
			//document.getElementById('bimbler-ajax-error-message').innerHTML = '<pre>' + response.responseText + '</pre>'; 
			console.log ('AJAX completed with errors.');
			
			toastrMsg ('error', 'System Error', 'Could not process comment: ' + response.text);
			
			$btn.button('reset');
        })  
	
	    
/*	    setTimeout(function () {
	    	$btn.button('reset');
	    }, 1000); */
	    
	});	

	

	/* Fake a click on the summary page in order to re-size the map properly. */
	$('a[data-toggle="pill"]:first').trigger("shown.bs.tab");	

	
	/* Stop links from opening a new window. */
/*	$("a").click(function (e) {
		e.preventDefault();
		window.location = $(this).attr("href");
	}); */

	
	$(".bimbler-spinner-source").click(function (e) {
	
		console.log ('Clicked link');
		
		var target = $(this).find (".bimbler-spinner-target");
		//var target = e.find (".bimbler-spinner-target");
		
		console.dir (target[0]);
		
		var spinner = new Spinner().spin(target[0]);
		
	}); 

	
	window.toastrMsg = function (type, title, msg) {
		
		var opts = {
				"closeButton": true,
				"debug": false,
//				"positionClass": "toast-top-right",
				"positionClass": "toast-top-full-width",
				"toastClass": "black",
				"onclick": null,
				"showDuration": "300",
				"hideDuration": "1000",
				"timeOut": "10000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut",
				"closeHtml": "<button><i class=\"fa fa-times fa-2x\"></i></button>"
			};
		
		if ('success' == type) {
			toastr.success (msg, title, opts);
		}
		if ('warning' == type) {
			toastr.warning (msg, title, opts);
		}
		if ('error' == type) {
			toastr.error (msg, title, opts);
		}
	}
	
	if (window.spinner) {
	    window.spinner.stop;
	}
	
	// Hide the empty comment.
	if (el = document.getElementById ('bimbler-empty-comment')) {
		el.style.display = 'none';
	}
	
});

/*jQuery(document).on('click', 'a', function (e) {
	e.preventDefault();
	window.location = $(this).attr("href");

}); */

// Display the loading widget when creating a page.
jQuery(document).on('pagebeforecreate', '[data-role="page"]', function(){     
/*    var interval = setInterval(function(){
        $.mobile.loading('show');
        clearInterval(interval);
    },1); */
    
    //window.spinner = new Spinner().spin();
    
	var opts = {
//			  lines: 17, // The number of lines to draw
			  length: 26, // The length of each line
//			  width: 5, // The line thickness
			  radius: 18, // The radius of the inner circle
//			  corners: 1, // Corner roundness (0..1)
//			  rotate: 0, // The rotation offset
//			  direction: 1, // 1: clockwise, -1: counterclockwise
//			  color: '#000', // #rgb or #rrggbb or array of colors
//			  speed: 1, // Rounds per second
//			  trail: 54, // Afterglow percentage
//			  shadow: false, // Whether to render a shadow
//			  hwaccel: false, // Whether to use hardware acceleration
//			  className: 'spinner', // The CSS class to assign to the spinner
//			  zIndex: 2e9, // The z-index (defaults to 2000000000) 
			  top: '20%', // Top position relative to parent
			  left: '50%' // Left position relative to parent
			};
	
    var target = document.getElementById('spinner-target');
    window.spinner = new Spinner(opts).spin(target);
});

// Close the loading widget after 1,000ms after the page has shown (give it time to fully render).
jQuery(document).on('pageshow', '[data-role="page"]', function(){  
/*    var interval = setInterval(function(){
        $.mobile.loading('hide');
        clearInterval(interval);
    },1000); */
	
	var interval = setInterval(function(){
		window.spinner.stop();
		clearInterval(interval);
	},2000);
    
    //window.spinner.stop();
});

