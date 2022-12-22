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

if(isset($_POST['Submit']) && $_POST['Submit'] === "Sign in"){
    if(!empty($_POST['username']) && !empty($_POST['password'])){
        login($_POST['username'],$_POST['password']);
    }
    else{
        echo "Error: No fields should be empty<br>";
    }
}

if(isset($_POST['Submit2']) && $_POST['Submit2'] === "Sign Out"){
    logout();
}

function login($email,$password){
    require "config.php";
    require "userFunctions.php";

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

    $query=$con->prepare("SELECT `ID`,`email`,`password`,`first_name`,`last_name`,`date_of_birth`,`contact`,`department`,`role`,`status` from users WHERE `email`=?");
    $query->bind_param('s',$email);
    if($query->execute()){ //executing query (processes and print the results)
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        if(empty($row['email']) || empty($row['password']) || $row['email'] !== $email || !password_verify($password,$row['password'])){
            printerror("Wrong Login Credentials",$con);
        }
        else{
			//preliminary Attendance Checking system for employees
			$query2 = $con->prepare("INSERT INTO `attendance` (`userId`,`workAttendance`) VALUES (?,?)"); //login to become present for attendance at work
			$workAttendance = 1;
			$query2->bind_param('ii',$row['ID'],$workAttendance);
			if($query2->execute()){
				printok("Attendance Updated.");
			}
			else{
				printerror("Selecting $db_database",$con);
			}
			//preliminary Attendance Checking system for employees
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
				echo $_COOKIE['PHPSESSID']."Redundant cookie named PHPSESSID containing session ID to be removed.";
				setcookie('PHPSESSID', "", time()-1*60*60, "/");
				session_unset(); // remove/unset/free all session variables
				session_destroy(); //destroy the session 

				session_start(); //start a NEW session
				session_regenerate_id(); //regenerate a new session ID because old one was destroyed
				printok("Started session"); //creates/resumes session
				$_SESSION["ID"]=$row['ID']; //adds email variable into session (form of keypair/hash map)
				$_SESSION["role"]=$row['role'];
				$_SESSION["status"]=$row['status'];
				printok("Added ID & role into _SESSION"); //acknowledgement
				setcookie("Department", $row['department'], [
					'expires' => time() + 86400,
					'path' => '/SWAP-TP',
					'domain' => '',
					'secure' => TRUE,
					'httponly' => TRUE,
					'samesite' => 'Strict'
				]);
			}
			else{
				//session_set_cookie_params(30*24*60*60,"/SWAP-TP", "",TRUE,TRUE); //this is non strict (non same site only)
				printok("Started session"); //creates/resumes session
				$_SESSION["ID"]=$row['ID']; //adds email variable into session (form of keypair/hash map)
				$_SESSION["role"]=$row['role'];
				$_SESSION["status"]=$row['status'];
				printok("Added ID & role into _SESSION"); //acknowledgement
				//setcookie("Department", $row['department'], time()+30*24*60*60, "/SWAP-TP", "",TRUE,TRUE); this is non strict (non same site only)
				setcookie("Department", $row['department'], [
					'expires' => time() + 86400,
					'path' => '/SWAP-TP',
					'domain' => '',
					'secure' => TRUE,
					'httponly' => TRUE,
					'samesite' => 'Strict'
				]);
				// header("Location: http://localhost/SWAP-TP/foo.php");
				// die();
				//debug();
			}
			if($_SESSION['status'] === -1){
				header("Location: http://localhost/SWAP-TP/firstPassChange.php");
				die();
			}
        }
	}
	else{
		printerror("Selecting $db_database",$con);
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
	header("Location: http://localhost/SWAP-TP/loginForm.php");
	die();
}

function firstPasswordChange($passwordInput,$confirmPasswordInput){
	if($passwordInput !== $confirmPasswordInput){
		echo "Passwords do not match.";
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
	else printok("Connecting to $db_hostname");
	// min & max length input validation
	$inputArray = array($passwordInput,$confirmPasswordInput);
	$inputNames = array('Password','Confirm Password');
	for($counter = 0; $counter < count($inputArray); $counter++){
		if(strlen($inputArray[$counter]) > 30){
			echo $inputNames[$counter]." is too long!<br>";
			die();
		}
		elseif(strlen($inputArray[$counter]) < 10){
			echo $inputNames[$counter]." is too Short!<br>";
			die();
		}
		else{

		}
	}
	// regular expressions + date checking
	$checkall = true;
	$checkall=$checkall && inputChecker($passwordInput,"/^((?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s])).{10,29}$/"); 
	$checkall=$checkall && inputChecker($confirmPasswordInput,"/^((?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s])).{10,29}$/"); //must have lower case, upper case, special char and number
	if (!$checkall) {
		echo "Error checking inputs<br>Please return to the registration form";
		die();
	}
	else{
		echo "Validated";
	}
	// htmlspecialchars
	$sanitizedPasswordInput = htmlspecialchars($passwordInput);
	//hashing password
	$hashed_password = password_hash($sanitizedPasswordInput,PASSWORD_DEFAULT);
	$newStatus = 1;
	$query=$con->prepare("UPDATE `users` SET `password`=?, `status`=? WHERE `ID` = ?");
	$query->bind_param('sii',$hashed_password, $newStatus, $_SESSION['ID']);
	if($query->execute()){ //executing query (processes and print the results)
		printok("Closing connection");
		echo "Logging Out. Log back in.";
		logout();
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}
?>