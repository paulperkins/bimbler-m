<!DOCTYPE html> 
<html> 
  <head>
  
<?php 
	require('../wp-blog-header.php');
	
	if (!is_user_logged_in()) {

		$redir_to = esc_url (home_url ('/m/'));

//		echo '<script type="text/javascript">alert(\'Redirect to: ' . $redir_to . '\');</script>'; // bloginfo ('url') not set here.

		echo '<script type="text/javascript">window.location.replace(\'https://bimblers.com/m/login.php\');</script>'; // bloginfo ('url') not set here.
//		echo '<script type="text/javascript">window.location.replace(\'' . $redir_to . '\');</script>'; // bloginfo ('url') not set here.
	}

?>
  
   
	<meta charset="utf-8"> 
	<title><?php bloginfo ('name'); ?></title>
	
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script> 
	
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	
	<!-- Optional theme -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">
	
 	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
 		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<link rel="stylesheet" href="fa/css/font-awesome.min.css">
	
	<script type="text/javascript" src="//maps.google.com/maps/api/js"></script>
	
		
	<script src="spin.js"></script>
<!--  	<script src="bimbler.js"></script> -->
		
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="<?php bloginfo ('description'); ?>" />
	<meta name="author" content="" />
		
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimal-ui" />
	<meta name="mobile-web-app-capable" content="yes">
	
	<meta name="apple-mobile-web-app-capable" content="yes" />
<!--  	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" /> --> 
	<meta name="apple-mobile-web-app-status-bar-style" content="white" /> 
	<link rel="apple-touch-startup-image" href="bimbler_ilogo.png">
	
	<!-- Framework7 components. -->
	<script type="text/javascript" src="js/template7.js?v=1"></script>
	<script type="text/javascript" src="js/messagebar.js?v=1"></script>
	<script type="text/javascript" src="js/messages.js?v=1"></script>

	
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="icon" sizes="16x16 32x32 64x64" href="favicon.ico">
	<link rel="icon" type="image/png" sizes="196x196" href="favicon-196.png">
	<link rel="icon" type="image/png" sizes="160x160" href="favicon-160.png">
	<link rel="icon" type="image/png" sizes="96x96" href="favicon-96.png">
	<link rel="icon" type="image/png" sizes="64x64" href="favicon-64.png">
	<link rel="icon" type="image/png" sizes="32x32" href="favicon-32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="favicon-16.png">
	<link rel="apple-touch-icon" sizes="152x152" href="favicon-152.png">
	<link rel="apple-touch-icon" sizes="144x144" href="favicon-144.png">
	<link rel="apple-touch-icon" sizes="120x120" href="favicon-120.png">
	<link rel="apple-touch-icon" sizes="114x114" href="favicon-114.png">
	<link rel="apple-touch-icon" sizes="76x76" href="favicon-76.png">
	<link rel="apple-touch-icon" sizes="72x72" href="favicon-72.png">
	<link rel="apple-touch-icon" href="favicon-57.png">
	<meta name="msapplication-TileColor" content="#FFFFFF">
	<meta name="msapplication-TileImage" content="favicon-144.png">
	<meta name="msapplication-config" content="browserconfig.xml">
	
	<link rel="stylesheet" href="bimbler-bs.css">
	
<?php 
	include_once ('dynamic_styling.php');
?>
		
<script type="text/javascript">
		(function(document,navigator,standalone) {
			// prevents links from apps from oppening in mobile safari
			// this javascript must be the first script in your <head>
			if ((standalone in navigator) && navigator[standalone]) {
				var curnode, location=document.location, stop=/^(a|html)$/i;
				document.addEventListener('click', function(e) {
					curnode=e.target;
					while (!(stop).test(curnode.nodeName)) {
						curnode=curnode.parentNode;
					}
					// Condidions to do this only on links to your own app
					// if you want all links, use if('href' in curnode) instead.
					if(
						'href' in curnode && // is a link
						(chref=curnode.href).replace(location.href,'').indexOf('#') && // is not an anchor
						(	!(/^[a-z\+\.\-]+:/i).test(chref) ||                       // either does not have a proper scheme (relative links)
							chref.indexOf(location.protocol+'//'+location.host)===0 ) // or is in the same protocol and domain
					) {
						e.preventDefault();
						location.href = curnode.href;
					}
				},false);
			}
		})(document,window.navigator,'standalone');
</script>
		

       
       <style type="text/css">
       /* Fix-up navbar overlapping content. */
body {
	padding-top: 50px; 
	//padding-bottom: 20px; 
}
       </style>
       
</head> 
<body> 

<?php
 
	include_once ('events.php');

?>

	<!-- Index page. -->
	<div id="bimbler-spinner-target" data-role="page" data-theme="a" class="jqm-demos jqm-home "> 

<script type="text/javascript">

		var target = document.getElementById("bimbler-spinner-target");
		
		window.spinner = new Spinner().spin(target);
	    
</script>
	
	
  		<div> 
  		<nav class="navbar navbar-default navbar-fixed-top">
  				<div class="navbar-header">
  					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			      </button>
			      <a class="navbar-brand"><?php bloginfo ('description'); ?></a>
			    </div>

			    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			      <ul class="nav navbar-nav">
			        <li <?php echo ((!isset ($_GET['past']) && !isset ($_GET['newest'])) 	? ' class="active" ' : ' ');?>><a href="/m/"><i class="fa fa-home fw"></i> Home - up-coming events <span class="sr-only">(current)</span></a></li>
			        <li <?php echo (isset ($_GET['past']) 								? ' class="active" ' : ' ');?>><a href="/m/?past=1"><i class="fa fa-clock-o fw"></i> Past events</a></li>
			        <li <?php echo (isset ($_GET['newest']) 								? ' class="active" ' : ' ');?>><a href="/m/?newest=1"><i class="fa fa-star-o fw"></i> Recently Added</a></li>
			        
	<!--  			        </li>-->
			      </ul>
			    </div>
			</div>
		</nav> 
		</div> 

		<!-- Content.  -->
		<div data-role="content" id="xspinner-target">      
				<div class="list-group" xdata-inset="false" xdata-dividertheme="b" xdata-count-theme="b"> 
<?php 

		// Render the list of events sorted by post_date DESC
		
		if (isset ($_GET['newest'])) {

			bimbler_mobile_render_events_listview ('newest');

		} else {

			bimbler_mobile_render_events_listview (isset ($_GET['past']) ? 'past' : 'upcoming');

		}
	
?>
				</div>           
		</div>
		<!-- /Content -->

<!-- 		<nav class="navbar navbar-default navbar-fixed-bottom">
			<div class="navbar-header">
				<a class="navbar-brand">&copy; 2015 <?php bloginfo ('description'); ?></a>
			</div>
		</nav> -->
		
	</div> 

	<!-- /Index page. -->
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    
   	<script src="bimbler-bs.js"></script> 
    
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-49752930-2', 'auto');
  ga('send', 'pageview');

</script>
    
</body> 
</html>
