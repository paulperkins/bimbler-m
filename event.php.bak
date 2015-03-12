<!DOCTYPE html> 
<html> 
  <head> 
<?php 
	require('../wp-blog-header.php');
	
	
	$event_id = $_GET['event_id'];
	
	$post_object = get_post ($event_id);
?>
  
  
  		<meta charset="utf-8"> 
		<title>Bimblers - <?php echo $post_object->post_title; ?></title>
        
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
		<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script> 
		<link rel="stylesheet" href="jqm.css">
		<script src="jquery.js"></script>
		<script src="jqm.js"></script>
		<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="Brisbane Bimblers Cycling" />
		<meta name="author" content="" />
		
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimal-ui" />
		<meta name="apple-mobile-web-app-status-bar-style" content="yes" />
		<meta name="mobile-web-app-capable" content="yes">
		

       
       
 </head> 
 <body> 


    <div data-role="page" data-theme="a" class="jqm-demos jqm-home" data-add-back-btn="true"> 

         <div data-role="header" class="jqm-header" data-position="fixed" data-add-back-btn="true"> 
			<a href="/m/" data-ajax="false" data-rel="back" class="ui-btn ui-btn-left ui-alt-icon ui-nodisc-icon ui-corner-all ui-btn-icon-notext ui-icon-carat-l">Back</a>
         	<h1><?php echo $post_object->post_title; ?></h1> 
			<!-- <a href="#" class="jqm-navmenu-link ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-right">Menu</a>-->

         </div> 

         
         
         <div data-role="tabs">
       		<div data-role="navbar">
    			<ul>
    				<li class="ui-btn-active" data-icon="bullets" data-icon-pos="top"><a href="#event-summary">1</a></li>
     				<li data-icon="" data-icon-pos="top"><a href="#event-map">2</a></li>
     				<li data-icon="" data-icon-pos="top"><a href="#">3</a></li>
    			</ul>
    		</div>         
         
  	       	<div id="event-summary" data-role="ui-content">      
				<?php include('single_event_summary.php'); ?>         
         	</div>

			<div id="event-map" data-role="ui-content">      
				<?php include('single_event_map.php'); ?>         
         	</div>
         </div>

		<div data-role="footer" data-position="fixed">
			<h4>&copy; 2015 Brisbane Bimblers</h4>
		</div> 
         
         
        </div>
		
       
        <!-- Menu panel -->
<!--   		<div data-role="panel" class="jqm-navmenu-panel" data-position="right" data-display="overlay" data-theme="a">
			<ul class="jqm-list ui-alt-icon ui-nodisc-icon">
				<li data-filtertext="demos homepage" data-icon="home"><a href="/m/"  data-ajax="false">Home</a></li>
				<li data-filtertext="introduction overview getting started"><a href="/m/intro/">Introduction</a></li>
				<li	data-filtertext="buttons button markup buttonmarkup method anchor link button element"><a href="../button-markup/">Buttons</a></li>
			</ul>
		</div> --> 
         <!-- /Menu panel -->
         
            
    </xdiv> 
    
    
    
<!--     <div data-role="panel" id="outside" data-theme="a">
		<ul data-role="listview">
			<li data-icon="back"><a href="#" data-rel="close">Close</a></li>
			<li>External panel</li>
			<li>Page A</li>
			<li>Reveal</li>
		</ul>
	</div> -->
	
    </body> 
    </html>
