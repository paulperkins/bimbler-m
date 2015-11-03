<!DOCTYPE html> 
<html> 
  <head> 
<?php
	require('../wp-blog-header.php');
	
	if (!is_user_logged_in()) {
		echo '<script type="text/javascript">window.location.replace(\'/m/login.php\');</script>';
	} 
?>
  
  <meta charset="utf-8"> 
	<title><?php bloginfo ('name'); ?></title>
	
	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script> 
	
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
	
	<!-- Swiper -->
	<link rel="stylesheet" href="swiper/dist/css/swiper.css"> 

	<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
	
    <link href="ps/dist/photoswipe.css?v=4.0.5-1.0.4" rel="stylesheet" />
    <link href="ps/dist/default-skin/default-skin.css?v=4.0.5-1.0.4" rel="stylesheet" />
	 
	<script src="ps/dist/photoswipe.js?v=4.0.5-1.0.4"></script> 
    <script src="ps/dist/photoswipe-ui-default.min.js?v=4.0.5-1.0.4"></script>  
	
	<link rel="stylesheet" href="toastr.css">
	<script type="text/javascript" src="toastr.js"></script>

	
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.0/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.0/js/bootstrap-toggle.min.js"></script>
    
	<script src="spin.js"></script>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="<?php bloginfo ('description'); ?>" />
	<meta name="author" content="" />
		
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimal-ui" />
	<meta name="mobile-web-app-capable" content="yes">
	
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="white" /> 
	<link rel="apple-touch-startup-image" href="bimbler_ilogo.png">
	
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
	
	<link rel="stylesheet" media="all" href="bimbler-bs.css?v=4">

<?php 
	include_once ('dynamic_styling.php');
?>

  <script type="text/javascript">
		(function(document,navigator,standalone) {
			// prevents links from apps from opening in mobile safari
			// this javascript must be the first script in your <head>
			if ((standalone in navigator) && navigator[standalone]) {
				var curnode, location=document.location, stop=/^(a|html)$/i;
				document.addEventListener('click', function(e) {
					curnode=e.target;
					while (!(stop).test(curnode.nodeName)) {
						curnode=curnode.parentNode;
					}
					// Conditions to do this only on links to your own app
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
	
<?php 
	include_once ('events.php');
	
	$this_post_object = get_post($_GET['event']);
	
	$title_style = '//float: left;
				height: 50px;
				padding: 15px 15px;
				font-size: 18px;
				line-height: 20px;
				float:none; 
				color: #777;
				//border: solid 2px blue; 
				display:block; 
				white-space:nowrap; 
				overflow: hidden; 
				text-overflow: ellipsis;
			';
?>
</head> 
<body> 
 
	<!-- Index page. -->
	<div id="bimbler-spinner-target" data-role="page" data-theme="a" class="jqm-demos jqm-home"> 
	
<script type="text/javascript">
		var target = document.getElementById("bimbler-spinner-target");
		
		window.spinner = new Spinner().spin(target);
</script>
	
		<div> 
			<nav class="navbar navbar-default navbar-fixed-top">
				<div class="xcontainer-fluid"> <!-- Added -->
					<div class="navbar-header">
						<!--  <a href="javascript:history.back()"><button type="button" class="navbar-toggle ppnavbar-left"><span class="glyphicon glyphicon-chevron-left"></span></button></a>-->
  					
<?php
        if (isset ($_GET['past'])) {
        	$href="/m/?past=1";
        } else if (isset ($_GET['newest'])) {
			$href="/m/?newest=1";
		} else {
        	$href="/m/";
        }
?>
  					
						<a href="<?php echo $href;  ?>"><button type="button" class="navbar-toggle ppnavbar-left" style="margin-right: 0px;"><i class="fa fa-chevron-left"></i></button></a>
						<a class="xnavbar-brand"></a><span style="<?php echo $title_style; ?>"><?php echo $this_post_object->post_title; //bloginfo ('description'); ?></span> 
				</div>
			    
<?php 
	bimbler_mobile_render_event_tab_bar ($_GET['event'], isset ($_GET['past']) ? false : true);
?>
					
				</div> <!-- /Container fluid -->
			</nav> 
		</div>

		<!-- Content.  -->
		<div data-role="content" id="spinner-target">      
			<?php bimbler_mobile_render_event_page ($_GET['event'], isset ($_GET['past']) ? false : true); ?>
		</div>
		<!-- /Content -->
		
	</div> 

	<!-- /Index page. -->
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    
	<!-- Swiper -->
	<script src="swiper/dist/js/swiper.js"></script>    
	
	<script src="ps.js"></script> 
    
	<script src="bimbler-bs.js?v=4"></script> 
	    
	<script src="bimbler-locator.js?v=4"></script> 
	
	
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