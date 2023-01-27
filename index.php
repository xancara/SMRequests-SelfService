<!--
* Module Name: 	Index.php
* Date: 		[[DATE HERE]]
* Author:		[[AUTHOR HERE]]
				Adapted from Easy, Code Is by Jstolpe Repository: https://github.com/jstolpe/easycodeis per request from maintainers.
* Purpose:		Post-authentication landing page and navigation
* Notes:		
-->
<?php
	// Creates a Session, Loads global APIs and other global resources
	include_once 'autoloader.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- page title -->
		<title>SMRequests Development | WIP</title>

		<!-- fonts -->
		<link href="https://fonts.googleapis.com/css?family=Coda" rel="stylesheet">

		<!-- mobile layout support -->
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

		<!-- css styles for our home page-->
		<link href="css/global.css" rel="stylesheet" type="text/css">
		<link href="css/home.css" rel="stylesheet" type="text/css">

		<!-- jquery -->
		<script type="text/javascript" src="js/jquery.js"></script>

		<!-- include our loader overlay script -->
		<script type="text/javascript" src="js/loader.js"></script>

		<script>
			$( function() { // do things when the document is ready
				// initialize our loader overlay
				loader.initialize();

				$( '#load_test' ).on( 'click', function() { // on click for our load test link
					// show our loading overlay
					loader.showLoader();

					setInterval( function() { // after 3 seconds, hide our loading overlay
						loader.hideLoader();
					}, 3000 );
				} );

				$( '#logout_link' ).on( 'click', function() { // on click for our logout link
					// show our loading overlay
					loader.showLoader();

					// server side logout
					$.ajax( {
						url: 'php/process_logout.php',
						type: 'post',
						dataType: 'json',
						success: function( data ) {
							loader.hideLoader();
							window.location.href = "index.php";
						}
					} );
				} );
			} );
		</script>
	</head>
	<body>
		<!-- REQUEST FROM MAINTAINERS: Remove video and replace with some other asset -->
		<div class="background-video-container">
			<video class="background-video-element" autoplay muted loop >
				<source src="assets/background_video.mp4" />
			</video>
			<img class="background-video-image" src="assets/background_video_image.png" />
			<div class="background-video-overlay"></div>
			<div class="background-video-text-overlay">

				<div>SMRequests Development | Work In Progress</div>

				<!-- Desktop Client Experience -->
				<div class="action-container pc-only">
					<?php if ( isLoggedIn() ) : // If there is a logged-in user ?>
						<div class="logged-in-text">Logged in as <b><?php echo $_SESSION['user_info']['first_name']; // Greet them by name ?></b></div>
					<?php else : //Otherwise, ?>

						<!-- Display a Sign Up Button -->
						<a class="a-action" href="signup.php">
							<div class="button-container">
								<div class="button-container-pad">
									SIGN UP
								</div>
							</div>
						</a>
						<!-- and Display a Login Button -->
						<a class="a-action" href="login.php">
							<div class="button-container">
								<div class="button-container-pad">
									LOGIN
								</div>
							</div>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="content">
			<div class="content-inner">
				<div class="content-inner-padding">

					<!-- Mobile Client Experience -->
					<div class="action-container mobile-only">
						<?php if ( isLoggedIn() ) : // If there is a logged-in user ?>
							<div class="logged-in-text">Logged in as <b><?php echo $_SESSION['user_info']['first_name']; // Greet them by name ?></b></div>
						<?php else : //Otherwise, ?>
							<!-- Display a Sign Up Button -->
							<a class="a-action" href="signup.php">
								<div class="button-container">
									<div class="button-container-pad">
										SIGN UP
									</div>
								</div>
							</a>
							<!-- and Display a Login Button -->
							<a class="a-action" href="login.php">
								<div class="button-container default-margin-top">
									<div class="button-container-pad">
										LOGIN
									</div>
								</div>
							</a>
						<?php endif; ?>
					</div>
					<h1>
						Welcome to SMRequests!
					</h1>
					<div>
						This is a skeleton template of the main site that is based off of the open source code listed in the github link below. <br/>It is inthe process of being modified to reflect all necessary functions for SMRequests. Development is just stating on this so hang in there with us while we work through things.
					</div>
				</div>
			</div>
		</div>
		<div class="footer-container">
			<div><a class="a-default" href="https://github.com/MrTwinkles47/Stepmania-Stream-Tools-MrTwinkles">View SMRequests on GitHub</a></div>
			<?php if ( isLoggedIn() ) : // If there is a logged-in user  ?>
				<?php if ( isAdmin() ) : // and if the user is an Administrator ?>
					<div>
						<!-- Display a link to the Admin Panel -->
						<a class="a-default" href="adminpanel.php">Admin Panel</a>
					</div>
				<?php endif; ?>
				<div>
					<a class="a-default" href="myaccount.php">My Account</a>
				</div>
				<div>
					<a class="a-default" href="mysettings.php">Manage Settings</a>
				</div>
				<div>
					<a class="a-default" href="myviewers.php">Manage Requestors</a>
				</div>
				<div>
					<a class="a-default" href="mysongs.php">Manage Songs</a>
				</div>
				<div>
					<a class="a-default" href="mywebhooks.php">Manage Webhooks</a>
				</div>
				<div id="logout_link" class="a-default">Logout</div>
			<?php endif; ?>
			<div><a class="a-default" href="https://github.com/jstolpe/easycodeis">View Easy, Code Is on GitHub</a></div>
		</div>
	</body>
</html>