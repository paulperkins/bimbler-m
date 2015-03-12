<?php

?>

	<div class="ui-body ui-body-a ui-corner-all">
		<!--  <div class="ui-corner-all ui-bar ui-bar-a"> -->	
			<h3>Summary</h3> 
		<!--  </div>-->

<?php 
global $post_object;

if (isset ($post_object))
{
?>
		<!--  <div class="ui-body ui-body-a ui-corner-all">-->	
			<p><?php echo $post_object->post_content; ?></p>
		<!--  </div>-->
<?php
}
?>
	
	</div>

				
