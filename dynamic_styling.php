

<?php
	echo '<!-- Dynamic Styling. -->' . PHP_EOL;
	echo '<style type="text/css">' . PHP_EOL;

	echo '.navbar-header { background-color: ' . ot_get_option('color-1') . '; }' . PHP_EOL;
	echo '[data-notifications]:after { background: ' . ot_get_option('color-1') . '; }' . PHP_EOL;

	echo '</style>' . PHP_EOL;
?>