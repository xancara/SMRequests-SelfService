<?php
/*
* Module Name: 	setupsmr.php
* Date: 		2023-03-25
* Author:		J. Sayre
* Purpose:		Enables user to provide any necessary info to complete SMR setup. This page has similar content to mysettings.php, but is intended for initial use.
*/

	// Load global resources and establish a session
	include_once 'autoloader.php';

	if ( !isLoggedIn() ) { // Redirect users that aren't logged-in
		header( 'location: index.php' );
	}

	if ( !empty( $_SESSION['user_info']['fb_access_token'] ) ) { // get users facebook info is we have an access token
		$fbUserInfo = getFacebookUserInfo( $_SESSION['user_info']['fb_access_token'] );
		$fbDebugTokenInfo = getDebugAccessTokenInfo( $_SESSION['user_info']['fb_access_token'] );
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- title of our page -->
		<title>SMRequests Self-Service | Setup SMR</title>

		<!-- include fonts -->
		<link href="https://fonts.googleapis.com/css?family=Coda" rel="stylesheet">

		<!-- mobile layout support -->
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

		<!-- css styles for our my account page-->
		<link href="css/global.css" rel="stylesheet" type="text/css">
		<link href="css/myaccount.css" rel="stylesheet" type="text/css">

		<!-- jquery -->
		<script type="text/javascript" src="js/jquery.js"></script>

		<!-- include our loader overlay script -->
		<script type="text/javascript" src="js/loader.js"></script>

		<script>
			$( function() { // once the document is ready, do things
				// initialize our loader overlay
				loader.initialize();

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

				$( '#setup_button' ).on( 'click', function() { // onclick for our finish setup button
					processSMRSetup();
				} );

				$( '.form-input' ).keyup( function( e ) {
					if ( e.keyCode == 13 ) { // our enter key
						processSMRSetup();
					}
				} );

				$( '.a-fb' ).on( 'click', function() { // on click for logout
					loader.showLoader();

					$.ajax( { 
						url: 'php/process_setupsmr.php',
						type: 'post',
						dataType: 'json',
						success: function( data ) {
							loader.hideLoader();
							window.location.href = 'index.php';
						}
					} );
				} );

				$( '.show-hide' ).on( 'click', function() { // on click for show hide section
					// get section we are showing/hiding
					var showHideSection = $( this ).data( 'section' );

					if ( $( '#' + showHideSection ).is( ':visible' ) ) { // section is currently visible
						// change text to show
						$( this ).html( 'show' );

						// hide section
						$( '#' + showHideSection ).hide();
					} else { // section is currently hidden
						// changet text to hide
						$( this ).html( 'hide' );

						// show section
						$( '#' + showHideSection ).show();
					}
				} );
			} );

			function processSMRSetup() {
				// clear error message
				$( '#error_message' ).html( '' );

				loader.showLoader();

				$.ajax( {
					url: 'php/process_setupsmr.php',
					data: $( '#setupsmr_form' ).serialize(),
					type: 'post',
					dataType: 'json',
					success: function( data ) {
						if ( 'ok' == data.status ) {
							window.location.href = 'index.php'; //redirect to logged-in home
						} else if ( 'fail' == data.status ) {
							$( '#error_message' ).html( data.message );
							loader.hideLoader();
						}
					}
				} );
			}
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
						<div class="section-heading">Complete SMR Setup</div>
						<div>Almost there! We need a few more things to initially configure your request system.</div>
						<div>Upon successful completion and save of this form, you will be redirected.</div>
						<br>
						<form id="setupsmr_form" name="setupsmr_form">
						<div class="section-mid-container">
						<div id="error_message" class="error-message"></div>
							<div>
								<div class="section-label" title="">Twitch Channel Name</div>
								<div><input class="form-input" type="text" name="twitch_channel" value="" required/></div>
							</div>
						</div>
						<div class="section-mid-container">
							<div>
								<div class="section-label" title="">Stepmania Profile Name</div>
								<div><input class="form-input" type="text" name="sm_profile" value="" required/></div>
							</div>
						</div>
						<div class="section-mid-container">
							<div class="section-label">Chatbot</div>
							<div><select class="form-input" form="setupsmr_form" name="chatbot">
									<option value="StreamElements">StreamElements</option>
									<option value="NightBot">NightBot</option>
									<option value="Lumia">Lumia</option>
									<option value="Other">Other</option>
								</select></div>
						</div>
							<div class="section-mid-container">
							<div>
								<div class="section-label" title="The system validates requests coming from your chat-bot. Your chat bot will have to include it to pass validation.">Security Key (Hover for Info)</div>
								<div><input class="form-input" type="text" name="security_key" value="" required/></div>
							</div>
						</div>
						<div class="section-mid-container">
							<div class="section-label">Maximum Requests (no more than 10)</div>
							<div><input class="form-input" type="text" name="maxRequests" value="10" required/></div>
						</div>
						<div class="section-mid-container">
							<div class="section-label" title="Determines how long a chatter needs to wait before requesitng again. (# of current requests * this value) = minutes between cooldowns.">Cooldown Interval (Hover for Info)</div>
							<div><input class="form-input" type="text" name="cooldownMultiplier" value="0.5" required/></div>
						</div>
						<div class="section-mid-container">
							<div class="section-label">Scoring Type</div>
							<div><select class="form-input" form="setupsmr_form" name="scoreType">
									<option value="ITG">ITG</option>
									<option value="DDR">DDR</option>
								</select></div>
						</div>
						<div class="section-mid-container">
							<div class="section-label" title="Your system will use this to determine how to pull random queries. 0.1 means the top 10% of your played songs will be included.">Top Percent (Hover for Info)</div>
							<div><input class="form-input" type="text" name="topPercent" value="0.1" required/></div>
							</div>
						</form>
						<div class="section-action-container">
							<div class="section-button-container" id="setup_button">
								<div>Finish Setup</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="site-content-container" style="display:none;">
			<div class="site-content-centered">
				<div class="site-content-section">
					<div class="site-content-section-inner">
					</div>
				</div>
			</div>
		</div>
		<br />
		<br />
		<br />
		<?php //include('footer.php'); ?>
	</body>
</html>