<?php	// Creates the user table and setup accounts

function printerror($message, $con) {
	echo "<pre>";
	echo "$message<br>";
	if ($con) echo "FAILED: ". mysqli_error($con). "<br>";
	echo "</pre>";
}

function printok($message) {
	echo "<pre>";
	echo "$message<br>";
	echo "OK<br>";
	echo "</pre>";
}

function dateCheck($dateOfBirth){
	$pattern = "/^(19|20)\d{2}[-](0?[1-9]|1[0-2])[-](0?[1-9]|[12]\d|3[01])$/"; //check for YYYY-MM-DD format (Month and Date can be 1 character long)
	if (strlen($pattern) > 0) {
		$ismatch=preg_match($pattern,$dateOfBirth);
		if (!$ismatch || $ismatch==0) {
			return false;
		}
		else{
			list($year, $month, $day) = explode('-', $dateOfBirth); 
    		if(checkdate($month, $day, $year)){
				return true;
			}
			else{
				return false;
			}
		}
	}
	
}

function inputChecker($input,$regexPattern){
	if(empty($input)){
		return false;
	}
	if (strlen($regexPattern) > 0) {
		$ismatch=preg_match($regexPattern,$input);
		if (!$ismatch || $ismatch==0) {
			return false;
		}
		else{
			return true;
		}
	}
}

function adduser($email, $password,$firstname, $lastname, $dateofbirth, $contact, $department) {
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
	$inputArray = array($email,$password,$firstname,$lastname,$dateofbirth,$contact,$department);
	$inputNames = array('Email','Password','First Name','Last Name','Date Of Birth','Contact','Department');
	for($counter = 0; $counter < count($inputArray); $counter++){
		if($counter == 2 || $counter == 3){
			if(strlen($inputArray[$counter]) > 20){
				echo $inputNames[$counter]." is too long!";
				die();
			}
		}
		elseif($counter == 4){
			if(strlen($inputArray[$counter]) > 10){
				echo $inputNames[$counter]." is wrong.";
				die();
			}
		}
		elseif($counter == 5){
			if(strlen($inputArray[$counter]) !== 8){
				echo $inputNames[$counter]." number is incorrect!<br>";
				die();
			}
		}
		elseif($counter == 6){
			if(strlen($inputArray[$counter]) > 20){
				echo $inputNames[$counter]." is too long!<br>";
				die();
			}
		}
		else{
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
	}
	// regular expressions + date checking
	$checkall = true;
	$checkall=$checkall && inputChecker($email,"/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i");
	$checkall=$checkall && inputChecker($password,"/^((?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s] || .*[_])).{10,29}$/"); //must have lower case, upper case, special char and number
	$checkall=$checkall && inputChecker($firstname,"/^[A-Za-z\s]+$/"); //only allow letters
	$checkall=$checkall && inputChecker($lastname,"/^[A-Za-z\s]+$/"); //only allow letters
	$checkall=$checkall && dateCheck($dateofbirth); 
	$checkall=$checkall && inputChecker($contact,"/(6|8|9)\d{7}/"); //only allow singaporean phone number
	$checkall=$checkall && inputChecker($department,"/^[A-Za-z\s]+$/"); //only allow letters
	if (!$checkall) {
		echo "Error checking inputs<br>Please return to the registration form";
		die();
	}
	else{
		echo "Validated";
	}
	// TO DO: Hash + Salt the password input before INSERTING into table
	$query=$con->prepare("INSERT INTO users (`email`,`password`,`first_name`,`last_name`,`date_of_birth`,`contact`,`department`) VALUES (?,?,?,?,?,?,?)");
	$query->bind_param('sssssis', $email, $password, $firstname, $lastname, $dateofbirth, $contact, $department);
	if($query->execute()){ //executing query (processes and print the results)
		// header("Location: http://localhost/SWAP-TP/userList.php");
		// die();
		printok("Closing connection");
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}

function edituser($email, $password,$firstname, $lastname, $dateofbirth, $contact, $department,$id){
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
	$inputArray = array($email,$password,$firstname,$lastname,$dateofbirth,$contact,$department);
	$inputNames = array('Email','Password','First Name','Last Name','Date Of Birth','Contact','Department');
	for($counter = 0; $counter < count($inputArray); $counter++){
		if($counter == 2 || $counter == 3){
			if(strlen($inputArray[$counter]) > 20){
				echo $inputNames[$counter]." is too long!";
				die();
			}
		}
		elseif($counter == 4){
			if(strlen($inputArray[$counter]) > 10){
				echo $inputNames[$counter]." is wrong.";
				die();
			}
		}
		elseif($counter == 5){
			if(strlen($inputArray[$counter]) !== 8){
				echo $inputNames[$counter]." number is incorrect!<br>";
				die();
			}
		}
		elseif($counter == 6){
			if(strlen($inputArray[$counter]) > 20){
				echo $inputNames[$counter]." is too long!<br>";
				die();
			}
		}
		else{
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
	}

	// regular expressions + date checking
	$checkall = true;
	$checkall=$checkall && inputChecker($email,"/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i");
	$checkall=$checkall && inputChecker($password,"/^((?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s] || .*[_])).{10,29}$/"); //must have lower case, upper case, special char and number
	$checkall=$checkall && inputChecker($firstname,"/^[A-Za-z\s]+$/"); //only allow letters
	$checkall=$checkall && inputChecker($lastname,"/^[A-Za-z\s]+$/"); //only allow letters
	$checkall=$checkall && dateCheck($dateofbirth); 
	$checkall=$checkall && inputChecker($contact,"/(6|8|9)\d{7}/"); //only allow singaporean phone number
	$checkall=$checkall && inputChecker($department,"/^[A-Za-z\s]+$/"); //only allow letters
	if (!$checkall) {
		echo "Error checking inputs<br>Please return to the registration form";
		die();
	}
	else{
		echo "Validated";
	}

	$query=$con->prepare("UPDATE `users` SET `email`=?, `password`=?, `first_name`=?, `last_name`=?, `date_of_birth`=?, `contact`=?, `department`=? WHERE `ID` = ?");
	$query->bind_param('sssssisi', $email, $password, $firstname, $lastname, $dateofbirth, $contact, $department,$id);
	if($query->execute()){ //executing query (processes and print the results)
		header("Location: http://localhost/SWAP-TP/userList.php");
		die();
		printok("Closing connection");
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}

?>
