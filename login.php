<!DOCTYPE html> 
<html> 
  <head> 
<?php 
	require('../wp-blog-header.php');
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

<?php 
	include_once ('events.php');
?>
       
<style type="text/css">
       /* Fix-up navbar overlapping content. */
body {
	padding-top: 50px; 
	padding-bottom: 20px; 
}
</style>

<?php 
	include_once ('dynamic_styling.php');
?>


</head> 
<body> 

 
	<!-- Login page. -->
	<div id="home" data-role="page" data-theme="a" class="jqm-demos jqm-home"> 
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<h4 style="text-shadow: none;"><?php bloginfo ('description'); ?></h4> 
			</div>
		</nav>
	
		<!-- Content.  -->
		<div data-role="content">
		
		<div class="container-fluid">      

		<h4>Please log in:</h4>
			<form method="post" role="form" action="<?php bloginfo('url') ?>/wp-login.php" class="wp-user-form">
			
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon">
							<!-- <span class="glyphicon glyphicon-user" aria-hidden="true"></span> -->
							<i class="fa fa-user"></i>
						</div>
						<input type="text" class="form-control" name="log" value="<?php echo esc_attr(stripslashes($user_login)); ?>" size="20" id="user_login" tabindex="11" placeholder="Username" autocomplete="off" />
					</div>
				</div>
				
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</div>
						<input type="password" class="form-control" name="pwd" value="" size="20" id="user_pass" tabindex="12" placeholder="Password" autocomplete="off" />
					</div>
				</div>
			
				<div class="form-group">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="rememberme" value="forever" checked="checked" id="rememberme" tabindex="13" /> Remember me
						</label>
					</div>				
				</div>

				<?php do_action('login_form'); ?>
				<input type="submit" name="user-submit" value="<?php _e('Login'); ?>" tabindex="14" class="user-submit" />
				<input type="hidden" name="redirect_to" value="/m/<?php //echo $_SERVER['REQUEST_URI']; ?>" />
				<input type="hidden" name="user-cookie" value="1" />
				
			</form>		

		</div>
			
		<!-- /Content -->
		
		</div> <!-- /container-fluid -->      


		<nav class="navbar navbar-default navbar-fixed-bottom">
			<div class="container">
					<h4>&copy; 2015 <?php bloginfo ('description'); ?></h4>
			</div>
		</nav>
				
	</div> 
	<!-- /Login page. -->
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
	
</body> 
</html>