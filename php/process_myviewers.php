<?php

	// load up global things
	include_once '../autoloader.php';

	//get user id from uri
	$id = trim( $_GET['id'] );


	// get toggleban or togglewhitelist command
	$cmd = trim( $_GET['cmd'] );


	// get current states of viewer's banned or whitelisted fields
	$st = trim( $_GET['st'] );


	// if current state is true, set value to false, else set value to true
	if ( $st == "true" ) {
		$value = "false";
	} elseif ( $st == "false" ) {
		$value = "true";
	} else {
		$value = "error";
	}

	// obtain field for update based on user selection
	if ( $cmd == "toggleban" ) {
		$field = "banned";
	} elseif ( $cmd == "togglewhitelist" ) {
		$field = "whitelisted";
	} else {
		$field = "error";
	}


	// validation checks
	if ( $field == "error" || $value == "error" ) {
		header( 'location: ../myviewers.php?message=Update%20Failed' ); // redirect to myviewers.php
	} else {
		// update viewer's banned or whitelisted field in database
		updateSMRRow( 'xancara', SMR_PREFIX . 'requestors', $field, $value, $id );
		header( 'location: ../myviewers.php?message=Update%20Successful' ); // redirect to myviewers.php
	}

/**
	if ($status == 'ok') {
		header( 'location: ../myviewers.php' ); // redirect to myviewers.php
	}
*/
/*
	echo json_encode( // return json for ajax on front end
		array(
			'status' => $status,
			'message' => $message
		)
	);
*/