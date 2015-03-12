<?php

?>

	<div class="ui-body ui-body-a ui-corner-all">
		<!--  <div class="ui-corner-all ui-bar ui-bar-a"> -->	
			<h3>Map</h3> 
		<!--  </div>-->

<?php 
global $post_object;

if (isset ($post_object))
{
	$content = '';
	
	$rwgps_id = Bimbler_RSVP::get_instance()->get_rwgps_id ($post_object->ID);
	
	if (0 == $rwgps_id) {
	
		$content .= '<span>This event does not yet have a map.</span>';
	
	} else {

		$content .= '<div style="padding-left: 15px; padding-right: 15px;">';
		$content .= '<iframe src="//ridewithgps.com/routes/' . $rwgps_id . '/embed" height="800px" width="100%" frameborder="0" scrolling="no" class="iframe-class"></iframe>';
		$content .= '</div>';
	}
	
	echo $content;
}
?>
	
	</div>

				
