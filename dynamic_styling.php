

<?php
	echo '<!-- Dynamic Styling. -->' . PHP_EOL;
	echo '<style type="text/css">' . PHP_EOL;
	
	//$color = ot_get_option('color-1');
	$colour = '#dd9933';

	echo '.navbar-header { background-color: ' . $colour . '; }' . PHP_EOL;
	echo '.navbar-header span { color: #000!important; }' . PHP_EOL;
	echo '.navbar-brand { color: #000!important; }' . PHP_EOL;
	echo '.navbar-toggle { background-color: #c0c0c0!important; }' . PHP_EOL;
	echo '[data-notifications]:after { background: ' . $colour . '; }' . PHP_EOL;
	echo '.badge { background-color: ' . $colour . ' ! important; }' . PHP_EOL;
	echo '.nav-justified li.active a { border-bottom-color: ' . $colour . '!important; }' . PHP_EOL;

	//echo '.comment-post-button {background-image: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); }' . PHP_EOL;
	echo '.comment-post-button {background-color: ' . $colour . '; }' . PHP_EOL;
	
	
	echo '</style>' . PHP_EOL;
?>