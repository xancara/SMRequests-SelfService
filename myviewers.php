<?php
/*
* Module Name: 	Myviewers.php
* Date: 		02/01/2023
* Author:		Sean Dixon
* Purpose:		Enables user to view and interact with their viewer list.
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

	// Deal with Paging
	if(!isset($_GET['page'])) {
		$page = 1;
	} else {
		$page = $_GET['page'];
	}
	
	// Determine offset and limit
	$limit = 50;
	$offset = ($page-1) * $limit;
	
	$total_pages = ceil(getSMRcountOnTable( 'xancara', SMR_PREFIX . 'requestors')/$limit);
	//wh_log('Total Pages is ' . json_encode($total_pages));

	// Get data from user DB for the viewers list
	$viewers = getSMRdataWithLimit( 'xancara', SMR_PREFIX . 'requestors', $limit, $offset );
	//wh_log('Viewers data array is ' . json_encode($viewers));
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- title of our page -->
		<title>SMRequests Development | My Viewers</title>

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

				$( '#update_button' ).on( 'click', function() { // onclick for our update button
					processMyViewers();
				} );

				$( '.form-input' ).keyup( function( e ) {
					if ( e.keyCode == 13 ) { // our enter key
						processMyViewers();
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

			function processMyViewers() {
				// clear error message
				$( '#error_message' ).html( '' );

				loader.showLoader();

				$.ajax( {
					url: 'php/process_myviewers.php',
					data: $( '#myviewers_form' ).serialize(),
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
		<div class="site-header">
			<div class="site-header-pad">
				<a class="header-home-link" href="index.php">
					SMRequests.Dev
				</a>
			</div>
		</div>
		<div class="site-content-container" style="display:none;">
			<div class="site-content-centered">
				<div class="site-content-section">
					<div class="site-content-section-inner">
						<div class="section-heading">My Viewers</div>
						<form id="myviewers_form" name="myviewers_form">
						<?php /* UPDATE SETTINGS FORM! THIS IS STILL A TEMPLATE OF THE MY ACCOUNT PAGE */ ?>
							<div id="error_message" class="error-message">
							</div>
							<div>
								<div class="section-label">Email</div>
								<div><input class="form-input" type="text" name="email" value="<?php echo $_SESSION['user_info']['email']; ?>" /></div>
							</div>
							<div class="section-mid-container">
								<div class="section-label">First Name</div>
								<div><input class="form-input" type="text" name="first_name" value="<?php echo $_SESSION['user_info']['first_name']; ?>" /></div>
							</div>
							<div class="section-mid-container">
								<div class="section-label">Last Name</div>
								<div><input class="form-input" type="text" name="last_name" value="<?php echo $_SESSION['user_info']['last_name']; ?>"/></div>
							</div>
							<div>
								<div class="section-label">
									<input type="checkbox" name="change_password" id="change_password" style="width:10px"/>
									<label for="change_password">Change Passowrd</label>
								</div>
							</div>
							<div id="change_password_section" style="display:none">
								<div class="section-mid-container">
									<div class="section-label">Password</div>
									<div><input class="form-input" type="password" name="password" /></div>
								</div>
								<div class="section-mid-container">
									<div class="section-label">Confirm Password</div>
									<div><input class="form-input" type="password" name="confirm_password" /></div>
								</div>
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
		<div class="site-content-container">
			<div class="site-content-centered">
				<div class="site-content-section">
					<div class="site-content-section-inner">
					<?php echo trim( $_GET['message'] ); ?>
					<div class="section-heading">Viewer List</div>
						<!-- - Sean Dixon - form to take in user input to indicate banned or whitelisted status -->
						<form id="myviewers_form" name="myviewers_form">
							<table>
								<tr>
									<th>id</th>
									<th>twitchid</th>
									<th>name</th>
									<th>dateadded</th>
									<th>banned</th>
									<th>whitelisted</th>
									<th>Status</th>
									<th>Update Status</th>
								</tr>
								<?php foreach( $viewers as $viewer ) : ?>
									<tr>
										<td><?php echo $viewer['id']; ?></td>
										<td><?php echo $viewer['twitchid']; ?></td>
										<td><?php echo $viewer['name']; ?></td>
										<td><?php echo $viewer['dateadded']; ?></td>
										<td><?php echo $viewer['banned']; ?></td>
										<td><?php echo $viewer['whitelisted']; ?></td>
									<?php if( $viewer['whitelisted'] === 'true' ) { ?>
										<td>White Listed</td>
									<?php } elseif( $viewer['banned'] === 'true') { ?>
										<td>Banned</td>
									<?php } else { ?>
										<td>default</td>	
									<?php	}	?>
										<td>
											<?php
											echo "<a href=\"php/process_myviewers.php?cmd=toggleban&id=".$viewer['id']."&st=".$viewer['banned']."\">Ban</a>";
											echo "&nbsp; &nbsp;";
											echo "<a href=\"php/process_myviewers.php?cmd=togglewhitelist&id=".$viewer['id']."&st=".$viewer['whitelisted']."\">Whitelist</a>";
											?>
										</td>
									</tr>
								<?php endforeach; ?>
							</table>
						</form>
						<div id="pagination">
							<?php for($page=1; $page <= $total_pages ; $page++) :?>

								<a href='<?php echo "?page=$page"; ?>' class="links"><?php  echo $page; ?>
								 </a>

							<?php endfor; ?>
						</div>
						<div class="section-action-container">
							<div class="section-button-container" id="update_button">
								<div>Update</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br />
		<br />
		<br />
		<?php include('footer.php'); ?>
	</body>
</html>