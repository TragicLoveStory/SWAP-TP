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

function adduser($email, $password,$firstname, $lastname, $dateofbirth, $contact, $department,$occupation,$role) {
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

	$query=$con->prepare("INSERT INTO users (`email`,`password`,`first_name`,`last_name`,`date_of_birth`,`contact`,`department`,`occupation`,`role`) VALUES (?,?,?,?,?,?,?,?,?)");
	$query->bind_param('sssssisss', $email, $password, $firstname, $lastname, $dateofbirth, $contact, $department, $occupation, $role);
	if($query->execute()){ //executing query (processes and print the results)
		header("Location: http://localhost/SWAP-TP/userList.php");
		die();
		printok("Closing connection");
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}

function edituser($email, $password,$firstname, $lastname, $dateofbirth, $contact, $department,$occupation,$role,$id){
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

	$query=$con->prepare("UPDATE `users` SET `email`=?, `password`=?, `first_name`=?, `last_name`=?, `date_of_birth`=?, `contact`=?, `department`=?, `occupation`=?, `role`=? WHERE `ID` = ?");
	$query->bind_param('sssssisssi', $email, $password, $firstname, $lastname, $dateofbirth, $contact, $department, $occupation, $role, $id);
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
