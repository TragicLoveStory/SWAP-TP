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
function addAttendance($userId) {
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
	$inputArray = array($userId);
	$inputNames = array('First Name','Last Name', 'Present');
	for($counter = 0; $counter < count($inputArray); $counter++){
		if($counter == 2 || $counter == 3){
			if(strlen($inputArray[$counter]) >= 20){
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
			if(strlen($inputArray[$counter]) >= 20){
				echo $inputNames[$counter]." is too long!<br>";
				die();
			}
		}
		else{
			if(strlen($inputArray[$counter]) >= 30){
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
	$query=$con->prepare("INSERT INTO users (`email`,`password`,`first_name`,`last_name`,`date_of_birth`,`contact`,`department`) VALUES (?)");
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

function edituser($email, $password,$firstname, $lastname, $dateofbirth, $contact, $department,$role,$id){
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
