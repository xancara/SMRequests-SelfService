<?php
	// load up global things
	include_once 'autoloader.php';

	if ( !isAdmin() ) {
		header( 'location: index.php' );
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- title of our page -->
		<title>SMRequests Self-Service | Admin Panel</title>

		<!-- include fonts -->
		<link href="https://fonts.googleapis.com/css?family=Coda" rel="stylesheet">

		<!-- need this so everything looks good on mobile devices -->
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

		<!-- css styles for our signup page-->
		<link href="css/global.css" rel="stylesheet" type="text/css">
		<link href="css/adminpanel.css" rel="stylesheet" type="text/css">

		<!-- jquery -->
		<script type="text/javascript" src="js/jquery.js"></script>

		<!-- include our loader overlay script -->
		<script type="text/javascript" src="js/loader.js"></script>

		<script>
			$( function() { // once the document is ready, do things
				// initialize our loader overlay
				loader.initialize();
			} );
		</script>
	</head>
	<body>
	<?php include('nav.php'); ?>
		<div class="site-header">
			<div class="site-header-pad">
				<a class="header-home-link" href="index.php">
				SMRequests Self-Service
				</a>
			</div>
		</div>
		<div class="site-content-container">
			<div class="site-content-centered">
				<div class="site-content-section">
					<div class="site-content-section-inner">
						<div class="section-heading">Admin Panel</div>
						<div class="section-mid-container">This page is under construction, but the SMRequests Admin team looks forward
														   to providing a user-interface for administrative functions, including user emulation, soon.<br><br>
														   Return <a href=index.php>home</a>.</div>
						<div class="admin-sub-heading">Logged in as <?php echo $_SESSION['user_info']['first_name']; ?> <?php echo $_SESSION['user_info']['last_name']; ?></div>
						<!-- Add table with users accounts and management buttons -- Edit Account - Add/Remove Premium - Suspend Account - Force Re-auth
						
						Edit Account = Administrator view of account information so information can be corrected/changed. E-mail, first, last, user level, User SMR Settings
						Add/remove Premium = Set user_level on account to 2
						Suspend Account = Set user_level on account to -1
						Force Re-Auth = Remove twitch_access_token & twitch_refresh_token from DB for user account
						-->
					</div>
				</div>
			</div>
		</div>
	</body>
</html>