

<?php
	echo '<!-- Dynamic Styling. -->' . PHP_EOL;
	echo '<style type="text/css">' . PHP_EOL;

	echo '.navbar-header { background-color: ' . ot_get_option('color-1') . '; }' . PHP_EOL;
	echo '.navbar-header span { color: #000!important; }' . PHP_EOL;
	echo '.navbar-brand { color: #000!important; }' . PHP_EOL;
	echo '[data-notifications]:after { background: ' . ot_get_option('color-1') . '; }' . PHP_EOL;
	echo '.badge { background-color: ' . ot_get_option('color-1') . ' ! important; }' . PHP_EOL;
	echo '.nav-justified li.active a { border-bottom-color: ' . ot_get_option('color-1') . '!important; }' . PHP_EOL;

	//echo '.comment-post-button {background-image: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); }' . PHP_EOL;
	echo '.comment-post-button {background-color: ' . ot_get_option('color-1') . '; }' . PHP_EOL;
	
	
	echo '</style>' . PHP_EOL;
?>