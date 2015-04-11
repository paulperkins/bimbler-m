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

    
    // Modify RSVP popup.
    $( ".bimblers_my_rsvp" ).on( "click", function() {
    	console.log ('bimblers_my_rsvp: clicked.');
    	
    	var rsvp_id = $(this).attr("id");
    	
    	console.log ('RSVP ID: ' + rsvp_id);
    	
    	//runtimePopup ('Hello.');
    	
/*    	$.dynamic_popup({
    		'data-rel': 'none',
    		content:	'This is a basic help message. Did you get it?'
//    		content: '<div data-role="popup" id="myPopupDialog"> ' +
//'  <div data-role="header"><h1>Header Text..</h1></div>' +
//'  <div data-role="main" class="ui-content"><p>Some text..</p><a href="#">some links..</a>' +
//'  <div data-role="footer"><h1>Footer Text..</h1></div>' +
//'</div>' 
    	}); */ 
    	
/*		var target = $( this ),
			brand = target.find( "h2" ).html(),
			model = target.find( "p" ).html(),
			short = target.attr( "id" ),
			closebtn = '<a href="#" xdata-rel="back" class="ui-btn ui-corner-all ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>',
			header = '<div data-role="header"><h2>Change your RSVP</h2></div>',
			//img = '<img src="../_assets/img/' + short + '.jpg" alt="' + brand + '" class="photo">',
			popup = '<div data-role="popup" id="popup-' + short + '" data-short="' + short +'" data-theme="none" data-overlay-theme="a" data-corners="false" data-tolerance="15"></div>'
			;

		
		// Create the popup.
		$( header )
			.appendTo( $( popup )
						.appendTo( $.mobile.activePage )
						.popup() )
			.toolbar()
			.before( closebtn )
			//.after( img )
			;

		// Wait with opening the popup until the popup image has been loaded in the DOM.
		// This ensures the popup gets the correct size and position
		$( ".photo", "#popup-" + short ).load(function() {
			// Open the popup
			$( "#popup-" + short ).popup( "open" );

			// Clear the fallback
			clearTimeout( fallback );
		});

		// Fallback in case the browser doesn't fire a load event
		var fallback = setTimeout(function() {
			$( "#popup-" + short ).popup( "open" );
		}, 1000); */
	});

	// Remove the popup after it has been closed to manage DOM size
	$( document ).on( "popupafterclose", ".ui-popup", function() {
		$( this ).remove();
	}); 
	

	// Handler for tab clicks.
	//
	// Call the 'resize' event on Google map when a tab is shown to ensure that 
	// it renders properly. Because we're creating all pages ahead of time the map
	// won't know its bounds at initialisation time.
	$( ":mobile-pagecontainer" ).on( "pagecontainerchange", function( event, ui ) {
		
		console.log ('bimbler.js: ' + e.target.className.split(" ")[0] + ' clicked.');
		
		if ('bimbler_mobile_summary_tab' == ui.toPage[0].className.split(" ")[0]) {
			//console.log ('Selected summary page - resizing Google map.');
			
			var event_id = ui.toPage[0].getAttribute ('data-bimbler-event-id');
			
			var gmap_id = 'tribe-events-gmap-' + event_id;
			
			var gmap = document.getElementById(gmap_id);
			
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

		// Fill in the RideWithGPS map if we've changed to a map tab.
		if ('bimbler_mobile_map_tab' == ui.toPage[0].className.split(" ")[0]) {
			
			var event_id = ui.toPage[0].getAttribute ('data-bimbler-event-id');
			
			//console.log ('Event ID ' + event_id);
			
			var container = document.getElementById('rwgps-map-container-' + event_id);
			
			// This page may not have a map.
			if (container) {
			
				//console.dir (container);
				
				var rwgps_id = container.getAttribute('data-rwgps-id');
				
				// Create the map if it doesn't already exist.
				if (!event_rwgps_maps[event_id]) {
					
					var new_dom = '<iframe id="rwgps-map-' + event_id + '" src="//ridewithgps.com/routes/' + rwgps_id + '/embed" height="800px" width="100%" frameborder="0" scrolling="no" class="iframe-class"></iframe>';
					
					container.innerHTML = new_dom;
					
					// Store something as the key's value.
					event_rwgps_maps[event_id] = rwgps_id;
				}
			}
		}
		
	});
	

	
	window.runtimePopup = function(message, popupafterclose) {
		  var template = "<div data-role='popup' class='ui-content messagePopup' style='max-width:280px'>" 
		      + "<a href='#' data-role='button' data-theme='g' data-icon='delete' data-iconpos='notext' " 
		      + " class='ui-btn-right closePopup'>Close</a> <span> " 
		      + message + " </span> </div>";
		  
		  popupafterclose = popupafterclose ? popupafterclose : function () {};
		 
		  $.mobile.activePage.append(template).trigger("create");
		 
		  $.mobile.activePage.find(".closePopup").bind("tap", function (e) {
		    $.mobile.activePage.find(".messagePopup").popup("close");
		  });
		 
		  $.mobile.activePage.find(".messagePopup").popup().popup("open").bind({
		    popupafterclose: function () {
		      $(this).unbind("popupafterclose").remove();
		      popupafterclose();
		    }
		  });
		}

	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
//		e.target // newly activated tab
//		e.relatedTarget // previous active tab
		
		console.log ('Tab shown.');
	})
	

	
});

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

