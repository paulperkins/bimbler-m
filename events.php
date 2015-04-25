<?php

	$bimbler_mobile_events_per_page = 15;

	include_once ('single_event.php');
	
	$bimbler_mobile_tab_summary = 1;
	$bimbler_mobile_tab_details = 2;
	$bimbler_mobile_tab_map = 3;
	$bimbler_mobile_tab_rsvps = 4;
	$bimbler_mobile_tab_photos = 5;
	$bimbler_mobile_tab_comments = 6;
	$bimbler_mobile_tab_locator = 7;
	
	$bimbler_mobile_tabs = array (1, 2, 3, 4, 5, 6, 7);
	
	$bimbler_mobile_tab_icons = array (	1 => 'fa fa-list-ul', 
										2 => 'fa fa-list-alt', 
										3 => 'fa fa-map-marker', 
										4 => 'fa fa-users', 
										5 => 'fa fa-camera',
										6 => 'fa fa-comments-o',
										7 => 'fa fa-compass');
	$bimbler_mobile_tab_text = array (	1 => 'Summary',
										2 => 'Details',
										3 => 'Map',
										4 => 'RSVPs',
										5 => 'Photos',
										6 => 'Comments',
										7 => 'Locator');
	$bimbler_mobile_tab_class = array (	1 => 'bimbler_mobile_summary_tab',
										2 => 'bimbler_mobile_details_tab',
										3 => 'bimbler_mobile_map_tab',
										4 => 'bimbler_mobile_rsvps_tab',
										5 => 'bimbler_mobile_photos_tab',
										6 => 'bimbler_mobile_comments_tab',
										7 => 'bimbler_mobile_locator_tab');
								
	
	$bimbler_mobile_time_str = 'j M g:ia';
	$bimbler_mobile_day_time_str = 'D j M g:ia';
	$bimbler_mobile_date_str = 'D j M';

	
	function bimbler_mobile_get_event_pics ($event_id) {
		global $wpdb;
	
		$meta_gallery_id = get_post_meta ($event_id, 'bimbler_gallery_id', true);
	
		if (!isset($meta_gallery_id) || (0 == $meta_gallery_id)) {
			//error_log ('No gallery set for event '. $post_id);
	
			return 0;
		}
		
		global $nggdb;
		
		$pics = $nggdb->get_gallery($meta_gallery_id, 'sortorder', 'ASC', true, 0, 0);
		
		//error_log ('Gallery ' . $meta_gallery_id . ' has ' . $pics->num_pics . ' pics.');
			
		return $pics;
	}
	
	function bimbler_mobile_render_photos ($event_id) {
		
		$content = '';
		
		$content .= '	<!-- Gallery Page. -->' . PHP_EOL;
		
		$pics = bimbler_mobile_get_event_pics ($event_id);
		
		if (!empty($pics)) {
			
			$content .= '	<div class="panel panel-default">' . PHP_EOL;
			$content .= '		<div class="panel-heading">' . PHP_EOL;
			$content .= '			<h4 class="panel-title">Photos</h4>' . PHP_EOL;
			$content .= '		</div>' . PHP_EOL;
			
			$content .= '		<div class="panel-body">' . PHP_EOL;

			$content .= '			<div class="bimbler-gallery" style="width: 100%; margin-left: auto; margin-right: auto;">' . PHP_EOL;
			
			foreach ($pics as $pic) {
				
				$img_dir = dirname(parse_url(print_r($pic->imageURL, true),PHP_URL_PATH));
				//$img_dir = $pic->path;
				$img_url = $pic->imageURL;
				$img_filename = $pic->filename;
				$img_width = $pic->meta_data['width'];
				$img_height = $pic->meta_data['height'];
				$img_thm_width = $pic->meta_data['thumbnail']['width'];
				$img_thm_height = $pic->meta_data['thumbnail']['height'];
				$img_thm_folder = $pic->thumbFolder;
				$img_thm_prefix = $pic->thumbPrefix;
				
				$img_thm_url = $img_dir . $img_thm_folder . $img_thm_prefix . $img_filename;
				
/*				$content .= '<pre>' . $img_width . ' x ' . $img_height . '</pre>' . PHP_EOL;
				$content .= '<pre>Thumb:' . $img_thm_folder . ' : ' . $img_thm_width . ' x ' . $img_thm_height . '</pre>' . PHP_EOL;
				//$content .= '<pre>' . dirname(parse_url(print_r($pic->imageURL, true),PHP_URL_PATH)) . '</pre>' . PHP_EOL;
				$content .= '<pre>' . $img_dir . '</pre>' . PHP_EOL;
				$content .= '<pre>' . print_r($pic->imageURL, true) . '</pre>' . PHP_EOL;
				$content .= '<pre>' . print_r($pic->meta_data, true) . '</pre>' . PHP_EOL;
				$content .= '<pre>' . print_r($pic->imageURL, true) . '</pre>' . PHP_EOL; */
				//$content .= '<pre>' . print_r($pic, true) . '</pre>'. PHP_EOL;

				//$content .= '	<a href="' . $pic->imageURL . '" data-size="' . $img_width . 'x' . $img_height . '" data-med="' . $img_thm_url . '" data-med-size="' . $img_thm_width . 'x' . $img_thm_height . '">' . PHP_EOL;
				$content .= '				<a href="' . $pic->imageURL . '" ' . PHP_EOL;
				$content .= '						data-size="' . $img_width . 'x' . $img_height . '" ' . PHP_EOL;
				$content .= '						data-med="' . $pic->imageURL . '" ' . PHP_EOL;
				$content .= '						data-med-size="' . $img_width . 'x' . $img_height . '">' . PHP_EOL;
				$content .= '					<img src="' . $img_thm_url . '" width="' . $img_thm_width . '" height="' . $img_thm_height . '">' . PHP_EOL;
				$content .= '				</a>' . PHP_EOL;
			}

			$content .= '			</div>' . PHP_EOL;

			// New stuff
			$content .= '		</div>' . PHP_EOL;
			$content .= '	</div>' . PHP_EOL;
			
		}

		$meta = get_post_meta ($event_id, 'bimbler_gallery_id');
		
		if (isset ($meta[0])) {
			
			$content .= '	<div class="panel panel-default">' . PHP_EOL;
			$content .= '		<div class="panel-heading">' . PHP_EOL;
			$content .= '			<h4 class="panel-title">Upload</h4>' . PHP_EOL;
			$content .= '		</div>' . PHP_EOL;
				
			$content .= '		<div class="panel-body">' . PHP_EOL;
		
			$content .= do_shortcode ('[ngg_uploader id='. $meta[0] .']');
			
			$content .= '		</div>' . PHP_EOL;
			$content .= '	</div>' . PHP_EOL;
		}
		
		$content .= '
			<div id="gallery" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="pswp__bg"></div>
				<div class="pswp__scroll-wrap">
					<div class="pswp__container">
						<div class="pswp__item"></div>
						<div class="pswp__item"></div>
						<div class="pswp__item"></div>
					</div>
			
					<div class="pswp__ui pswp__ui--hidden">
						<div class="pswp__top-bar">
							<div class="pswp__counter"></div>
							<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
							<button class="pswp__button pswp__button--share" title="Share"></button>
							<button class="pswp__button pswp__button--fs"
								title="Toggle fullscreen"></button>
							<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
							<div class="pswp__preloader">
								<div class="pswp__preloader__icn">
									<div class="pswp__preloader__cut">
										<div class="pswp__preloader__donut"></div>
									</div>
								</div>
							</div>
						</div>
			
						<!-- <div class="pswp__loading-indicator"><div class="pswp__loading-indicator__line"></div></div> -->
			
						<div
							class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
							<div class="pswp__share-tooltip">
								<!-- <a href="#" class="pswp__share--facebook"></a>
												<a href="#" class="pswp__share--twitter"></a>
												<a href="#" class="pswp__share--pinterest"></a>
												<a href="#" download class="pswp__share--download"></a> -->
							</div>
						</div>
			
						<button class="pswp__button pswp__button--arrow--left"
							title="Previous (arrow left)"></button>
						<button class="pswp__button pswp__button--arrow--right"
							title="Next (arrow right)"></button>
						<div class="pswp__caption">
							<div class="pswp__caption__center"></div>
						</div>
					</div>
				</div>
			</div>
				' . PHP_EOL; 
		
		$content .= '	<!-- /Gallery Page. -->' . PHP_EOL;
		
		return $content;
	}
	
	function bimbler_get_avatar_img ($avatar) {
		
		preg_match( '#src=["|\'](.+)["|\']#Uuis', $avatar, $matches );
	
		return ( isset( $matches[1] ) && ! empty( $matches[1]) ) ?
		(string) $matches[1] : '';
	}
	
	/*
	 * Determines if the user can execute Ajax, and checks if the Ajax Bimbler plugin is loaded.
	*/
	function bimbler_mobile_can_modify_attendance () {
	
/*		if (!class_exists (BIMBLER_AJAX_CLASS)) {
			//error_log ('User can\'t run Ajax - BIMBLER_AJAX_CLASS not loaded.');
			return false;
		} */
	
		if (!current_user_can ('manage_options')) {
			//error_log ('User can\'t run Ajax - not an admin.');
			return false;
		}
	
		return true;
	}
	
	
	
	function bimbler_mobile_render_rsvps ($event_id) {

		global $current_user;
		global $bimbler_mobile_time_str;
		
		
		$content = '';

		// Only show content to logged-in users, and only if we're on an event page.
		if (is_user_logged_in()) {
			
			$nonce = wp_create_nonce('bimbler_rsvp');
			
			$rsvps_y = Bimbler_RSVP::get_instance()->get_event_rsvp_object ($event_id, 'Y');
			$rsvps_n = Bimbler_RSVP::get_instance()->get_event_rsvp_object ($event_id, 'N');
			$count_rsvps_y = Bimbler_RSVP::get_instance()->count_rsvps ($event_id);
			$count_rsvps_n = Bimbler_RSVP::get_instance()->count_no_rsvps ($event_id);
			
			$host_users = Bimbler_RSVP::get_instance()->get_event_host_users ($event_id);
			
			if (!isset ($count_rsvps_y)) {
				$count_rsvps_y = 0;
			}
				
			if (!isset ($count_rsvps_n)) {
				$count_rsvps_n = 0;
			}
				
			get_currentuserinfo();
				
			$user_id = $current_user->ID;
			
			$has_event_passed = Bimbler_RSVP::get_instance()->has_event_passed ($event_id);
				
			$current_rsvp = Bimbler_RSVP::get_instance()->get_current_rsvp_object ($event_id, $user_id);

			if (null == $current_rsvp) {
				$this_rsvp .= 'You have not RSVPd.';
				$no_btn_state = '  ';
				$yes_btn_state = ' ';
			}
			else {
				if ('Y' == $current_rsvp->rsvp) {
					$this_rsvp .= 'You have RSVPd \'Yes\'.';
					$yes_btn_state = ' disabled="disabled" ';
					$no_btn_state = ' ';
				} else {
					$this_rsvp .= 'You have RSVPd \'No\'.';
					$no_btn_state = ' disabled="disabled" ';
					$yes_btn_state = ' ';
				}
			}

			// This user's RSVP.
			$content .= '	<div class="panel panel-default">' . PHP_EOL;
			$content .= '		<div class="panel-heading">' . PHP_EOL;
			$content .= '			<h4 class="panel-title">Your RSVP</h4>' . PHP_EOL;
			$content .= '		</div>' . PHP_EOL;
			$content .= '		<div class="panel-body">' . PHP_EOL;
			$content .= '			<div class="row">' . PHP_EOL;
			$content .= '					<div class="col-xs5 pull-left" style="padding-left:15px;" id="bimbler-your-rsvp">' . PHP_EOL;
			$content .= '						<p>' . $this_rsvp . '</p>' . PHP_EOL;
			$content .= '					</div>' . PHP_EOL;
			$content .= '					<div class="col-xs-7 pull-right" style="padding-left:15px; text-align: right; xheight:100px;">' . PHP_EOL;
			$content .= '						<div id="bimbler-rsvp-control" class="btn-group" data-event-id="' . $event_id . '" data-user-id="' . $user_id . '" data-nonce="' . $nonce . '">' . PHP_EOL;
			$content .= '							<button type="button" class="btn btn-success rsvp-button" ' . $yes_btn_state . ' data-rsvp="Y" id="bimbler-rsvp-yes" data-loading-text="<i class=\'fa fa-spinner fa-spin\'></i> RSVP Yes">' . PHP_EOL;
			$content .= '								RSVP Yes' . PHP_EOL;
			$content .= '							</button>' . PHP_EOL;
			$content .= '							<button type="button" class="btn btn-danger rsvp-button" ' . $no_btn_state . ' data-rsvp="N" id="bimbler-rsvp-no" data-loading-text="<i class=\'fa fa-spinner fa-spin\'></i> RSVP No">' . PHP_EOL;
			$content .= '								RSVP No' . PHP_EOL;
			$content .= '							</button>' . PHP_EOL;
			$content .= '						</div>' . PHP_EOL;
			$content .= '					</div>' . PHP_EOL;
			$content .= '			</div>' . PHP_EOL;
			$content .= '		</div>' . PHP_EOL;
			$content .= '	</div>' . PHP_EOL;

			foreach (array('Y', 'N') as $this_rsvp) {
				
				$content .= '	<div class="panel panel-default">' . PHP_EOL;
				$content .= '		<div class="panel-heading">' . PHP_EOL;
				
				if ('Y' == $this_rsvp) {
					$rsvps = $rsvps_y;
					$count = $count_rsvps_y;
					
					$content .= '			<h4 class="panel-title">Coming<span class="badge pull-right" id="bimbler-rsvp-yes-count">' . $count . '</span></h4>' . PHP_EOL;
				} else {
					$rsvps = $rsvps_n;
					$count = $count_rsvps_n;
						
					$content .= '			<h4 class="panel-title">Not coming<span class="badge pull-right" id="bimbler-rsvp-no-count">' . $count . '</span></h4>' . PHP_EOL;
				}

				$content .= '		</div>' . PHP_EOL;

				$panel_style = 'border-bottom-color: rgb(221, 221, 221); border-bottom-style: solid; border-bottom-width: 1px; padding-top:0px; padding-bottom:0px; ';

				// Always render a hidden yes and no RSVP for this user. This can then be un-hidden if they change their RSVP.
					
				$avatar = get_avatar ($current_user->ID, null, null, $current_user->user_login);
				$avatar_img = bimbler_get_avatar_img($avatar);
				
				if (('Y' == $this_rsvp)){
					$content .= '		<div class="panel-body" style="' . $panel_style . ' display: none;" id="hidden_yes_rsvp">' . PHP_EOL;
				} else {
					$content .= '		<div class="panel-body" style="' . $panel_style . ' display: none;" id="hidden_no_rsvp">' . PHP_EOL;
				}
				$content .= '				<div class="row">' . PHP_EOL;
				$content .= '					<div class="col-xs2 pull-left" style="height: 80px;">' . PHP_EOL;
				$content .= '						<div class="avatar-clipped" style="background-image: url(\'' . $avatar_img . '\');"></div>' . PHP_EOL;
				$content .= '					</div>' . PHP_EOL;
				$content .= '					<div class="col-xs-8" style="padding-left:10px;">' . PHP_EOL;
				$content .= '						<p><strong>' . $current_user->nickname;
				$content .= '</strong></p>' . PHP_EOL;
				$content .= '					</div>' . PHP_EOL;
				$content .= '				</div> <!-- /row -->' . PHP_EOL;
				$content .= '			</div>' . PHP_EOL;
				
				if (isset ($rsvps)) {
					foreach ( $rsvps as $rsvp) {
					
						$user_info   = get_userdata ($rsvp->user_id);
					
						$avatar = '';
							
						if (isset ($user_info->user_login)) {
							$avatar .= get_avatar ($rsvp->user_id, null, null, $user_info->user_login);
							$avatar_img = bimbler_get_avatar_img($avatar);
						}

						if ($rsvp->user_id == $current_user->ID) {
							$content .= '		<div class="panel-body" style="' . $panel_style . '" id="this_user_rsvp">' . PHP_EOL;
						} else {
							$content .= '		<div class="panel-body" style="' . $panel_style . '">' . PHP_EOL;
						}
						
						$content .= '				<div class="row">' . PHP_EOL;
						$content .= '					<div class="col-xs2 pull-left" style="height: 80px;">' . PHP_EOL;

						// Output an innocuous DIV if the user cannot amend attendance, or if the Ajax module is not loaded.
						if (!bimbler_mobile_can_modify_attendance ()) {
							$content .= '<div class="rsvp-checkin-container-noajax">';
						}
						else {
							// Store the RSVP ID.
							$content .= '<div class="rsvp-checkin-container" id="'. $rsvp->id .'" data-event-id="' . $event_id . '">';
						}
						
						$content .= '						<div class="avatar-clipped" style="background-image: url(\'' . $avatar_img . '\');"></div>' . PHP_EOL;

						// Only show indicators if the event has ended or we're admin, and if we're showing the 'Yes' RSVPs.
						if (('Y' == $this_rsvp) && (current_user_can( 'manage_options') || $has_event_passed))
						{
							$content .= '<div class="rsvp-checkin-indicator" id="rsvp-checkin-indicator-'. $rsvp->id .'">'; // Content will be replaced by Ajax.
							
							if (!isset ($rsvp->attended)) {
								$content .= '<div class="rsvp-checkin-indicator-none"><i class="fa-question-circle"></i></div>';
							} else if ('Y' == $rsvp->attended) {
								$content .= '<div class="rsvp-checkin-indicator-yes"><i class="fa-check-circle"></i></div>';
							}
							else {
								$content .= '<div class="rsvp-checkin-indicator-no"><i class="fa-times-circle"></i></div>';
							}
						
							$content .= '</div>';
						}
						
						$content .= '</div> <!-- rsvp-checkin-container -->';
						
						$content .= '					</div>' . PHP_EOL;
						
						$content .= '					<div class="col-xs-8" style="padding-left:10px;">' . PHP_EOL;
						$content .= '						<p><strong>' . $user_info->nickname;
						
						if ($rsvp->guests > 0) {
							$content .= ' + ' . $rsvp->guests;
						}
						$content .= '</strong>' . PHP_EOL;

						if (isset ($host_users) && in_array ($user_info->ID,$host_users)) {
						
							$content .= ' (Host)' . PHP_EOL;
								
						}
						
						$content .= '</p>' . PHP_EOL;
						
						if (current_user_can( 'manage_options')) {
							$content .= ' 						<p>RSVPd on ' . date ($bimbler_mobile_time_str, strtotime($rsvp->time)) . '</p>' . PHP_EOL;
						}
							
						//$content .= '						<p xclass="pull-right xui-li-aside" xstyle="text-align: right"><strong>' . date ($time_str, strtotime($event_date)) . '</strong></p>' . PHP_EOL;
						$content .= '					</div>' . PHP_EOL;
						
						$content .= '				</div> <!-- /row -->' . PHP_EOL;
						$content .= '			</div>' . PHP_EOL;				
					}
				}
									
				$content .= '		</div>' . PHP_EOL;
			}
				
		}
				
		//$content .= bimbler_mobile_render_rsvp_dialog ($event_id);
		
		return $content;
	}

	function bimbler_mobile_render_map ($event_id, $rwgps_id) {
		$content = '';
		
		if (0 == $rwgps_id) {
		
			$content .= '<span>This event does not yet have a map.</span>';
		
		} else {
		
			$content .= '				<p><div style="padding-left: 5px; padding-right: 5px;" id="rwgps-map-container-' . $event_id . '" data-rwgps-id="' . $rwgps_id . '">';
			$content .= '<iframe id="rwgps-map-' . $event_id . '" src="//ridewithgps.com/routes/' . $rwgps_id . '/embed" height="800px" width="100%" frameborder="0" scrolling="no" class="iframe-class"></iframe>';
			$content .= '</div></p>' . PHP_EOL;
		}
		
		return $content;
	}

	/*
	 *
	*/
	function get_venue_address ($event_id) {
			
		$locationMetaSuffixes = array( 'address', 'city', 'region', 'zip', 'country' );
		$address = "";
			
		$address .= tribe_get_address ($event_id);
		$address .= ' ' . tribe_get_city ($event_id);
		$address .= ' ' . tribe_get_region ($event_id);
		$address .= ' ' . tribe_get_zip ($event_id);
		$address .= ' ' . tribe_get_country ($event_id);
	
		return trim($address);
	}
	
	
	/**
	 * Adds the ride page to the event.
	 *
	 * @param
	 */
	function bimbler_mobile_render_summary_page ($event_id) {
		global $bimbler_mobile_time_str;
		global $bimbler_mobile_day_time_str;
		global $bimbler_mobile_date_str;
		
		
		$post_id = $event_id;
	
		$content = '';
	
		// Only show content to logged-in users, and only if we're on an event page.
		if (is_user_logged_in()) {
	
			// Get the summary text from the event.
			$post_object = get_post ($post_id);
	
			if (!isset($post_object)) {
				error_log ('Cannot get post object for event ID '. $meta_ride_page);
				return null;
			}
	
			$content .= '	<div class="panel panel-default">' . PHP_EOL;
			$content .= '		<div class="panel-heading">' . PHP_EOL;
			$content .= '			<h4 class="panel-title">' . $post_object->post_title . '</h4>' . PHP_EOL;
			$content .= '		</div>' . PHP_EOL;
			
			$content .= '		<div class="panel-body">' . PHP_EOL;
			$content .= apply_filters( 'the_content', $post_object->post_content);
			$content .= '		</div>' . PHP_EOL;
	
			$content .= '	</div>' . PHP_EOL;
			
			// Get the date and time details.
			$start_date = tribe_get_start_date($event_id, false, $bimbler_mobile_day_time_str);
			$end_date = tribe_get_end_date($event_id, false, $bimbler_mobile_day_time_str);

			$content .= '	<div class="panel panel-default">' . PHP_EOL;
			$content .= '		<div class="panel-heading">' . PHP_EOL;
			$content .= '			<h4 class="panel-title">When</h4>' . PHP_EOL;
			$content .= '		</div>' . PHP_EOL;

			$content .= '		<div class="panel-body">' . PHP_EOL;
			$content .= '			<p><strong>Start:</strong> ' . $start_date . '</p>' . PHP_EOL;
			$content .= '			<p><strong>End:</strong> ' . $end_date . '</p>' . PHP_EOL;
			$content .= '		</div>' . PHP_EOL;
				
			$content .= '	</div>' . PHP_EOL;

			// Get the venue details.
			$venue = tribe_get_venue($event_id);
			$venue_address = get_venue_address($event_id);
			
			$content .= '	<div class="panel panel-default">' . PHP_EOL;
			$content .= '		<div class="panel-heading">' . PHP_EOL;
			$content .= '			<h4 class="panel-title">Where</h4>' . PHP_EOL;
			$content .= '		</div>' . PHP_EOL;
			
			$content .= '		<div class="panel-body">' . PHP_EOL;
			$content .= '			<p><strong>Venue:</strong> ' . $venue . '</p>' . PHP_EOL;
			$content .= '			<p><strong>Address:</strong> ' . $venue_address . '</p>' . PHP_EOL;
				
			// Add the map if an address is present. Note that we only render the DIV now, as JS will fill-in the map 
			// itself on the PageChange event.
			if (!empty ($venue_address)) {

				$map_style = 'height: 250px; width: 100%; margin-bottom: 15px;';
				
				$content .= '			<p><strong>Map:</strong></p>' . PHP_EOL;
				
				$map_div = 'tribe-events-gmap-' . $event_id;
				
				$content .= '			<div id="' . $map_div . '" style="'. $map_style . '" class="bimbler_event_map" data-venue-address="' . urlencode($venue_address) . '"></div>' . PHP_EOL;
				
				// Fake clicking the first tab.
				$content .= '<script type="text/javascript">$(\'a[data-toggle="pill"]:first\').trigger("shown.bs.tab");</script>' . PHP_EOL;
			} 
				
			$content .= '		</div>' . PHP_EOL;
			$content .= '	</div>' . PHP_EOL;
				
		} else {
			$content .= '<h2>Please log in.</h2>' . PHP_EOL;
		}
	
		return $content;
	}
	
	/*
	 *
	*/
	function bimbler_mobile_get_comment_count ($event_id) {
	
		$args = array (
				'post_id' 	=> $event_id,
				'status'	=> 'approve',
				'count'		=> true
		);
	
		return get_comments ($args);
	}
	
	
	/*
	 * 
	 */
	function bimbler_mobile_get_comment_children ($parent_id, $event_id = null) {
		
		if (isset ($event_id)) {
			
			$args = array (
					'post_id' 	=> $event_id,
					'status'	=> 'approve',
					'order'		=> 'DESC'
					//'orderby'	=> ''
					);
			
		} else {
				
			$args = array (
					'comment_parent' => $parent_id,
					'status'	=> 'approve',
					'order'		=> 'DESC'
					//'orderby'	=> ''
					);
				
		}
		
		return get_comments ($args);
	}

	function bimbler_mobile_show_hidden_comment ($comment) {
		global $current_user;

		get_currentuserinfo();
		
		$content = '';
		
		$content .= '		<div class="bimbler-empty-comment" style="visibility: none;" id="bimbler-empty-comment">'. PHP_EOL;
		$content .= '		<li class="dd-item">'. PHP_EOL;
		$content .= '			<div class="dd-handle">'. PHP_EOL;

		$avatar = get_avatar ($current_user->ID, null, null);
		$avatar_img = bimbler_get_avatar_img($avatar);
		
		$content .= '				<div class="container-fixed">' . PHP_EOL;
		
		$content .= '					<div class="row" style="margin-left:0px; margin-right:0px;">' . PHP_EOL;
		$content .= '						<div class="col-xs-2 pull-left" style="height: 80px; padding-left:0px; padding-right:0px;">' . PHP_EOL;
		$content .= '							<div class="avatar-clipped" style="background-image: url(\'' . $avatar_img . '\');"></div>' . PHP_EOL;
		$content .= '						</div>' . PHP_EOL;
		
		$content .= '						<div class="col-xs-10 pull-left" style="padding-left:30px;">' . PHP_EOL;

		$content .= '							<p><strong>' . $current_user->nickname . '</strong> said just now </p>' . PHP_EOL;
		$content .= '							<p id="bimbler-new-comment"></p>' . PHP_EOL;
		$content .= '						</div>' . PHP_EOL;
		$content .= '					</div> <!-- /row -->' . PHP_EOL;
		
		$content .= '				</div>';

		$content .= '			</div>'. PHP_EOL;
		$content .= '		</li>'. PHP_EOL;
		$content .= '		</div>'. PHP_EOL;
		
		return $content;
	}	
	
	function bimbler_mobile_show_comments ($comment) {
		global $bimbler_mobile_time_str;

		$content = '';
		
		if ($comment->comment_parent == 0) {
			$content .= '		<li class="dd-item" style="border-top: 1px solid #ebebeb;">'. PHP_EOL;
		} else {
			$content .= '		<li class="dd-item">'. PHP_EOL;				
		}
		
		
		$content .= '			<div class="dd-handle">'. PHP_EOL;

		$avatar .= get_avatar ($comment->comment_author_email, null, null);
		$avatar_img = bimbler_get_avatar_img($avatar);
		
		$content .= '<div class="container-fixed">' . PHP_EOL;
		
		$content .= '				<div class="row" style="margin-left:0px; margin-right:0px;">' . PHP_EOL;
		$content .= '					<div class="col-xs-2 pull-left" style="height: 80px; padding-left:0px; padding-right:0px;">' . PHP_EOL;
		$content .= '						<div class="avatar-clipped" style="background-image: url(\'' . $avatar_img . '\');"></div>' . PHP_EOL;
		$content .= '					</div>' . PHP_EOL;
		
		$content .= '					<div class="col-xs-10 pull-left" style="padding-left:30px;">' . PHP_EOL;

		$content .= '						<div><strong>' . $comment->comment_author . '</strong> said on ' . date ($bimbler_mobile_time_str, strtotime($comment->comment_date)) . '</div>' . PHP_EOL;
		$content .= '						<div class="pull-right"><a href="#"><i class="fa fa-reply-all fa-2x"></i></a></div>' . PHP_EOL;
		
		$content .= apply_filters('the_content', $comment->comment_content) . PHP_EOL;
		
		$content .= '					</div>' . PHP_EOL;
		$content .= '				</div> <!-- /row -->' . PHP_EOL;
		
		$content .= '</div>';

		$content .= '			</div>'. PHP_EOL;
			
		$children = bimbler_mobile_get_comment_children ($comment->comment_ID);
		
		if (!empty ($children)) {
			
			$content .= '			<ul class="dd-list">' .PHP_EOL;

			foreach ($children as $child) {
				
				// Only show direct descendents.
				if ($comment->comment_ID == $child->comment_parent) {
				
					$content .= bimbler_mobile_show_comments($child);
						
				}
			}
			
			$content .= '			</ul>' . PHP_EOL;
		}
			
		$content .= '		</li>'. PHP_EOL;
		
		return $content;
	}
	
	/**
	 * Adds the comments tab.
	 *
	 * @param
	 */
	function bimbler_mobile_render_comments ($event_id) {
		global $bimbler_mobile_time_str;
		global $bimbler_mobile_day_time_str;
		global $bimbler_mobile_date_str;
	
	
		$post_id = $event_id;
	
		$content = '';
	
		//return null;
	
		// Only show content to logged-in users, and only if we're on an event page.
		if (is_user_logged_in()) {
			
			$nonce = wp_create_nonce('bimbler_comment');
	
			$content .= '';
			
			
			$content .= '	<div class="row">' . PHP_EOL;
  			$content .= '		<div class="col-lg-12">' . PHP_EOL;
			$content .= '    		<div class="input-group">' . PHP_EOL;
			$content .= '     		 	<input type="text" id="bimbler-comment" class="form-control" placeholder="Post a comment...">' . PHP_EOL;
			$content .= '     		 	<span class="input-group-btn">' . PHP_EOL;
			$content .= '        			<button class="btn btn-warning comment-post-button" type="button"';
			$content .= ' data-event-id="' . $post_id . '" ';
			$content .= ' data-nonce="' . $nonce . '" ';
			$content .= ' data-loading-text="<i class=\'fa fa-spinner fa-spin\'></i> Post">Post</button>' . PHP_EOL;
			$content .= '      			</span>' . PHP_EOL;
			$content .= '    		</div><!-- /input-group -->' . PHP_EOL;
			$content .= '  		</div><!-- /.col-lg-12 -->' . PHP_EOL;
			$content .= '	</div><!-- /.row -->' . PHP_EOL;

			$content .= '	<div class="panel panel-default">' . PHP_EOL;
			$content .= '		<div class="panel-heading">' . PHP_EOL;
			$content .= '			<h4 class="panel-title">Comments</h4>' . PHP_EOL;
			$content .= '		</div>' . PHP_EOL;
			
			$content .= '		<div class="panel-body" style="padding: 0px;">' . PHP_EOL;
			
			$content .= '	<div class="panel-body" style="padding:0px;">' . PHP_EOL;
			$content .= '		<div id="list-1" class="nested-list dd xwith-margins"><!-- adding class "with-margins" will separate list items -->' . PHP_EOL;			

			$content .= bimbler_mobile_show_hidden_comment(null);
			
			$content .= '			<ul class="dd-list">' .PHP_EOL;

			$comments = bimbler_mobile_get_comment_children(null, $post_id);
				
			foreach ($comments as $comment) {
				
				// Only show root-level comments.
				if (0 == $comment->comment_parent) {
				
					$content .= bimbler_mobile_show_comments($comment);
					
				}
			}
			
			$content .= '			</ul>' . PHP_EOL;
			
			$content .= '		</div>' . PHP_EOL;
			$content .= '	</div>' . PHP_EOL;
			
			$content .= '		</div>' . PHP_EOL;
			
			$content .= '	</div>' . PHP_EOL;

		} else {
			$content .= '<h2>Please log in.</h2>' . PHP_EOL;
		}
	
		return $content;
	}

	/**
	 * Adds the locator tab.
	 *
	 * @param
	 */
	function bimbler_mobile_render_locator_canvas ($event_id, $rwgps_id) {
		global $bimbler_mobile_time_str;
		global $bimbler_mobile_day_time_str;
		global $bimbler_mobile_date_str;
	
		global $current_user;
		get_currentuserinfo();
		
		
		$content = '';
		
		$content = '';
		
		$nonce = wp_create_nonce('bimbler_locator');
		
		// Test.
		//$rwgps_id = 6463068;
		
		
		$map_style = 'height: 500px; width: 100%; margin-bottom: 15px;';
		
		//$content .= '<h5>Input Parameters</h5>';
		
		//$content .= print_r ($a, true);
		//$content .= '<br>';
		
		$content .= '<div class="bimbler-locator">';
		
		$content .= '<div id="bimbler_mobile_locator_map_canvas" style="' . $map_style . '"';
		
		$content .= ' data-event-id="' . $event_id . '"';
		
		$content .= ' data-user-id="' . $current_user->ID . '"';
		
		$content .= ' data-nonce="' . $nonce . '"';
		
		if (isset ($rwgps_id)) {
			$content .= ' data-rwgps-id="' . $rwgps_id . '"';
		}
		
		$content .= '></div>';
		
		$content .= '</div>';
		
		
		return $content;
	}
	
	/**
	 * Adds the locator tab.
	 *
	 * @param
	 */
	function bimbler_mobile_render_locator ($event_id) {
		global $bimbler_mobile_time_str;
		global $bimbler_mobile_day_time_str;
		global $bimbler_mobile_date_str;
	
	
		$post_id = $event_id;
	
		$content = '';
	
		//return null;
	
		// Only show content to logged-in users, and only if we're on an event page.
		if (is_user_logged_in()) {
				
			$nonce = wp_create_nonce('bimbler_locator');
			
			$rwgps_id = Bimbler_RSVP::get_instance()->get_rwgps_id ($event_id);
	
			$content .= '';
				
			$content .= '	<div class="panel panel-default">' . PHP_EOL;
			$content .= '		<div class="panel-heading">' . PHP_EOL;
			$content .= '			<h4 class="panel-title pull-left">Locator <span id="bimbler-locator-indicator"></span></h4>' . PHP_EOL;
			$content .= '			<div class="row" style="margin-right: 0px;">';
			$content .= '				<div class="input-group pull-right">' . PHP_EOL;
			$content .= '					<label class="checkbox-inline"><input id="bimbler-trackme-toggle" type="checkbox" xchecked xdata-toggle="toggle">Track me</label>' . PHP_EOL;
			//$content .= '				Track me: <input type="checkbox" checked data-toggle="toggle">' . PHP_EOL;
			$content .= '				</div>' . PHP_EOL;
			$content .= '			</div>';
			$content .= '		</div>' . PHP_EOL;
			
				
			$content .= '		<div class="panel-body">' . PHP_EOL;
			
			//$content .= '<strong>Work in progress...</strong>' . PHP_EOL;
			
			$this_rsvp = Bimbler_RSVP::get_instance()->get_current_rsvp ($event_id);
			
			// Only show to admin users, or to those who have RSVPd 'Yes' to this event.
			if (!current_user_can( 'manage_options' ) &&
					(!isset ($this_rsvp) || ('Y' != $this_rsvp))) {
						
				$content .= '<div class="bimbler-alert-box notice"><span>Notice: </span>You must RSVP for this event to see this page.</div>';

			} elseif (!current_user_can( 'manage_options' ) && // Don't show if not in-progress.
					(!Bimbler_RSVP::get_instance()->is_event_in_progress($event_id))) {
				 
				$content .= '<div class="bimbler-alert-box notice"><span>Notice: </span>The event will not be starting soon, or finished a while ago.</div>';
				
			} else { // All good - render the locator.
				
				$content .= bimbler_mobile_render_locator_canvas ($event_id, $rwgps_id);
				
			}
			
			$content .= '		</div>' . PHP_EOL;
			$content .= '	</div>' . PHP_EOL;

			// The who's who box.
			$content .= '	<div class="panel panel-default">' . PHP_EOL;
			$content .= '		<div class="panel-heading">' . PHP_EOL;
			$content .= '			<h4 class="panel-title">Who\'s who</h4>' . PHP_EOL;
			$content .= '		</div>' . PHP_EOL;
			
			$content .= '		<div class="panel-body">' . PHP_EOL;

			// Content here.
			$content .= '		<span id="bimbler-whos-who"><i class=\'fa fa-spinner fa-spin\'></i></span>';
				
			$content .= '		</div>' . PHP_EOL;
			$content .= '	</div>' . PHP_EOL;

			$content .= '<span id="bimbler-debug-output"></span>' . PHP_EOL;
				
	
		} else {
			$content .= '<h2>Please log in.</h2>' . PHP_EOL;
		}
	
		return $content;
	}	
	/**
	 * Adds the ride page to the event.
	 *
	 * @param
	 */
	function bimbler_mobile_render_ride_page ($event_id, $ride_page) {

		$post_id = $event_id;
	
		$content = '';
		
		//return null;
	
		// Only show content to logged-in users, and only if we're on an event page.
		if (is_user_logged_in()) {
	
			//$meta_ride_page = get_post_meta ($post_id, '_BimblerRidePage', true);
	
/*			if (!isset ($meta_ride_page) || empty ($meta_ride_page)) {
				//error_log ('No ride page for event ID ' . $post_id);
	
				// Nothing to do.
				return null;
			} */
				
			//error_log ('Got page meta ' . $meta_ride_page . ' for event ID ' . $post_id);
				
			$post_object = get_post ($ride_page);
	
			if (!isset($post_object)) {
				error_log ('Cannot get post object for event ID '. $ride_page);
				return null;
			}
	
			//var_dump ($post_object->post_content);
	
			//$content .= '<h3>Ride Details <a href="' . site_url () . '/wp-admin/post.php?post=' . $ride_page . '&action=edit" target="_external"><i class="fa fa-pencil"></i></a></h3>';
	
			//$content .= apply_filters( 'the_content', $post_object->post_content);
	
			//$content .= '<br><br><br>';
			
			$content .= '	<div class="panel panel-default">' . PHP_EOL;
			$content .= '		<div class="panel-heading">' . PHP_EOL;
			$content .= '			<h4 class="panel-title">Ride details</h4>' . PHP_EOL;
			$content .= '		</div>' . PHP_EOL;
			
			$content .= '		<div class="panel-body">' . PHP_EOL;
			$content .= apply_filters( 'the_content', $post_object->post_content);
			$content .= '		</div>' . PHP_EOL;
			
			$content .= '	</div>' . PHP_EOL;
				
			
			
		}
		
		return $content;
	}

	function bimbler_mobile_render_event_tab_bar ($event_id, $future = true) {
		global $bimbler_mobile_events_per_page;
			
		$content = '';
	
		$when = 'upcoming';
	
		if (!$future) {
			$when = 'past';
		}
	
		$post = get_post ($event_id);
	
		if (!isset($post)) {
			error_log ('Cannot get post object for event ID '. $meta_ride_page);
			return null;
		}
	
		$content .= '	<!-- Event tab bar. -->' . PHP_EOL;

		global $bimbler_mobile_tabs;
		global $bimbler_mobile_tab_icons;
		global $bimbler_mobile_tab_text;
		global $bimbler_mobile_tab_class;

		global $bimbler_mobile_tab_summary;
		global $bimbler_mobile_tab_details;
		global $bimbler_mobile_tab_map;
		global $bimbler_mobile_tab_rsvps;
		global $bimbler_mobile_tab_photos;
		global $bimbler_mobile_tab_comments;
		global $bimbler_mobile_tab_locator;
		
		$do_summary_tab = true;
		$do_details_tab = true;
		$do_map_tab = true;
		$do_rsvps_tab = true;
		$do_photos_tab = true;
		$do_comments_tab = true;
		$do_locator_tab = true;
		
		$content_tab_list = '';
		$content_tab_content = '';
		
		$count_pics_pill = '';
		
		$first = true;

		$meta_ride_page = get_post_meta ($post->ID, '_BimblerRidePage', true);
			
		if (!isset ($meta_ride_page) || empty ($meta_ride_page)) {
			$do_details_tab = false;
		}

		$rwgps_id = Bimbler_RSVP::get_instance()->get_rwgps_id ($post->ID);
			
		if (0 == $rwgps_id) {
			$do_map_tab = false;
		}

		$meta_gallery_id = get_post_meta ($post->ID, 'bimbler_gallery_id', true);

		//error_log ('Gallery: ' . $meta_gallery_id);

		if (!isset ($meta_gallery_id[0]) || empty ($meta_gallery_id)) {
			$do_photos_tab = false;
		}

		$content .= PHP_EOL . '	<!-- Tabs for event ID ' . $post->ID . '. -->' . PHP_EOL;
		
		foreach ($bimbler_mobile_tabs as $sub_page) {
			$do_tab = false;
			
			$count_pill = '';
			
			// Work out which tabs we're going to render.
			switch ($sub_page) {

				case $bimbler_mobile_tab_summary:
					$do_tab = true;
					break;

				case $bimbler_mobile_tab_details:
					if ($do_details_tab) {
						$do_tab = true;
					}
					break;

				case $bimbler_mobile_tab_map:
					if ($do_map_tab) {
						$do_tab = true;
					}
					break;

				case $bimbler_mobile_tab_rsvps:
					$do_tab = true;

					$num_rsvps = Bimbler_RSVP::get_instance()->count_rsvps ($post->ID);
			
					if ($num_rsvps > 0) {
						$count_pill = 'data-notifications="' . $num_rsvps . '"';
					}
					
					break;

				case $bimbler_mobile_tab_photos:
					if ($do_photos_tab) {
						$do_tab = true;
						
						$num_pics = Bimbler_RSVP::get_instance()->get_gallery_pic_count ($post->ID);
							
						if ($num_pics > 0) {
							$count_pill = 'data-notifications="' . $num_pics . '"';
						}
					}
					break;

				case $bimbler_mobile_tab_comments:
					
					$do_tab = true;
					
					$num_comments = bimbler_mobile_get_comment_count ($post->ID);
			
					if ($num_comments > 0) {
						$count_pill = 'data-notifications="' . $num_comments . '"';
					}
					break;

				case $bimbler_mobile_tab_locator:
					
					$do_tab = false;
					$do_locator_tab = false;
						
					$this_rsvp = Bimbler_RSVP::get_instance()->get_current_rsvp ($post->ID);
						
					// Only show to admin users, or to those who have RSVPd 'Yes' to this event.
					if (current_user_can( 'manage_options' ) ||
						(isset ($this_rsvp) && ('Y' == $this_rsvp))) {
						
						$do_tab = true;
						$do_locator_tab = true;
					}
					break;
						
			}

			if ($do_tab) {

				$class = $bimbler_mobile_tab_class[$sub_page];

				$icon = $bimbler_mobile_tab_icons[$sub_page];
				$text = $bimbler_mobile_tab_text[$sub_page];
					

				$content_tab_list .= '			<li role="presentation"';
				
				if ($first) {
					$content_tab_list .= ' class="active" ';
				}
				
				$content_tab_list .= '>' . PHP_EOL;
					
				$controls = 'event-'. $post->ID . '-tab' . $sub_page;
					
				$content_tab_list .= '			<a href="#' . $controls . '" aria-controls="' . $controls . '" role="tab" data-toggle="pill" data-icon="' . $icon .'" class="' . $class . ' bimbler-badge" data-bimbler-event-id="' . $post->ID . '" ' . $count_pill . '>';
				$content_tab_list .= '<i class="' . $icon . '" aria-hidden="true"></i>';
				//$content_tab_list .= $text;
				$content_tab_list .= '</a></li>' . PHP_EOL;
			}

				$first = false;
		} // End foreach tab.

		$content .= '	<div role="tabpanel">' . PHP_EOL;

		$content .= '		<ul class="nav nav-pills nav-justified" role="tablist" id="bimbler-tab">' . PHP_EOL;
		$content .= $content_tab_list;
		$content .= '		</ul>' . PHP_EOL;

		$content .= '	</div> <!-- /tabpanel -->' . PHP_EOL;

		$content .= '	<!-- /Tabs for event ID ' . $post->ID . '. -->' . PHP_EOL . PHP_EOL;
	
		echo $content;
	}

	function bimbler_mobile_render_event_page ($event_id, $future = true) {
		global $bimbler_mobile_events_per_page;
			
		$content = '';
	
		$when = 'upcoming';
	
		if (!$future) {
			$when = 'past';
	
			error_log ('Showing past events');
		}
	
		$post = get_post ($event_id);
	
		if (!isset($post)) {
			error_log ('Cannot get post object for event ID '. $meta_ride_page);
			return null;
		}
	
		$content .= '	<!-- Events content. -->' . PHP_EOL;
	
		global $bimbler_mobile_tabs;
		global $bimbler_mobile_tab_icons;
		global $bimbler_mobile_tab_text;
		global $bimbler_mobile_tab_class;

		global $bimbler_mobile_tab_summary;
		global $bimbler_mobile_tab_details;
		global $bimbler_mobile_tab_map;
		global $bimbler_mobile_tab_rsvps;
		global $bimbler_mobile_tab_photos;
		global $bimbler_mobile_tab_comments;
		global $bimbler_mobile_tab_locator;
			
			
		$do_summary_tab = true;
		$do_details_tab = true;
		$do_map_tab = true;
		$do_rsvps_tab = true;
		$do_photos_tab = true;
		$do_comments_tab = true;
		$do_locator_tab = true;
			
		$content_tab_list = '';
		$content_tab_content = '';

		$first = true;

		$meta_ride_page = get_post_meta ($post->ID, '_BimblerRidePage', true);
			
		if (!isset ($meta_ride_page) || empty ($meta_ride_page)) {
			$do_details_tab = false;
		}

		$rwgps_id = Bimbler_RSVP::get_instance()->get_rwgps_id ($post->ID);
			
		if (0 == $rwgps_id) {
			$do_map_tab = false;
		}

		$meta_gallery_id = get_post_meta ($post->ID, 'bimbler_gallery_id', true);

		//error_log ('Gallery: ' . $meta_gallery_id);

		if (!isset ($meta_gallery_id[0]) || empty ($meta_gallery_id)) {
			$do_photos_tab = false;
		}


		$content .= PHP_EOL . '	<!-- Sub-pages for event ID ' . $post->ID . '. -->' . PHP_EOL;

		foreach ($bimbler_mobile_tabs as $sub_page) {
			$do_tab = false;

			// Work out which tabs we're going to render.
			switch ($sub_page) {

				case $bimbler_mobile_tab_summary:
					$do_tab = true;
					break;

				case $bimbler_mobile_tab_details:
					if ($do_details_tab) {
						$do_tab = true;
					}
					break;

				case $bimbler_mobile_tab_map:
					if ($do_map_tab) {
						$do_tab = true;
					}
					break;

				case $bimbler_mobile_tab_rsvps:
					$do_tab = true;
					break;

				case $bimbler_mobile_tab_photos:
					if ($do_photos_tab) {
						$do_tab = true;
					}
					break;

				case $bimbler_mobile_tab_comments:
					$do_tab = true;
					break;
					
				case $bimbler_mobile_tab_locator:
						
					$do_tab = false;
					$do_locator_tab = false;
				
					$this_rsvp = Bimbler_RSVP::get_instance()->get_current_rsvp ($post->ID);
				
					// Only show to admin users, or to those who have RSVPd 'Yes' to this event.
					if (current_user_can( 'manage_options' ) ||
						(isset ($this_rsvp) && ('Y' == $this_rsvp))) {
			
							$do_tab = true;
							$do_locator_tab = true;
					}
					break;
			}

			if ($do_tab) {

				$class = $bimbler_mobile_tab_class[$sub_page];


				$icon = $bimbler_mobile_tab_icons[$sub_page];
				$text = $bimbler_mobile_tab_text[$sub_page];
					

				$content_tab_list .= '			<li role="presentation"';
				$content_tab_list .= ' class="' . ($first ? 'active' : '') . '">' . PHP_EOL;
					
				$controls = 'event-'. $post->ID . '-tab' . $sub_page;
					
				$content_tab_list .= '			<a href="#' . $controls . '" aria-controls="' . $controls . '" role="tab" data-toggle="pill" data-icon="' . $icon .'" data-bimbler-event-id="' . $post->ID . '">';
				$content_tab_list .= '<i class="' . $icon . '" aria-hidden="true"></>';
				$content_tab_list .= '</a></li>' . PHP_EOL;
					
					
				$content_tab_content .= '		<div role="tabpanel"';
				$content_tab_content .= ' class="tab-pane fade ' . ($first ? 'in active' : '') . '"';
				$content_tab_content .= ' id="' . $controls . '">' . PHP_EOL;

				if (1 == $sub_page) {

					$content_tab_content .= bimbler_mobile_render_summary_page($post->ID);

				} else if (2 == $sub_page) {

					$content_tab_content .= bimbler_mobile_render_ride_page($post->ID, $meta_ride_page);

				} else if (3 == $sub_page) {

					$content_tab_content .= bimbler_mobile_render_map($post->ID, $rwgps_id);

				} else if (4 == $sub_page) {

					$content_tab_content .= bimbler_mobile_render_rsvps($post->ID);

				} else if (5 == $sub_page) {

					$content_tab_content .= bimbler_mobile_render_photos($post->ID);

				} else if (6 == $sub_page) {

					$content_tab_content .= bimbler_mobile_render_comments($post->ID);

				} else if (7 == $sub_page) {

					$content_tab_content .= bimbler_mobile_render_locator($post->ID);

				}
				else {

					$content_tab_content .= '			<p>Event ' . $post->ID . ', tab ' . $sub_page . '  content</p>' . PHP_EOL;
				}

				$content_tab_content .= '		</div> <!-- /tabpanel -->' . PHP_EOL;


				// The footer.
				//$content .= '		<div data-role="footer" data-position="fixed">' . PHP_EOL;
				//$content .= '			<h2>&copy;&nbsp;2015 Brisbane Bimblers</h2>' . PHP_EOL;
				//$content .= '		</div>' . PHP_EOL;


			} // End if do tab.

				$first = false;
		} // End foreach tab.

		$content .= '	<div role="tabpanel">' . PHP_EOL;
					
		// The content.
		$content .= '		<div class="tab-content">' . PHP_EOL;
		$content .= $content_tab_content;
		$content .= '		</div> <!-- /tab-content -->' . PHP_EOL;

		$content .= '	</div> <!-- /tabpanel -->' . PHP_EOL;

		$content .= '	<!-- /Sub-pages for event ID ' . $post->ID . '. -->' . PHP_EOL . PHP_EOL;
	
		$content .= '	<!-- /Events content. -->' . PHP_EOL . PHP_EOL;
	
		echo $content;
	}
	
	function bimbler_mobile_render_events_listview ($which = 'upcoming') {//$future = true) {
		
		global $bimbler_mobile_events_per_page;
		
		$time_str = 'D j M g:ia';
		$month_str = 'F';
		
		$content = '';
		
/*
			$posts = tribe_get_events( array(
					'eventDisplay' 	=> 'custom',
					'posts_per_page'=>	$instance["events_num"],
					'meta_query' 	=> array(
							array(
									'key' 		=> '_EventStartDate',
									'value' 	=> date('Y-m-d H:i:s'), // Now onwards.
									'compare' 	=> '>',
									'type' 		=> 'date'
							)
					)));
 
 */
		
		if ('upcoming' == $which) {
			
			$when = $which;
				
			$posts = tribe_get_events( array(
					'eventDisplay' 	=> 'custom',
					'posts_per_page'=>	$bimbler_mobile_events_per_page,
					'meta_query' 	=> array(
							array(
									'key' 		=> '_EventStartDate',
									'value' 	=> date('Y-m-d H:i:s'), // Now onwards.
									'compare' 	=> '>',
									'type' 		=> 'date'
							)
					)));
		}

		if ('past' == $which) {
		
			$when = $which;

			$posts = tribe_get_events( array(
					'eventDisplay' 	=> 'custom',
					'posts_per_page'=>	$bimbler_mobile_events_per_page,
					'order'			=> 'DESC', 
					'meta_query' 	=> array(
							array(
									'key' 		=> '_EventStartDate',
									'value' 	=> date('Y-m-d H:i:s'), // Now onwards.
									'compare' 	=> '<=',
									'type' 		=> 'date'
							)
					)));
		}
		
		if ('newest' == $which) {
			
			if ( function_exists( 'tribe_get_events' ) ) {
				$args = array(
						'eventDisplay'   => 'all', //'upcoming',
						'posts_per_page' => $bimbler_mobile_events_per_page,
						'orderby'		=> 'post_date',
						'order'			=> 'DESC'
				);
					
				$posts = tribe_get_events( $args );
			}
		}
		
		if ($posts)
		{
			$first = true;
			$divider = '';
			
			foreach ($posts as $post)
			{
				$event_date = $post->EventStartDate;
		
				$event_month = date ($month_str, strtotime($event_date));
				
				$rsvpd = Bimbler_RSVP::get_instance()->get_current_rsvp ($post->ID);
				$num_rsvps = Bimbler_RSVP::get_instance()->count_rsvps ($post->ID);
				
				if (empty ($num_rsvps)) {
					$num_rsvps = 0;
				}
				
				$excerpt = $post->post_excerpt;
		
				$rwgps_id = Bimbler_RSVP::get_instance()->get_rwgps_id ($post->ID);
				
				// Nothing found, use Tomewin.
				if (0 == $rwgps_id) {
					$rwgps_id = 5961603; 
				}
				
				// Add a divider if we need one.
				if ($first && ('upcoming' == $when)) {

					$content .= '		<div class="list-group-item" style="background-color: #e9e9e9;"><h4>Next ride:</h4></div>' . PHP_EOL;

				}
				else if ($first && ('newest' == $which)) {
					
					$content .= '		<div class="list-group-item" style="background-color: #e9e9e9;"><h4>Recently added:</h4></div>' . PHP_EOL;
				
				} else {
		
					// Don't bother with a divider if we're rendering recently updated rides.
					if (('newest' != $which) && ($divider != $event_month)) {
						
						$divider = $event_month;
		
						$content .= '		<div class="list-group-item" style="background-color: #e9e9e9;"><h4>' . $divider . '</h4></div>' . PHP_EOL;
					}
				}
		
				$content .= '		<a class="list-group-item bimbler-spinner-source" href="event.php?event=' . $post->ID . '&' . $which . '=1"  title="' . $post->post_title . '">' . PHP_EOL;
				$content .= '			<span class="badge pp-valign-centre">' . $num_rsvps . '</span>' . PHP_EOL;

				$addr = get_venue_address($post->ID);
				
				// For the first event display a full-width map.
				if ($first && (!empty ($addr))) {
					$content .= ' 				<div class="row">' . PHP_EOL;

					$content .= '					<div class="col-xs-8">' . PHP_EOL;
						
					
					$content .= '				<h4 style="text-overflow: none!important;">' . $post->post_title . '</h4>' . PHP_EOL;
					$content .= '				<p><strong>' . date ($time_str, strtotime($event_date)) . '</strong></p>' . PHP_EOL;
					$content .= '				<p style="white-space: normal!important; text-overflow: initial!important; overflow: auto!important;">' . $excerpt . '</p>' . PHP_EOL;
				
					$content .= '				</div>' . PHP_EOL;
					
					// Add the spinner for on-click.
					$content .= '				<div class="col-xs-2 bimbler-spinner-target" style="top: 25px;">' . PHP_EOL;
					$content .= '				</div>' . PHP_EOL;
					
					$content .= '				</div>' . PHP_EOL;
						
					$content .= '				<div class="tribe-events-venue-map">' . PHP_EOL;
					$content .= tribe_get_embedded_map ($post->ID, '100%', '150px', true) . PHP_EOL;
					$content .= '				</div>' . PHP_EOL;

						
				}
				else {				
					// Note that total width of below is not 12.
					$content .= '				<div class="row">' . PHP_EOL;
						
					$content .= '					<div class="col-xs2 pull-left" style="height: 80px;">' . PHP_EOL;
					
					$content .= '						<div class="rsvp-checkin-indicator-noajax">' . PHP_EOL;
					
					$content .= '						<div class="avatar-clipped bimbler-spinner-target" style="background-image: url(\'http://assets2.ridewithgps.com/routes/' . $rwgps_id . '/thumb.png\');"></div>' . PHP_EOL;
					
					if (!isset ($rsvpd)) {
						$content .= '						<div class="rsvp-checkin-indicator-none"><i class="fa-question-circle"></i></div>' . PHP_EOL;
					} else if ('Y' == $rsvpd) {
						$content .= '						<div class="rsvp-checkin-indicator-yes"><i class="fa-check-circle"></i></div>' . PHP_EOL;
					}
					else {
						$content .= '						<div class="rsvp-checkin-indicator-no"><i class="fa-times-circle"></i></div>' . PHP_EOL;
					}
					
					$content .= '						</div>' . PHP_EOL;
														
					$content .= '					</div>' . PHP_EOL;
					
					$content .= '					<div class="col-xs-8" style="padding-left:10px;">' . PHP_EOL;
					$content .= '						<h4 style="text-overflow: none!important;">' . $post->post_title . '</h4>' . PHP_EOL;
					//$content .= '						<p class="xui-li-aside" style="text-align: right"><strong>' . date ($time_str, strtotime($event_date)) . '</strong></p>' . PHP_EOL;
					$content .= '						<p xclass="pull-right xui-li-aside" xstyle="text-align: right"><strong>' . date ($time_str, strtotime($event_date)) . '</strong></p>' . PHP_EOL;
					$content .= '					</div>' . PHP_EOL;
					
					$content .= '					<div class="col-xs-2 xbimbler-spinner-target">' . PHP_EOL;
					$content .= '					</div>' . PHP_EOL;
					
					$content .= '				</div> <!-- /row -->' . PHP_EOL;
				}
		
				$content .= '		</a>' . PHP_EOL;
		
				if ($first) {
					$first = false;
				}
						
			} // foreach
		} else { // if posts

			if ('upcoming' == $when) {
				$content .= '<h4>No up-coming events.</h4>';
			}
			else if ('past' == $when) {
				$content .= '<h4>No past events.</h4>';
			} else {
				$content .= '<h4>No events.</h4>';
			}
		}
		
		echo $content;
	
	}

?>