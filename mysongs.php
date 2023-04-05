<?php
/*
* Module Name: 	Mysongs.php
* Date: 		2023-03-30
* Author:		M. Seibel & J. Sayre
* Purpose:		Enables user to view and interact with their song library.
*/

// Load global resources and establish a session
include_once 'autoloader.php';

if (!isLoggedIn()) { // Redirect users that aren't logged-in
	header('location: index.php');
}

if (!empty($_SESSION['user_info']['fb_access_token'])) { // get users facebook info is we have an access token
	$fbUserInfo = getFacebookUserInfo($_SESSION['user_info']['fb_access_token']);
	$fbDebugTokenInfo = getDebugAccessTokenInfo($_SESSION['user_info']['fb_access_token']);
}

// Deal with Paging
if (!isset($_GET['page'])) {
	$page = 1;
} else {
	$page = $_GET['page'];
}

// Determine offset and limit
$limit = 50;
$offset = ($page - 1) * $limit;

$total_pages = ceil(getSMRcountOnTable('xancara', SMR_PREFIX . 'songs') / $limit);
//wh_log('Total Pages is ' . json_encode($total_pages));

// Get additional Query Data from URL String

// Get data from user DB for the songlist list
if (isset($_GET['ShowBanned'])) {
	//Retrieve only banned songs
	if (isset($_GET['query'])) {
		$query = $_GET['query'];
		$songlist = getSMRBannedSongsWithLimit('xancara', $query, $limit, $offset);
	} else {
		$songlist = getSMRBannedSongsWithLimit('xancara', false, $limit, $offset);
	}
} else {
	//Retrieve only banned songs
	if (isset($_GET['query'])) {
		$query = $_GET['query'];
		$songlist = getSMRSongsWithLimit('xancara', $query, $limit, $offset);
	} else {
		$songlist = getSMRSongsWithLimit('xancara', false, $limit, $offset);
	}
}
//wh_log('Songlist data array is ' . json_encode($songlist));
?>
<!DOCTYPE html>
<html>

<head>
	<!-- title of our page -->
	<title>SMRequests Self-Service | My Songs</title>

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

	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="css/w3-theme-dark-grey.css">
	<link rel="icon" type="image/png" href="images/ddr_arrow.png">
	<meta name="robots" content="noindex,nofollow">

	<script>
		$(function() { // once the document is ready, do things
			// initialize our loader overlay
			loader.initialize();

			$('#logout_link').on('click', function() { // on click for our logout link
				// show our loading overlay
				loader.showLoader();

				// server side logout
				$.ajax({
					url: 'php/process_logout.php',
					type: 'post',
					dataType: 'json',
					success: function(data) {
						loader.hideLoader();
						window.location.href = "index.php";
					}
				});
			});

			$('#update_button').on('click', function() { // onclick for our update button
				processMySongs();
			});

			$('#search').on('click', function() { // onclick for our update button
				processMySongSearch();
			});

			$('.form-input').keyup(function(e) {
				if (e.keyCode == 13) { // our enter key
					processMySongs();
				}
			});

			$('.a-fb').on('click', function() { // on click for logout
				loader.showLoader();

				$.ajax({
					url: 'php/process_logout.php',
					type: 'post',
					dataType: 'json',
					success: function(data) {
						loader.hideLoader();
						window.location.href = 'index.php';
					}
				});
			});

			$('.show-hide').on('click', function() { // on click for show hide section
				// get section we are showing/hiding
				var showHideSection = $(this).data('section');

				if ($('#' + showHideSection).is(':visible')) { // section is currently visible
					// change text to show
					$(this).html('show');

					// hide section
					$('#' + showHideSection).hide();
				} else { // section is currently hidden
					// changet text to hide
					$(this).html('hide');

					// show section
					$('#' + showHideSection).show();
				}
			});
		});

		function processMySongs() {
			// clear error message
			$('#error_message').html('');

			loader.showLoader();

			$.ajax({
				url: 'php/process_mysongs.php',
				data: $('#mysongs_form').serialize(),
				type: 'post',
				dataType: 'json',
				success: function(data) {
					if ('ok' == data.status) {
						window.location.reload();
					} else if ('fail' == data.status) {
						$('#error_message').html(data.message);
						loader.hideLoader();
					}
				}
			});
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
	<div>
		<div class="center" style="width:40%;margin-inline-start:35rem;">

			
				<h2>Search Songs by: <?php echo isset($_GET['query']) ? $_GET['query'] : ""; ?></h2>
				<div class="section-mid-container">
					<form method="GET">
					<label>Song ID:</label>
						<input type="TEXT" name="query"	value="" placeholder="Enter ID" autofocus="AutoFocus" >
							<div class="section-action-container">
			   			<input type="SUBMIT" value="Search" style="width:25%" class="w3-btn w3-border"/>
					</div>
					</form>
				</div>
				<div class="section-mid-container">
					<form method="GET">
					<label>Song Title:</label>
						<input type="TEXT" name="query" value="" placeholder="Input a song title or artist"/>
							<div class="section-action-container">
			   			<input type="SUBMIT" value="Search" style="width:25%"  class="w3-btn w3-border"/>
					</div>
					</form>
				</div>
				<div class="section-mid-container">
					<form method="GET">
					<label>Pack:</label><br>
					<?php $packlist = getSMRPacks('xancara'); ?>
						<select name="query" class="form-input" id="pack" style="width:100%" >
							<option value="none" selected disabled>Select a pack...</option>
							<?php
    							foreach ($packlist as $pack) {
        							echo '<option value="' . $pack['pack'] . '">' . $pack['pack'] . '</option>';
    							}
   							 ?>
						</select>
						<div class="section-action-container">
			   			<input type="SUBMIT" value="Search" style="width:25%"  class="w3-btn w3-border"/>
					</div>
						</form>
				</div>
			
			  			<a type="reset" href="mysongs.php" style="align-center">Reset</a>
			  			<a type="button" href="mysongs.php?ShowBanned" style="align-center">View Banned Songs</a> </div></div>
			</form>
	</div></div>

	<div class="site-content-container">
		<div class="site-content-centered">
			<div class="site-content-section">
				<div class="site-content-section-inner">
					<div class="section-heading">My Songs</div>
					<div>
						<div class="section-label">Song List</div>
						<!-- Let's throw the message display box here-->
						<div class="message">
							<?php if (isset($_GET['message'])) : ?>
								<p><?php echo urldecode($_GET['message']); ?></p>
							<?php endif; ?>
						</div>
					</div>
					<div class="section-mid-container">
						<!-- Add Button that Toggles between "Full List" and "Banned Only" -->
						<?php
						if (!isset($_GET['ShowBanned'])) {
							echo '<a href="mysongs.php?ShowBanned">Show Banned Only</a>';
						} else {
							echo '<a href="mysongs.php">Show All Songs</a>';
						}
						?>

						<!-- Add Search form that calls back to getSMRsongslist with query data -->

						<!-- Going to start the actual table here. Nothing fancy for the time being. -->
						<table id="tblSongs" class="display row-border stripe order-column hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Title</th>
									<th>Artist</th>
									<th>Pack</th>
									<th title="In seconds">Length</th>
									<th>BPM</th>
									<th title="0=Available; 1=Banned from Request; 2=Banned from !random-type Requests">Banned?</th>
									<th title="This means this song won't be available for request">Toggle Banned</th>
									<th title="This means your audience can request it, but it won't be selected via !random-type Requests">Toggle Random</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($songlist as $song) : ?>
									<tr style="text-align:center;">
										<td><?php echo $song['id']; ?></td>
										<td><?php echo $song['title'] . " " . $song['subtitle']; ?></td>
										<td><?php echo $song['artist']; ?></td>
										<td><?php echo $song['pack']; ?></td>
										<td><?php echo $song['music_length']; ?></td>
										<td><?php echo $song['display_bpm']; ?></td>
										<?php
										if ($song['banned'] === 1) {
											//Convert Int to Current Status
											echo "<td>Request Banned</td>";
										} elseif ($song['banned'] === 2) {
											//Convert Int to Current Status
											echo "<td>Random Banned</td>";
										} else {
											//Just display it's state
											echo "<td>" . $song['banned'] . " </td>";
										}
										echo "<td>";
										echo "<a href=\"process_mysongs.php?cmd=toggleban&id=" . $song['id'] . "&state=" . $song['banned'] . "\">Request Ban</a>";
										echo "</td><td>";
										echo "<a href=\"process_mysongs.php?cmd=togglerand&id=" . $song['id'] . "&state=" . $song['banned'] . "\">Random Ban</a>";
										echo "</td>";
										?>
									</tr>
								<?php endforeach; ?>
							</tbody>
							<tfoot>
								<tr>
									<th>ID</th>
									<th>Title</th>
									<th>Artist</th>
									<th>Pack</th>
									<th title="In seconds">Length</th>
									<th>BPM</th>
									<th title="0=Available; 1=Banned from Request; 2=Banned from !random-type Requests">Banned?</th>
									<th title="This means this song won't be available for request">Toggle Banned</th>
									<th title="This means your audience can request it, but it won't be selected via !random-type Requests">Toggle Random</th>
								</tr>
							</tfoot>
						</table>
						<div id="pagination" style="text-align: center;">
							<?php for ($page = 1; $page <= $total_pages; $page++) : ?>
								<?php if (!isset($_GET['ShowBanned']) && !isset($_GET['query'])) : ?>
									<a href='<?php echo "?page=$page"; ?>' class="links"><?php echo $page; ?>
									</a>&nbsp;
								<?php elseif (!isset($_GET['ShowBanned']) && isset($_GET['query'])) : ?>
									<a href='<?php echo "?query=" . $_GET['query'] . "&page=$page"; ?>' class="links"><?php echo $page; ?>
									</a>&nbsp;
								<?php elseif (isset($_GET['ShowBanned']) && isset($_GET['query'])) : ?>
									<a href='<?php echo "?ShowBanned&query=" . $_GET['query'] . "&page=$page"; ?>' class="links"><?php echo $page; ?>
									</a>&nbsp;
								<?php else : ?>
									<a href='<?php echo "?ShowBanned&page=$page"; ?>' class="links"><?php echo $page; ?>
									</a>&nbsp;
								<?php endif; ?>
							<?php endfor; ?>
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