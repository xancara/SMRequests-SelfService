<?php

// load up global things
include_once '../autoloader.php';

$userDetails = getRowWithValue( 'userdetails', 'id', $_SESSION['user_details']['id'] ); //get user details to compare with values posted

$id = $_SESSION['user_details']['id']; //get id

$twitchChannel = $_POST['twitch_channel']; //get twitch channel name from post
if ( $twitchChannel != $userDetails['twitchChannel'] ) {  //if twitch channel from post is not equal to value in database
	updateRow( 'userDetails', 'twitchChannel', $twitchChannel, $id);   //update that row with new value from post
}

// Repeat same steps for each of the updatable fields

$smProfile = $_POST['sm_profile'];
if ( $smProfile != $userDetails['smProfile'] ) {
	updateRow( 'userDetails', 'smProfile', $smProfile, $id );
}

$chatbot = $_POST['chatbot'];
if ( $chatbot != $userDetails['chatbot'] ) {
	updateRow( 'userDetails', 'chatbot', $chatbot, $id );
}

$securityKey = $_POST['security_key'];
if ( $securityKey != $userDetails['securityKey'] ) {
	updateRow( 'userDetails', 'securityKey', $securityKey, $id );
}

$maxRequests = $_POST['maxRequests'];
if ( $maxRequests != $userDetails['maxRequests'] ) {
	updateRow( 'userDetails', 'maxRequests', $maxRequests, $id );
}

$cooldownMultiplier = $_POST['cooldownMultiplier'];
if ( $cooldownMultiplier != $userDetails['cooldownMultiplier'] ) {
	updateRow( 'userDetails', 'cooldownMultiplier', $cooldownMultiplier, $id );
}

$scoreType = $_POST['scoreType'];
if ( $scoreType != $userDetails['scoreType'] ) {
	updateRow( 'userDetails', 'scoreType', $scoreType, $id );
}

$topPercent = $_POST['topPercent'];
if ( $topPercent != $userDetails['topPercent'] ) {
	updateRow( 'userDetails', 'topPercent', $topPercent, $id );
}

// get user details so we have most recent info
$userDetails = getRowWithValue( 'userdetails', 'id', $_SESSION['user_details']['id'] );

//update session with updated user details
$_SESSION['user_details'] = $userDetails;

// redirect to mysettings.php
header( 'location: ../mysettings.php' );


/*
	echo json_encode( // return json for ajax on front end
		array(
			'status' => $status,
			'message' => $message
		)
	);
*/