<?php


	// load up global things
	include_once '../autoloader.php';


	//Get data from URL
	$userId = $_SESSION['user_info']['id'];
	$twitchChannel = $_POST['twitch_channel'];
	$smProfile = $_POST['sm_profile'];
	$chatbot = $_POST['chatbot'];
	$securityKey = $_POST['security_key'];
	$maxRequests = $_POST['maxRequests'];
	$cooldownMultiplier = $_POST['cooldownMultiplier'];
	$scoreType = $_POST['scoreType'];
	$topPercent = $_POST['topPercent'];

	//Submit user details from the form to the userDetails table
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

        // sign the user up to our site!
        insertUserDetails($userId, $twitchChannel, $smProfile, $chatbot, $securityKey, $maxRequests, $cooldownMultiplier, $scoreType, $topPercent);
    }


		//$subdomain = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/','',$username)))

	//Process provisioning
		//cpanel calls

		
		//set user level = provisioned upon success
		
		
		//create subdomain call
		
		
		//start db creation calls
			//create user
				//get pass back from DB
				//$retPass = getRowWithValue('userDetails', 'userId', $_SESSION['user_info']['id']);
			//create db
			//grant new user perms to newly created db
			//grant smrequests user account access to the newly created db
			//import mysqlschema.sql
		
		
		//copy site via ftp
		
		
		//write the config.php file for user
		
		
		//email user & admin upon success
		

	

	echo json_encode(
		array(
			'status' => $status,
			'message' => $message
		)
	);