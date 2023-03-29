<?php

// load up global things
include_once '../autoloader.php';

$userDetails = getRowWithValue( 'userdetails', 'id', $_SESSION['user_details']['id'] ); //get user details to compare with values posted

$id = $_SESSION['user_details']['id']; //get userDetails id from session

//Get form data from URL
$twitchChannel = $_POST['twitch_channel'];
$smProfile = $_POST['sm_profile'];
$chatbot = $_POST['chatbot'];
$securityKey = $_POST['security_key'];
$maxRequests = $_POST['maxRequests'];
$cooldownMultiplier = $_POST['cooldownMultiplier'];
$scoreType = $_POST['scoreType'];
$topPercent = $_POST['topPercent'];

//Validate field entries for submission.
if (empty($twitchChannel) || strlen($twitchChannel) > 25) { // ensure channel neither empty nor too long
	$status = 'fail';
	$message = 'A valid Twitch Channel was not specified.';
} elseif ( empty($smProfile) || strlen($smProfile) > 25) { // ensure StepMania profile isn't empty or too long
	$status = 'fail';
	$message = 'A valid StepMania Profile Name was not specified.';
} elseif ( empty( $securityKey) ) { // ensure Security key is not blank
	$status = 'fail';
	$message = 'You must specify a security key.';
} elseif ( empty($maxRequests) || $maxRequests < 1 || $maxRequests > 10) { // max requests validation
	$status = 'fail';
	$message = 'Please specify a valid Maximum Requests value, 1-10';
} elseif ( empty($cooldownMultiplier) || $cooldownMultiplier < 0.01 || $cooldownMultiplier > 1) { // max requests validation
	$status = 'fail';
	$message = 'Please specify a valid Cooldown Multiplier, 0.01-1';
} elseif ( empty($topPercent) || $topPercent < 0.01 || $topPercent > 1) { // max requests validation
	$status = 'fail';
	$message = 'Please specify a valid Top Percent, 0.01-1';
} else { // all passes so we are all good!
	$status = 'ok';
	$message = 'valid';
	//process updates of updated fields
	if ( $twitchChannel != $userDetails['twitchChannel'] ) {  //if twitch channel from post is not equal to value in database
		updateRow( 'userDetails', 'twitchChannel', $twitchChannel, $id);   //update that row with new value from post
	}
	if ( $smProfile != $userDetails['smProfile'] ) {
		updateRow( 'userDetails', 'smProfile', $smProfile, $id );
	}
	if ( $chatbot != $userDetails['chatbot'] ) {
		updateRow( 'userDetails', 'chatbot', $chatbot, $id );
	}
	if ( $securityKey != $userDetails['securityKey'] ) {
		updateRow( 'userDetails', 'securityKey', $securityKey, $id );
	}
	if ( $maxRequests != $userDetails['maxRequests'] ) {
		updateRow( 'userDetails', 'maxRequests', $maxRequests, $id );
	}
	if ( $cooldownMultiplier != $userDetails['cooldownMultiplier'] ) {
		updateRow( 'userDetails', 'cooldownMultiplier', $cooldownMultiplier, $id );
	}
	if ( $scoreType != $userDetails['scoreType'] ) {
		updateRow( 'userDetails', 'scoreType', $scoreType, $id );
	}
	if ( $topPercent != $userDetails['topPercent'] ) {
		updateRow( 'userDetails', 'topPercent', $topPercent, $id );
	}
}

// get user details so we have most recent info
$userDetails = getRowWithValue( 'userdetails', 'id', $_SESSION['user_details']['id'] );

//update session with updated user details
$_SESSION['user_details'] = $userDetails;


	echo json_encode( // return json for ajax on front end
		array(
			'status' => $status,
			'message' => $message
		)
	);