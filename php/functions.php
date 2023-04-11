<?php
	/**
	 * Get DB connection
	 *
	 * @param void
	 *
	 * @return db connection
	 */
	function getDatabaseConnection() {
		try { // connect to database and return connections
			//wh_log("In Get Database Connection" . PHP_EOL);
			//wh_log("DB INfo is - Host: " . DB_HOST . ", Name: " . DB_NAME . " , User: " . DB_USER . ", Pass: " . DB_PASS . " right now" . PHP_EOL);
			$conn = new PDO( 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS );
			//wh_log("Connection is " . $conn . " right now" . PHP_EOL);
			return $conn;
		} catch ( PDOException $e ) { // connection to database failed, report error message
			return $e->getMessage();
		}
	}

	/**
	 * Get SMR User DB connection 
	 *
	 * @param smrUser
	 *
	 * @return db connection
	 */
	function getSMRDatabaseConnection( $smrUser ) {
		try { // connect to database and return connections
			//wh_log("In Get Database Connection" . PHP_EOL);
			//wh_log("DB INfo is - Host: " . DB_HOST . ", Name: " . DB_NAME . " , User: " . DB_USER . ", Pass: " . DB_PASS . " right now" . PHP_EOL);
			$conn = new PDO( 'mysql:host=' . DB_HOST . ';dbname=' . DB_PREFIX . $smrUser , DB_USER, DB_PASS );
			//wh_log("Connection is " . $conn . " right now" . PHP_EOL);
			return $conn;
		} catch ( PDOException $e ) { // connection to database failed, report error message
			return $e->getMessage();
		}
	}

	/**
	 * Post message to Log
	 *
	 * @param message
	 *
	 * @return void
	 */
	function wh_log($log_msg){
		$log_filename = __DIR__."/log";
		if (!file_exists($log_filename)) 
		{
			// create directory/folder uploads.
			mkdir($log_filename, 0777, true);
		}
		$log_file_data = $log_filename.'/log_' . date('Y-m-d') . '.log';
		$log_msg = rtrim($log_msg); //remove line endings
		// if you don't add `FILE_APPEND`, the file will be erased each time you add a log
		file_put_contents($log_file_data, date("Y-m-d H:i:s") . " -- [" . strtoupper(basename(__FILE__)) . "] : ". $log_msg . PHP_EOL, FILE_APPEND);
	}
	
	/**
	 * Update user
	 *
	 * @param array $info
	 *
	 * @return void
	 */
	function updateUserInfo( $info ) {
		// get database connection
		$databaseConnection = getDatabaseConnection();

		// create our sql statment adding in password only if change password was checked
		$statement = $databaseConnection->prepare( '
			UPDATE
				users
			SET
				email = :email,
				first_name = :first_name,
				last_name = :last_name
				' . ( isset( $info['change_password'] ) ? ', password = :password ' : '' ) . '
			WHERE
				key_value = :key_value
		' );

		$params = array( //params 
			'email' => trim( $info['email'] ),
			'first_name' => trim( $info['first_name'] ),
			'last_name' => trim( $info['last_name'] ),
		);

		if ( isset( $info['change_password'] ) ) { // add password and key value if password checkbox is checked
			$params['password'] = hashedPassword( $info['password'] );
			$params['key_value'] = $info['key_value'];
		} else { // only add key value, change password checkbox was not checked
			$params['key_value'] = $info['key_value'];
		}

		// run the sql statement
		$statement->execute( $params );
	}

	/**
	 * Update user details
	 *
	 * @param array $details
	 *
	 * @return void
	 */
	function updateUserDetails( $details ) {
		// get database connection
		$databaseConnection = getDatabaseConnection();

		// create our sql statment
		$statement = $databaseConnection->prepare( '
			UPDATE
				userdetails
			SET
				twitchChannel = :twitchChannel,
				smProfile = :smProfile,
				chatbot = :chatbot,
				securityKey = :securityKey,
				maxRequests = :maxRequests,
				cooldownMultiplier = :cooldownMultiplier,
				scoreType = :scoreType
				topPercent = :topPercent,
			WHERE
				id = :id
		' );

		$params  = array( //params 
			'twitchChannel' => trim( $details['twitch_channel'] ),
			'smProfile' => trim( $details['sm_profile'] ),
			'chatbot' => trim( $details['chatbot'] ),
			'securityKey' => trim( $details['security_key'] ),
			'maxRequests' => trim( $details['maxRequests'] ),
			'cooldownMultiplier' => trim( $details['cooldownMultiplier'] ),
			'scoreType' => trim( $details['scoreType'] ),
			'topPercent' => trim( $details['topPercent'] ),
			'id' => trim( $details['id'] ),
		);

		//$params['id'] = $details['id']; // add id

		// run the sql statement
		$statement->execute( $params );
	}

	/**
	 * Get row from a table with a value
	 *
	 * @param string $smRuser	 
	 * @param string $tableName
	 * @param string $column
	 * @param string $value
	 *
	 * @return array $data
	 */
	function getSMRRowWithValue( $smrUser, $tableName, $column, $value ) {
		// get database connection
		$databaseConnection = getSMRDatabaseConnection( $smrUser );

		// create our sql statment
		$statement = $databaseConnection->prepare( '
			SELECT
				*
			FROM
				' . $tableName . '
			WHERE
				' . $column . ' = :' . $column
		);
		//wh_log("Statement is " . json_encode($statement));
		// execute sql with actual values
		$statement->setFetchMode( PDO::FETCH_ASSOC );
		$statement->execute( array(
			$column => trim( $value )
		) );

		// get and return data
		$data = $statement->fetch();
		return $data;
	}

	/**
	 * Get row from a table with a value
	 *
	 * @param string $tableName
	 * @param string $column
	 * @param string $value
	 *
	 * @return array $data
	 */
	function getRowWithValue( $tableName, $column, $value ) {
		// get database connection
		$databaseConnection = getDatabaseConnection();

		// create our sql statment
		$statement = $databaseConnection->prepare( '
			SELECT
				*
			FROM
				' . $tableName . '
			WHERE
				' . $column . ' = :' . $column
		);

		// execute sql with actual values
		$statement->setFetchMode( PDO::FETCH_ASSOC );
		$statement->execute( array(
			$column => trim( $value )
		) );

		// get and return data
		$data = $statement->fetch();
		return $data;
	}
	
	/**
	 * Get data from a table with limit and offset
	 *
	 * @param string $tableName
	 * @param string $column
	 * @param string $limit
	 * @param string $offset
	 *
	 * @return array $data
	 */
	function getSMRdataWithLimit( $smrUser, $tableName, $limit, $offset ) {
		// get database connection
		$databaseConnection = getSMRDatabaseConnection( $smrUser );

		// create our sql statment
		$statement = $databaseConnection->prepare( '
			SELECT
				*
			FROM
				' . $tableName . '
			LIMIT
				' . $limit . '
			OFFSET 
				' . $offset
		);
		//wh_log("Statement is " . json_encode($statement));
		// execute sql with actual values
		$statement->setFetchMode( PDO::FETCH_ASSOC );
		$statement->execute();

		// get and return data
		$data = $statement->fetchALL();
		return $data;
	}
	
	/**
	 * Get data from a table with limit and offset
	 *
	 * @param string $smrUser
	 * @param string $tableName

	 *
	 * @return string $data
	 */
	function getSMRcountOnTable( $smrUser, $tableName ) {
		// get database connection
		$databaseConnection = getSMRDatabaseConnection( $smrUser );

		// create our sql statment
		$statement = $databaseConnection->prepare( '
			SELECT
				COUNT(*)
			FROM
				' . $tableName
		);
		//wh_log("Statement is " . json_encode($statement));
		// execute sql with actual values
		$statement->setFetchMode( PDO::FETCH_ASSOC );
		$statement->execute();

		// get and return data
		$data = $statement->fetchColumn();
		return $data;
	}

	/**
	 * Get user with email address
	 *
	 * @param array $email
	 *
	 * @return array $userInfo
	 */
	function getUserWithEmailAddress( $email ) {
		//wh_log("In get user with email address" . PHP_EOL);
		//wh_log("Email is: " . $email . " right now." . PHP_EOL);
		// get database connection
		$databaseConnection = getDatabaseConnection();
		//wh_log("Database connection is " . $databaseConnection . " right now." . PHP_EOL);

		// create our sql statment
		$statement = $databaseConnection->prepare( '
			SELECT
				*
			FROM
				users
			WHERE
				email = :email
		' );

		// execute sql with actual values
		$statement->setFetchMode( PDO::FETCH_ASSOC );
		$statement->execute( array(
			'email' => trim( $email )
		) );

		// get and return user
		$user = $statement->fetch();
		return $user;
	}

	/**
	 * Update an SMR colum with a value in a table by id
	 * @param string $smrUser
	 * @param string $tableName
	 * @param string $column
	 * @param string $value
	 * @param string $id
	 *
	 * @return void
	 */
	function updateSMRRow( $smrUser, $tableName, $column, $value, $id ) {
		// get database connection
		$databaseConnection = getSMRDatabaseConnection($smrUser);

		// create our sql statment
		$statement = $databaseConnection->prepare( '
			UPDATE
				' . $tableName . '
			SET
				' . $column . ' = :value
			WHERE
				id = :id
		' );

		// set our parameters to use with the statment
		$params = array(
			'value' => trim( $value ),
			'id' => trim( $id )
		);

		// run the query
		$statement->execute( $params );
	}

	/**
	 * Update a colum with a value in a table by id
	 *
	 * @param string $tableName
	 * @param string $column
	 * @param string $value
	 * @param string $id
	 *
	 * @return void
	 */
	function updateRow( $tableName, $column, $value, $id ) {
		// get database connection
		$databaseConnection = getDatabaseConnection();

		// create our sql statment
		$statement = $databaseConnection->prepare( '
			UPDATE
				' . $tableName . '
			SET
				' . $column . ' = :value
			WHERE
				id = :id
		' );

		// set our parameters to use with the statment
		$params = array(
			'value' => trim( $value ),
			'id' => trim( $id )
		);

		// run the query
		$statement->execute( $params );
	}

	/**
	 * Get data from songs table with query, limit, and offset
	 *
	 * @param string $tableName
	 * @param string $query
	 * @param string $limit
	 * @param string $offset
	 *
	 * @return array $data
	 */
	function getSMRSongsWithLimit( $smrUser, $query, $limit, $offset ) {
		// get database connection
		$databaseConnection = getSMRDatabaseConnection( $smrUser );
		
		//wh_log("Query is " . $query . " as we enter command");
		
		// create our sql statments
		if(!isset($query) || !$query){
			$statement = $databaseConnection->prepare( '
				SELECT
					*
				FROM
					sm_songs
				WHERE
					installed = 1
				LIMIT
					' . $limit . '
				OFFSET 
					' . $offset
			);
		} else {
			$query = urldecode($query);
			$query = str_replace("'", "''", $query);
			//wh_log("Query is " . $query . " after we ran urldecode");
			
			$statement = $databaseConnection->prepare( '
				SELECT
					*
				FROM
					sm_songs
				WHERE
					installed = 1 AND (title like \'%' . $query . '%\' OR artist like \'' . $query . '%\' OR pack like \'%' . $query . '%\' OR id like \'%' . $query . '%\')
				LIMIT
					' . $limit . '
				OFFSET 
					' . $offset
			);
		}			
		//wh_log("Statement is " . json_encode($statement));
		// execute sql with actual values
		$statement->setFetchMode( PDO::FETCH_ASSOC );
		$statement->execute();

		// get and return data
		$data = $statement->fetchALL();
		return $data;
	}

	/** Get list of packs from songs table
	*
	* @param string $smrUser
	*
	* @return array $data
	*/
   function getSMRPacks( $smrUser ) {
	   // get database connection
	   $databaseConnection = getSMRDatabaseConnection( $smrUser );
	   
	   //wh_log("Query is " . $query . " as we enter command");
	   
	   // create our sql statments
		   $statement = $databaseConnection->prepare( '
			   SELECT
				   DISTINCT(pack)
			   FROM
				   sm_songs
			   WHERE
				   installed = 1'
		   );
	   // execute sql with actual values
	   $statement->setFetchMode( PDO::FETCH_ASSOC );
	   $statement->execute();

	   // get and return data
	   $data = $statement->fetchALL();
	   return $data;
   }
		
	/**
	 * Get only banned data from songs table with query, limit, and offset
	 *
	 * @param string $tableName
	 * @param string $query
	 * @param string $limit
	 * @param string $offset
	 *
	 * @return array $data
	 */
	function getSMRBannedSongsWithLimit( $smrUser, $query, $limit, $offset ) {
		// get database connection
		$databaseConnection = getSMRDatabaseConnection( $smrUser );
		
		//wh_log("Query is " . $query . " as we enter command");
		
		// create our sql statments
		if(!isset($query) || !$query){
			$statement = $databaseConnection->prepare( '
				SELECT
					*
				FROM
					sm_songs
				WHERE
					installed = 1 AND banned <> 0
				LIMIT
					' . $limit . '
				OFFSET 
					' . $offset
			);
		} else {
			$query = urldecode($query);
			//wh_log("Query is " . $query . " after we ran urldecode");
			$statement = $databaseConnection->prepare( '
				SELECT
					*
				FROM
					sm_songs
				WHERE
					installed = 1 AND banned <> 0 AND (title like \'%' . $query . '%\' OR artist like \'' . $query . '%\' OR  like \'%' . $query . '%\')
				LIMIT
					' . $limit . '
				OFFSET 
					' . $offset
			);
		}			
		//wh_log("Statement is " . json_encode($statement));
		// execute sql with actual values
		$statement->setFetchMode( PDO::FETCH_ASSOC );
		$statement->execute();

		// get and return data
		$data = $statement->fetchALL();
		return $data;
	}	

	/**
	 * Sign a user up
	 *
	 * @param array $info
	 *
	 * @return array $userInfo
	 */
	function signUserUp( $info ) {
		// get database connection
		$databaseConnection = getDatabaseConnection();

		// create our sql statment
		$statement = $databaseConnection->prepare( '
			INSERT INTO
				users (
					email,
					first_name,
					last_name,
					password,
					key_value,
					fb_user_id,
					fb_access_token,
					tw_user_id,
					oauth_token,
					oauth_token_secret,
					twitch_user_id,
					twitch_access_token,
					twitch_refresh_token
				)
			VALUES (
				:email,
				:first_name,
				:last_name,
				:password,
				:key_value,
				:fb_user_id,
				:fb_access_token,
				:tw_user_id,
				:oauth_token,
				:oauth_token_secret,
				:twitch_user_id,
				:twitch_access_token,
				:twitch_refresh_token
			)
		' );

		// execute sql with actual values
		$statement->execute( array(
			'email' => trim( $info['email'] ),
			'first_name' => trim( $info['first_name'] ),
			'last_name' => trim( $info['last_name'] ),
			'password' => isset( $info['password'] ) ? hashedPassword( $info['password'] ) : '',
			'key_value' => newKey(),
			'fb_user_id' => isset( $info['id'] ) ? $info['id'] : '',
			'fb_access_token' => isset( $info['fb_access_token'] ) ? $info['fb_access_token'] : '',
			'tw_user_id' => isset( $info['tw_user_id'] ) ? $info['tw_user_id'] : '',
			'oauth_token' => isset( $info['oauth_token'] ) ? $info['oauth_token'] : '',
			'oauth_token_secret' => isset( $info['oauth_token_secret'] ) ? $info['oauth_token_secret'] : '',
			'twitch_user_id' => isset( $info['twitch_user_id'] ) ? $info['twitch_user_id'] : '',
			'twitch_access_token' => isset( $info['twitch_access_token'] ) ? $info['twitch_access_token'] : '',
			'twitch_refresh_token' => isset( $info['twitch_refresh_token'] ) ? $info['twitch_refresh_token'] : '',
		) );

		// return id of inserted row
		return $databaseConnection->lastInsertId();
	}

	/**
	 * Generate a key for a user
	 *
	 * @param array $info
	 *
	 * @return array $userInfo
	 */
	function newKey( $length = 32 ) {
		$time = md5( uniqid() ) . microtime();
		return substr( md5( $time ), 0, $length );
	}

	/**
	 * Hash password
	 *
	 * @param String $password plain text password
	 * @param String $salt to hash passoword with set to false auto gen one
	 *
	 * @return Sting of password now hashed
	 */
	function hashedPassword( $password ) {
		$random = openssl_random_pseudo_bytes( 18 );
		$salt = sprintf( '$2y$%02d$%s',
			12, // 2^n cost factor, hackers got nothin on this!
			substr( strtr( base64_encode( $random ), '+', '.' ), 0, 22 )
		);

		// hash password with salt
		$hash = crypt( $password, $salt );

		// return hash
		return $hash;
	}

		/**
	 * Generate a random password for use for DB user
	 */
	function randomPassword() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}


	/**
	 * Insert user details upon SMRSetup completion
	 * 
	 * @param userId
	 * @param twitchChannel
	 * @param smProfile
	 * @param chatbot
	 * @param securityKey
	 * @param maxRequests
	 * @param cooldownMultiplier
	 * @param scoreType
	 * @param topPercent
	 * 
	 * @return boolean
	 */
	function insertUserDetails ($userId, $twitchChannel, $smProfile, $chatbot, $securityKey, $maxRequests, $cooldownMultiplier, $scoreType, $topPercent ){
		// get database connection
		$databaseConnection = getDatabaseConnection();

		// create our sql statment
		$statement = $databaseConnection->prepare( '
		INSERT INTO
				userdetails (
					userId,
					twitchChannel,
					smProfile,
					chatbot,
					securityKey,
					maxRequests,
					cooldownMultiplier,
					scoreType,
					topPercent,
					dbPass
				)
			VALUES (
				:userId,
				:twitchChannel,
				:smProfile,
				:chatbot,
				:securityKey,
				:maxRequests,
				:cooldownMultiplier,
				:scoreType,
				:topPercent,
				:dbPass
			)
		' );

		// execute sql with actual values
		$statement->execute( array(
			'userId' => trim( $userId ),
			'twitchChannel' => trim( $twitchChannel ),
			'smProfile' => trim( $smProfile ),
			'chatbot' => trim( $chatbot ),
			'securityKey' => trim( $securityKey ),
			'maxRequests' => trim( $maxRequests ),
			'cooldownMultiplier' => trim( $cooldownMultiplier ),
			'scoreType' => trim( $scoreType ),
			'topPercent' => trim( $topPercent ),
			'dbPass' => randomPassword(), 
		) 
	);

		// return true upon success
		return true;
	}

	/**
	 * Check if user is logged in
	 *
	 * @param void
	 *
	 * @return boolean
	 */
	function isLoggedIn() {
		if ( ( isset( $_SESSION['is_logged_in'] ) && $_SESSION['is_logged_in'] ) && ( isset( $_SESSION['user_info'] ) && $_SESSION['user_info'] ) ) { // check session variables, user is logged in
			return true;
		} else { // user is not logged in
			return false;
		}
	}

	/**
	 * If user is logged in, redirect to homepage
	 *
	 * @param void
	 *
	 * @return boolean
	 */
	function loggedInRedirect() {
		if ( isLoggedIn() ) { // user is logged in
			// send them to the home page
			header( 'location: index.php' );
		}
	}

	// Check to see if the currently-authenticated user's level is 1 - Provisioned
	function isProvisioned() {
		if ( isset( $_SESSION['user_info'] ) && $_SESSION['user_info'] && USER_LEVEL_PROVISIONED == $_SESSION['user_info']['user_level'] ) {
			return true;
		} else {
			return false;
		}
	}

	// Check to see if the currently-authenticated user's level is 2 - Premium
	function isPremium() {
		if ( isset( $_SESSION['user_info'] ) && $_SESSION['user_info'] && USER_LEVEL_PREMIUM == $_SESSION['user_info']['user_level'] ) {
			return true;
		} else {
			return false;
		}
	}

	// Check to see if the currently-authenticated user's level is 3 - Admin
	function isAdmin() {
		if ( isset( $_SESSION['user_info'] ) && $_SESSION['user_info'] && USER_LEVEL_ADMIN == $_SESSION['user_info']['user_level'] ) {
			return true;
		} else {
			return false;
		}
	}

	// Check to see if the currently-authenticated user is not provisioned or higher AND whether they have submitted the SetupSMR form
	function isSetupSubmitted() {
		if ( isset( $_SESSION['user_info']) && $_SESSION['user_info']['user_level'] == 0 ) {
			// get database connection
			$databaseConnection = getDatabaseConnection();
			//wh_log("Database connection is " . $databaseConnection . " right now." . PHP_EOL);

			$userId = $_SESSION['user_info']['id'];
			// create our sql statment
			$statement = $databaseConnection->prepare( '
				SELECT
					userId
				FROM
					userdetails
				WHERE
					userId = :userId
			' );

			// execute sql with actual values
			$statement->setFetchMode( PDO::FETCH_ASSOC );
			$statement->execute( array(
				'userId' => trim( $userId )
			) );
			// get and return user
			$submitted = $statement->fetch();
			if (!empty($submitted['userId'])){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	if ( ! function_exists( 'password_verify' ) ) { // if version of php does not have password_verify function we need to define it
		/**
		 * password_verify()
		 *
		 * @link	http://php.net/password_verify
		 * @param	string	$password
		 * @param	string	$hash
		 * @return	bool
		 */
		function password_verify( $password, $hash ) {
			if ( strlen( $hash ) !== 60 OR strlen($password = crypt($password, $hash)) !== 60) {
				return FALSE;
			}

			$compare = 0;

			for ( $i = 0; $i < 60; $i++ ) {
				$compare |= ( ord( $password[$i] ) ^ ord( $hash[$i] ) );
			}

			return ( $compare === 0 );
		}
	}