<?php 

function debug() {
	echo "<pre>";
	echo "--------------------------------------------<br>";
	echo "_SESSION<br>";
	print_r($_SESSION);
	echo "_COOKIE<br>";
	print_r($_COOKIE);
	echo "session_name()= " . session_name();
	echo "<br>";
	echo "session_id()= " . session_id();
	echo "<br>";
	echo "</pre>";
}

function attendanceCheck($ID){
	require "config.php";

	try {
	$con=mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
	}
	catch (Exception $e) {
		printerror($e->getMessage(),$con);
	}
	if (!$con) {
		printerror("Connecting to $db_hostname", $con);
		die();
	}
	// check if attendance was already taken(first login of the day POST)
	date_default_timezone_set('Singapore');
	$date = date('Y-m-d', time());
	$dateCheck = $date."%";
	$query=$con->prepare("SELECT `Date` FROM `attendance` WHERE `Date` LIKE ? AND `userId` = ?");
	$query->bind_param('si', $dateCheck,$ID); //bind the parameters
	$query->execute();
	$result = $query-> get_result();
	$row = $result -> fetch_assoc();
	if ($row) {
		//echo "Attendance has already been taken today.";
   	}
	else{
		$query2 = $con->prepare("INSERT INTO `attendance` (`userId`,`status`,`Date`) VALUES (?,?,?)"); //login to become present for attendance at work
		$workAttendance = 1;
		date_default_timezone_set('Singapore');
		$date = date('Y-m-d H:i:s', time());
		$query2->bind_param('iis',$ID,$workAttendance,$date);
		if($query2->execute()){
			//printok("Attendance Updated.");
		}
		else{
			printerror("Selecting $db_database",$con);
		}
	}	
}

function logAttempt($emailInput,$logStatus){
	require "config.php";
	date_default_timezone_set('Singapore');
	try {
	$con=mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
	}
	catch (Exception $e) {
		printerror($e->getMessage(),$con);
	}
	if (!$con) {
		printerror("Connecting to $db_hostname", $con);
		die();
	}
	// status = 1: successful login
	// status = 0: failed login
	// status = -1: failed in logging attempt (-1 is default value)
	$query=$con->prepare("INSERT INTO `logattempts` (`email`,`logStatus`) VALUES (?,?)");
	$query->bind_param('si',$emailInput,$logStatus);
	if($query->execute()){ 
	}
	else{
		echo "Logging Error.";
		die();
	}
}

function checkAttempt($emailInput){ //lockout system
	require "config.php";
	date_default_timezone_set('Singapore');
	try {
	$con=mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
	}
	catch (Exception $e) {
		printerror($e->getMessage(),$con);
	}
	if (!$con) {
		printerror("Connecting to $db_hostname", $con);
		die();
	}
	$query=$con->prepare("SELECT `failedAttempts`,`lastFailed`,`elapsedtime` FROM `users` WHERE `email`=?");
	$query->bind_param('s',$emailInput);
	if($query->execute()){ 
		$result = $query->get_result();
        $row = $result->fetch_assoc();
		$failedAttempts = $row['failedAttempts'] + 1;
		if($failedAttempts >5){ //lockout for 10 minutes
			$status = 1;
			$newFailedAttempts = 0;
			$newLastFailed = "";
			$newLockoutTime = time();
			$elapsedTime = 300;
			$query2=$con->prepare("UPDATE `users` SET `failedAttempts` = ?, `lastFailed` = ?, `elapsedtime` = ?, `lockoutTime`=?, `lockoutStatus`=? WHERE `email` = ?");
			$query2->bind_param('isisss',$newFailedAttempts,$newLastFailed,$elapsedTime,$newLockoutTime,$status, $emailInput);
			if($query2->execute()){
				echo "<p class='AlreadyLoggedInText'>Failed too many login attempts. This account is now locked out. <a href='loginForm.php'>Redirect Here:</a></p>";
				die();
			}
		}
		elseif($failedAttempts == 5 && $row['elapsedtime'] < 300){ //lockout for 10 minutes
			$status = 1;
			$newFailedAttempts = 0;
			$newLastFailed = "";
			$newLockoutTime = time();
			$elapsedTime = 300;
			$query3=$con->prepare("UPDATE `users` SET `failedAttempts` = ?, `lastFailed` = ?, `elapsedtime` = ?, `lockoutTime`=?, `lockoutStatus`=? WHERE `email` = ?");
			$query3->bind_param('isisss',$newFailedAttempts,$newLastFailed,$elapsedTime,$newLockoutTime,$status,$emailInput);
			if($query3->execute()){
				echo "<p class='AlreadyLoggedInText'>Failed too many login attempts. This account is now locked out. <a href='loginForm.php'>Redirect Here:</a></p>";
				die();
			}
		}
		elseif($failedAttempts == 1){ // add 1 failed attempt, update lastFailed 
			$newLastFailed = time();
			$elapsedTime = 0;
			$query4=$con->prepare("UPDATE `users` SET `failedAttempts` = ?, `lastFailed` = ?, `elapsedtime` = ? WHERE `email` = ?");
			$query4->bind_param('isis',$failedAttempts,$newLastFailed,$elapsedTime,$emailInput);
			if($query4 ->execute()){
			}
		}
		else{
			$newLastFailed = time();
			$elapsedTime = $newLastFailed - $row['lastFailed'];
			$newElapsedTime = $elapsedTime + $row['elapsedtime'];
			$query5=$con->prepare("UPDATE `users` SET `failedAttempts` = ?, `lastFailed` = ?, `elapsedtime` = ? WHERE `email` = ?");
			$query5->bind_param('isis',$failedAttempts,$newLastFailed,$newElapsedTime,$emailInput);
			if($query5->execute()){
			}
		}
		
	}
	else{
		echo "Logging Error.";
		die();
	}
}

function checkLockout($emailInput){ //check lockout time left
	require "config.php";
	date_default_timezone_set('Singapore');
	try {
	$con=mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
	}
	catch (Exception $e) {
		printerror($e->getMessage(),$con);
	}
	if (!$con) {
		printerror("Connecting to $db_hostname", $con);
		die();
	}
	$query=$con->prepare("SELECT `elapsedtime`,`lockoutTime` FROM `users` WHERE `email`=?");
	$query->bind_param('s',$emailInput);
	if($query->execute()){
		$result = $query->get_result();
        $row = $result->fetch_assoc();
		$currentTime = time();
		$elapsedTime = $currentTime - $row['lockoutTime'];

		if($elapsedTime >= $row['elapsedtime']){
			$newStatus = 0;
			$resetElapsedTime = 0;
			$resetLockoutTime = "";
			$query2=$con->prepare("UPDATE `users` SET `elapsedtime` = ?, `lockoutTime`=?, `lockoutStatus` =? WHERE `email` = ?");
			$query2->bind_param('isis',$resetElapsedTime,$resetLockoutTime,$newStatus,$emailInput);
			if($query2->execute()){}
		}

		else{
			$newElapsedTime = $row['elapsedtime'] - $elapsedTime;
			$query2=$con->prepare("UPDATE `users` SET `elapsedtime` = ? WHERE `email` = ?");
			$query2->bind_param('is',$newElapsedTime,$emailInput);
			if($query2->execute()){
				echo "<p class='AlreadyLoggedInText'>Account Locked out. Time Left: ".$newElapsedTime.". <a href='loginForm.php'>Redirect Here:</a></p>";
				die();
			}
		}

	}
}

function resetAttempt($emailInput){ //reset all fields when login successfully
	require "config.php";
	date_default_timezone_set('Singapore');
	try {
	$con=mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
	}
	catch (Exception $e) {
		printerror($e->getMessage(),$con);
	}
	if (!$con) {
		printerror("Connecting to $db_hostname", $con);
		die();
	}
	$resetStatus = 1;
	$resetFailedAttempts = 0;
	$resetLastFailed = "";
	$resetElapsedTime = 0;
	$resetLockoutTime = "";
	$query=$con->prepare("UPDATE `users` SET `failedAttempts` = ?,`lastFailed` = ?,`elapsedtime` = ?, `lockoutTime`=?, `lockoutStatus` = ? WHERE `email` = ?");
	$query->bind_param('iisiss',$resetFailedAttempts,$resetLastFailed,$resetElapsedTime,$resetLockoutTime,$resetStatus,$emailInput);
	if($query->execute()){}
}

function login($email,$password,$recaptcha){
    require "config.php";
    require "userFunctions.php";
	date_default_timezone_set('Singapore');
	try {
	$con=mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
	}
	catch (Exception $e) {
		printerror($e->getMessage(),$con);
	}
	if (!$con) {
		printerror("Connecting to $db_hostname", $con);
		die();
	}
	//else printok("Connecting to $db_hostname");

    $query=$con->prepare("SELECT * from users WHERE `email`=?");
    $query->bind_param('s',$email);
    if($query->execute()){ //executing query (processes and print the results)
        $result = $query->get_result();
        $row = $result->fetch_assoc();
		if($row['lockoutStatus'] == 1){
			checkLockout($email);
		}

		//htmlspecialchars the password input as we sanitized the password input before INSERT into database.
        if(empty($row['email']) || empty($row['password']) || $row['email'] !== $email || !password_verify($password,htmlspecialchars($row['password']))){
			//function fails, shows error message
			$logStatus = 0;
			logAttempt($email,$logStatus); //log failed login attempt
			checkAttempt($email);
        }
        else{
			resetAttempt($email);

			$secret_key = '6Lc9GR0kAAAAAOuYLevKn3oYBIErSwwKb70Wr3up';
			// Hitting request to the URL, Google will
    		// respond with success or error scenario
			$url= 'https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$recaptcha;
			// Making request to verify captcha
			$response = file_get_contents($url);
			// Response return by google is in
			// JSON format, so we have to parse
			// that json
			$response = json_decode($response);
			// Checking if response is true or not
			if ($response->success == false) {
				echo "<p class='AlreadyLoggedInText'>Captcha Failure.</p>";
				die();
			} 
			// checks if OTP is enabled
			if($row['otp'] == 1){
				$key = "82734ywehfgagjlwenfgwuipth2498579efgo29835yrehgjkreng";
				$initializationIV = "";
				$AES128_ECB="aes-128-ecb";
				$encryptedValue = openssl_encrypt($email, $AES128_ECB, $key, $options=0, $initializationIV);
				// 30 minute cookie for otp
				setcookie("nezha", $encryptedValue, [
					'expires' => time() + 1800,
					'path' => '/SWAP-TP',
					'domain' => '',
					'secure' => TRUE,
					'httponly' => TRUE,
					'samesite' => 'Strict'
				]);
				include "mailFunctions.php";
				sendOTP($email);
			}

			$logStatus = 1;
			logAttempt($email,$logStatus); //log successful login attempt

			session_destroy(); //destroy the session created at loginForm.php
			attendanceCheck($row['ID']); // take attendance for that day
			session_set_cookie_params([ 
				'lifetime' => '86400',
				'path' => '/SWAP-TP',
				'domain' => '',
				'secure' => TRUE,
				'httponly' => TRUE,
				'samesite' => 'Strict'
			]);
			session_start(); //start session
			if(isset($_COOKIE['PHPSESSID'])){
				//echo $_COOKIE['PHPSESSID']."<br>Redundant cookie named PHPSESSID containing session ID to be removed.";
				setcookie('PHPSESSID', "", time()-1*60*60, "/");
				session_unset(); // remove/unset/free all session variables
				session_destroy(); //destroy the session 

				session_start(); //start a NEW session
				session_regenerate_id(); //regenerate a new session ID because old one was destroyed
				//printok("Started session");
				$_SESSION["ID"]=$row['ID'];
				$_SESSION['email'] = $row['email']; 
				$_SESSION["role"]=$row['role'];
				$_SESSION["occupation"]=$row['occupation'];
				$_SESSION["department"]=$row['department'];
				$_SESSION["status"]=$row['status'];
				//printok("Added ID & role into _SESSION"); //acknowledgement
			}
			else{
				//session_set_cookie_params(30*24*60*60,"/SWAP-TP", "",TRUE,TRUE); //this is non strict (non same site only)
				$_SESSION["ID"]=$row['ID']; 
				$_SESSION['email'] = $row['email']; 
				$_SESSION["role"]=$row['role'];
				$_SESSION["occupation"]=$row['occupation'];
				$_SESSION["department"]=$row['department'];
				$_SESSION["status"]=$row['status'];
				//printok("Added ID & role into _SESSION"); //acknowledgement
			}
			if($_SESSION['status'] === -1){
				header("Location: https://localhost/SWAP-TP/firstPassChange.php");
				die();
			}
			else{
				header("Location: https://localhost/SWAP-TP/index.php");
				die();
			}
        }
	}
	else{
		echo "Login Error.";
		die();
		//printerror("Selecting $db_database",$con);
	}
}

function logout(){
	foreach ($_COOKIE as $key=>$value) {
		foreach($_COOKIE as $key=>$value){
			echo "Clearing: $key $value<br>";
			setcookie($key, "", time()-1*60*60, "/SWAP-TP"); // path needs to match the initial setcookie() call
		}
	}
	session_unset();
	session_destroy();
	header("Location: https://localhost/SWAP-TP/loginForm.php");
	die();
}

function firstPasswordChange($passwordInput,$confirmPasswordInput){
	if($passwordInput != $confirmPasswordInput){
		echo "<p class='AlreadyLoggedInText'>Passwords Do not match.</p>";
		die();
	}
	require "config.php";
	try {
	$con=mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
	}
	catch (Exception $e) {
		printerror($e->getMessage(),$con);
	}
	if (!$con) {
		printerror("Connecting to $db_hostname", $con);
		die();
	}
	// min & max length input validation
	$inputArray = array($passwordInput,$confirmPasswordInput);
	$inputNames = array('Password','Confirm Password');
	for($counter = 0; $counter < count($inputArray); $counter++){
		if(strlen($inputArray[$counter]) > 30){
			echo "<p class='AlreadyLoggedInText'>".$inputNames[$counter]." is too long!.</p>";
			die();
		}
		elseif(strlen($inputArray[$counter]) < 10){
			echo "<p class='AlreadyLoggedInText'>".$inputNames[$counter]." is too short!.</p>";
			die();
		}
	}
	// regular expressions
	$checkall = true;
	$checkall=$checkall && inputChecker($passwordInput,"/^((?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s])).{10,29}$/"); //must have lower case, upper case, special char and number
	if (!$checkall) {
		echo "<p class='AlreadyLoggedInText'>Incorrect Input. Must contain 1 Lowercase, Uppercase, Special Character, number and at least 10 characters long</p>";
		die();
	}
	// htmlspecialchars
	$sanitizedPasswordInput = htmlspecialchars($passwordInput);
	//hashing password
	$hashed_password = password_hash($sanitizedPasswordInput,PASSWORD_DEFAULT);
	$newStatus = 1;
	$query=$con->prepare("UPDATE `users` SET `password`=?, `status`=? WHERE `ID` = ?");
	$query->bind_param('sii',$hashed_password, $newStatus, $_SESSION['ID']);
	if($query->execute()){ //executing query (processes and print the results)
		logout();
	}
	else{
		echo "First password change error.";
		die();
		//printerror("Selecting $db_database",$con);
	}
}

function otpLogin($inputOTP){
	require "config.php";
    require "userFunctions.php";
	date_default_timezone_set('Singapore');
	try {
	$con=mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
	}
	catch (Exception $e) {
		printerror($e->getMessage(),$con);
	}
	if (!$con) {
		printerror("Connecting to $db_hostname", $con);
		die();
	}
	else printok("Connecting to $db_hostname");

	// decryption of cookie containing email input
	$key = "82734ywehfgagjlwenfgwuipth2498579efgo29835yrehgjkreng";
	$initializationIV = "";
	$AES128_ECB="aes-128-ecb";
	$decryptedvalue = openssl_decrypt($_COOKIE['nezha'], $AES128_ECB, $key, $options=0, $initializationIV);
	//decryption of cookie value containing OTP input
	$key2 = "erioepb834759-0324523u45htyiow45hjiopwjh-90et0-239456";
    $initializationIV2 = "";
    $AES128_ECB2="aes-128-ecb";
    $decryptedvalue2 = openssl_decrypt($_COOKIE['nali'], $AES128_ECB2, $key2, $options=0, $initializationIV2);

	if(!isset($_COOKIE['nezha']) || !isset($_COOKIE['nali']) || $decryptedvalue2 != $inputOTP){
		echo "Incorrect OTP";
		die();
	}
	foreach ($_COOKIE as $key=>$value) {
		foreach($_COOKIE as $key=>$value){
			echo "Clearing: $key $value<br>";
			setcookie($key, "", time()-1*60*60, "/SWAP-TP"); // path needs to match the initial setcookie() call
		}
	}
    $query=$con->prepare("SELECT * from users WHERE `email`=?");
    $query->bind_param('s',$decryptedvalue);
    if($query->execute()){ //executing query (processes and print the results)
        $result = $query->get_result();
        $row = $result->fetch_assoc();
		//htmlspecialchars the password input as we sanitized the password input before INSERT into database.
        if(empty($row['email']) || $row['email'] !== $decryptedvalue){
            echo "<p class='AlreadyLoggedInText'>ERROR: Email and/or Password is incorrect.</p>";
			die();
        }
        else{
			$logStatus = 1;
			logAttempt($decryptedvalue,$logStatus); //log successful login attempt
			resetAttempt($decryptedvalue); //reset anything failed attempts just in case

			attendanceCheck($row['ID']);
            printok("Login Successful");
			session_set_cookie_params([
				'lifetime' => '86400',
				'path' => '/SWAP-TP',
				'domain' => '',
				'secure' => TRUE,
				'httponly' => TRUE,
				'samesite' => 'Strict'
			]);
			session_start();
			if(isset($_COOKIE['PHPSESSID'])){
				echo $_COOKIE['PHPSESSID']."<br>Redundant cookie named PHPSESSID containing session ID to be removed.";
				setcookie('PHPSESSID', "", time()-1*60*60, "/");
				session_unset(); // remove/unset/free all session variables
				session_destroy(); //destroy the session 

				session_start(); //start a NEW session
				session_regenerate_id(); //regenerate a new session ID because old one was destroyed
				printok("Started session"); //creates/resumes session
				$_SESSION["ID"]=$row['ID'];
				$_SESSION['email'] = $row['email']; 
				$_SESSION["role"]=$row['role'];
				$_SESSION["occupation"]=$row['occupation'];
				$_SESSION["department"]=$row['department'];
				$_SESSION["status"]=$row['status'];
			}
			else{
				//session_set_cookie_params(30*24*60*60,"/SWAP-TP", "",TRUE,TRUE); //this is non strict (non same site only)
				printok("Started session"); //creates/resumes session
				$_SESSION["ID"]=$row['ID']; 
				$_SESSION['email'] = $row['email']; 
				$_SESSION["role"]=$row['role'];
				$_SESSION["occupation"]=$row['occupation'];
				$_SESSION["department"]=$row['department'];
				$_SESSION["status"]=$row['status'];
			}
			if($_SESSION['status'] === -1){
				header("Location: https://localhost/SWAP-TP/firstPassChange.php");
				die();
			}
			else{
				header("Location: https://localhost/SWAP-TP/index.php");
				die();
			}
        }
	}
	else{
		echo "Login Error.";
		die();
		//printerror("Selecting $db_database",$con);
	}
}
?>