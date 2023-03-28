<?php
	/* NEEDS TO BE UPDATED FOR SONGS; THIS IS JUST A COPY OF PROCESS_MYACCOUNT.PHP */

	// load up global things
	include_once 'autoloader.php';

	//get user id from uri
	$id = trim( $_GET['id'] );
	echo $id." here"; 


	// get toggleban or togglerand command
	$cmd = trim( $_GET['cmd'] );
	echo $cmd." here"; 


	// get current states of banned
	$state = trim( $_GET['state'] );
	echo $state." here"; 

	
	wh_log("All of the vars. id = " . $id . " and cmd = " . $cmd . " and state = " . $state . " .");
	
	// obtain fvalue for update based on user selection
	if ( $cmd == "toggleban" && $state == 1) {
		$fvalue = "0";
	} elseif ( $cmd == "toggleban" && $state == 0) {
		$fvalue = "1";
	} elseif ( $cmd == "togglerand" && $state == 2) {
		$fvalue = "0";
	} elseif ( $cmd == "togglerand" && $state == 0) {
		$fvalue = "2";			
	} else {
		$fvalue = "error";
	}
	
	wh_log("fvalue is " . $fvalue . " .");

	// validation checks
	if ( $fvalue == "error" ) {
		header( 'location: mysongs.php?message=Update%20Failed' ); // redirect to myviewers.php
	} else {
		// update viewer's banned or whitelisted field in database
		$check = updateSMRRow( 'xancara', SMR_PREFIX . 'songs', 'banned', $fvalue, $id );
		if($check) {
			header( 'location: mysongs.php?message=Update%20Successful' ); // redirect to myviewers.php
		} else {
			header( 'location: mysongs.php?message=No%20idea' );
		}
	}

	/*
	echo json_encode( // return json for ajax on front end
		array(
			'status' => $status,
			'message' => $message
		)
	); */