<?php

	// load up global things
	include_once '../autoloader.php';

	// get viewer info of smrUser from sm_requestors table using id field
	$viewerInfo = getSMRRowWithValue( 'xancara', SMR_PREFIX . 'requestors', 'id', trim( $_GET['id'] ) );
	//var_dump($viewerInfo);

	// get toggleban or togglewhitelist command from url
	$cmd = trim( $_GET['cmd'] );
	//var_dump($cmd);

	if ($cmd == 'toggleban') {
		if ( $viewerInfo['whitelisted'] === 'true') { // prevent ban of a whitelisted viewer
			$status = 'fail';
			$message = 'Cannot ban a whitelisted viewer';
		} elseif ( $viewerInfo['banned'] === 'true' ) { // if viewer is banned, unban them
			updateSMRRow( 'xancara', SMR_PREFIX . 'requestors', 'banned', false, $viewerInfo['id']);
			$status = 'ok';
			$message = 'valid';
			$viewerInfo = getSMRRowWithValue( 'xancara', SMR_PREFIX . 'requestors', 'id', trim( $_GET['id'] ) );
		} elseif ( $viewerInfo['banned'] === 'false' ) {
			updateSMRRow( 'xancara', SMR_PREFIX . 'requestors', 'banned', true, $viewerInfo['id']);
			$status = 'ok';
			$message = 'valid';
			$viewerInfo = getSMRRowWithValue( 'xancara', SMR_PREFIX . 'requestors', 'id', trim( $_GET['id'] ) );
		} else {
			$status = 'fail';
			$message = 'Unknown error';
		}
		//var_dump($viewerInfo);
	}

	elseif ($cmd == 'togglewhitelist') {
		if ( $viewerInfo['banned'] === 'true') { // prevent whitelist of a banned viewer
			$status = 'fail';
			$message = 'Cannot whitelist a banned viewer';
		} elseif ( $viewerInfo['whitelisted'] === 'true' ) { // if viewer is whitelisted, unwhitelist them
			updateSMRRow( 'xancara', SMR_PREFIX . 'requestors', 'whitelisted', false, $viewerInfo['id']);
			$status = 'ok';
			$message = 'valid';
			$viewerInfo = getSMRRowWithValue( 'xancara', SMR_PREFIX . 'requestors', 'id', trim( $_GET['id'] ) );
		} elseif ( $viewerInfo['whitelisted'] === 'false' ) {
			updateSMRRow( 'xancara', SMR_PREFIX . 'requestors', 'whitelisted', true, $viewerInfo['id']);
			$status = 'ok';
			$message = 'valid';
			$viewerInfo = getSMRRowWithValue( 'xancara', SMR_PREFIX . 'requestors', 'id', trim( $_GET['id'] ) );
		} else {
			$status = 'fail';
			$message = 'Unknown error';
		}
		//var_dump($viewerInfo);
	}

	if ($status == 'ok') {
		header( 'location: ../myviewers.php' ); // redirect to myviewers.php
	}

	echo json_encode( // return json for ajax on front end
		array(
			'status' => $status,
			'message' => $message
		)
	);