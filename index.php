<?php
/*
* Module Name: 	Index.php
* Date: 		2023-02-04
* Author:		J. Sayre
*				Adapted from Easy, Code Is by Jstolpe Repository: https://github.com/jstolpe/easycodeis per request from maintainers.
* Purpose:		Post-authentication landing page and navigation.
*/

	// Load global resources and establish a session
	include_once 'autoloader.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- title of our page -->
		<title>SMRequests Development | WIP</title>

		<!-- include fonts -->
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
		<!--Low-Priority Request from Maintainers: Replace Video with some other asset-->
		<div class="background-video-container">
			<video class="background-video-element" autoplay muted loop >
				<source src="assets/background_video.mp4" />
			</video>
			<img class="background-video-image" src="assets/background_video_image.png" />
			<div class="background-video-overlay"></div>
			<div class="background-video-text-overlay">
				<div>SMRequests Development | Work In Progress</div>
				<!--Desktop Client Experience-->
				<div class="action-container pc-only">
					<?php if ( isLoggedIn() ) : ?> <!--Check for logged-in user and greet-->
						<div class="logged-in-text">Logged in as <b><?php echo $_SESSION['user_info']['first_name']; ?></b></div>
					<?php else : ?> <!--Display buttons-->
						<a class="a-action" href="signup.php">
							<div class="button-container">
								<div class="button-container-pad">
									SIGN UP
								</div>
							</div>
						</a>
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
					<!--Mobile Experience-->
					<div class="action-container mobile-only">
						<?php if ( isLoggedIn() ) : ?> <!--Check for logged-in user and greet-->
							<div class="logged-in-text">Logged in as <b><?php echo $_SESSION['user_info']['first_name']; ?></b></div>
						<?php else : ?> <!--Display buttons-->
							<a class="a-action" href="signup.php">
								<div class="button-container">
									<div class="button-container-pad">
										SIGN UP
									</div>
								</div>
							</a>
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
						This is a skeleton of the main site that is based off of the open source code listed in the github link below. <br/>It has been modified to reflect all necessary functions for SMRequests. Development is just stating on this so hang in there with us while we work through things.
					</div>
				</div>
			</div>
		</div>
		<?php include('footer.php'); ?>
	</body>
</html>