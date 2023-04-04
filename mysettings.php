<?php
/*
* Module Name: 	Mysettings.php
* Date: 		2/23/2023
* Author:		J. Sayre and S. Dixon		
* Purpose:		Enables user to view and interact with their request system settings.
				This form is very similar to setupsmr.php, except it pulls in a user's existing
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

	//var_dump( $_SESSION['user_info']['id'] ); // checking id from session because you will use to get user details from userdetails table

	if ( !isset( $_SESSION['user_details'] )) {
		$userDetails = getRowWithValue( 'userdetails', 'userId', $_SESSION['user_info']['id'] ); // get user details where id matches session id
		$_SESSION['user_details'] = $userDetails; // save info to php session 
	}

	//var_dump( $_SESSION['user_details'] );

?>
<!DOCTYPE html>
<html>
	<head>
		<!-- title of our page -->
		<title>SMRequests Self-Service | My Settings</title>

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

				$( '#update_button' ).on( 'click', function() { // onclick for our update button
					processMySettings();
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

				$( '.form-input' ).keyup( function( e ) {
					if ( e.keyCode == 13 ) { // our enter key
						processMySettings();
					}
				} );

				$( '.a-fb' ).on( 'click', function() { // on click for logout
					loader.showLoader();

					$.ajax( { 
						url: 'php/process_logout.php',
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

			function processMySettings() {
				// clear error message
				$( '#error_message' ).html( '' );

				loader.showLoader();

				$.ajax( {
					url: 'php/process_mysettings.php',
					data: $( '#mysettings_form' ).serialize(),
					type: 'post',
					dataType: 'json',
					success: function( data ) {
						if ( 'ok' == data.status ) {
							window.location.reload();
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
						<div class="section-heading">My Settings</div>
						<form id="mysettings_form" name="mysettings_form" action="php/process_mysettings.php" method="post">
							<div id="error_message" class="error-message"></div>
							<div>
								<div class="section-label" title="">Twitch Channel Name</div>
								<div><input class="form-input" type="text" name="twitch_channel" value="<?php echo $_SESSION['user_details']['twitchChannel']; ?>" required/></div>
							</div>
							<div>
								<div class="section-label" title="">Stepmania Profile Name</div>
								<div><input class="form-input" type="text" name="sm_profile" value="<?php echo $_SESSION['user_details']['smProfile']; ?>"  required/></div>
							</div>
							<div class="section-mid-container">
								<div class="section-label">Chatbot</div>
								<div><select class="form-input" form="mysettings_form" name="chatbot">
										<option <?php if($_SESSION['user_details']['chatbot'] == "StreamElements") echo "selected" ?> value="StreamElements">StreamElements</option>
										<option <?php if($_SESSION['user_details']['chatbot'] == "NightBot") echo "selected" ?> value="NightBot">NightBot</option>
										<option <?php if($_SESSION['user_details']['chatbot'] == "Lumia") echo "selected" ?> value="Lumia">Lumia</option>
										<option <?php if($_SESSION['user_details']['chatbot'] == "Other") echo "selected" ?> value="Other">Other</option>
									</select>
								</div>
							</div>
							<div>
								<div class="section-label" title="The system validates requests coming from your chat-bot. Your chat bot will have to include it to pass validation.">Security Key (Hover for Info)</div>
								<div><input class="form-input" type="text" name="security_key" value="<?php echo $_SESSION['user_details']['securityKey']; ?>"  required/></div>
							</div>
							<div class="section-mid-container">
								<div class="section-label">Maximum Requests (no more than 10)</div>
								<div><input class="form-input" type="text" name="maxRequests" value="<?php echo $_SESSION['user_details']['maxRequests']; ?>"  required/></div>
							</div>
							<div class="section-mid-container">
								<div class="section-label" title="Determines how long a chatter needs to wait before requesitng again. (# of current requests * this value) = minutes between cooldowns.">Cooldown Interval (Hover for Info)</div>
								<div><input class="form-input" type="text" name="cooldownMultiplier" value="<?php echo $_SESSION['user_details']['cooldownMultiplier']; ?>"  required/></div>
							</div>
							<div class="section-mid-container">
								<div class="section-label">Scoring Type</div>
								<div><select class="form-input" form="mysettings_form" name="scoreType">
										<option <?php if($_SESSION['user_details']['scoreType'] == "ITG") echo "selected" ?> value="ITG">ITG</option>
										<option <?php if($_SESSION['user_details']['scoreType'] == "DDR") echo "selected" ?> value="DDR">DDR</option>
									</select></div>
							</div>
							<div class="section-mid-container">
								<div class="section-label" title="Your system will use this to determine how to pull random queries. 0.1 means the top 10% of your played songs will be included.">Top Percent (Hover for Info)</div>
								<div><input class="form-input" type="text" name="topPercent" value="<?php echo $_SESSION['user_details']['topPercent']; ?>"  required/></div>
							</div>
						</form>
						<div class="section-action-container">
							<div class="section-button-container" id="update_button">
								<div>Update</div>
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