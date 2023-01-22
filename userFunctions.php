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

function adduser($email, $password,$firstname, $lastname, $dateofbirth, $contact, $department,$occupation) {
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
	$inputArray = array($email,$password,$firstname,$lastname,$dateofbirth,$contact,$department,$occupation);
	$inputNames = array('Email','Password','First Name','Last Name','Date Of Birth','Contact','Department','Occupation');
	for($counter = 0; $counter < count($inputArray); $counter++){
		if($counter == 2 || $counter == 3){
			if(strlen($inputArray[$counter]) > 20){
				echo "<p class='AlreadyLoggedInText'>".$inputNames[$counter]." is too long!</p>";
				die();
			}
		}
		elseif($counter == 4){
			if(strlen($inputArray[$counter]) > 10){
				echo "<p class='AlreadyLoggedInText'>".$inputNames[$counter]." wrong format.</p>";
				die();
			}
		}
		elseif($counter == 5){
			if(strlen($inputArray[$counter]) !== 8){
				echo "<p class='AlreadyLoggedInText'>".$inputNames[$counter]." number is incorrect!</p>";
				die();
			}
		}
		elseif($counter == 6 || $counter == 7){
			if(strlen($inputArray[$counter]) > 20){
				echo "<p class='AlreadyLoggedInText'>".$inputNames[$counter]." is too long!</p>";
				die();
			}
		}
		else{
			if(strlen($inputArray[$counter]) > 30){
				echo "<p class='AlreadyLoggedInText'>".$inputNames[$counter]." is too long!</p>";
				die();
			}
			elseif(strlen($inputArray[$counter]) < 10){
				echo "<p class='AlreadyLoggedInText'>".$inputNames[$counter]." is too Short!</p>";
				die();
			}
			else{

			}
		}
	}
	// regular expressions + date checking
	$checkall = true;
	$checkall=$checkall && inputChecker($email,"/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i");
	$checkall=$checkall && inputChecker($password,"/^((?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s]|.*[_])).{10,29}$/"); //must have lower case, upper case, special char and number
	$checkall=$checkall && inputChecker($firstname,"/^[A-Za-z\s]+$/"); //only allow letters
	$checkall=$checkall && inputChecker($lastname,"/^[A-Za-z\s]+$/"); //only allow letters
	$checkall=$checkall && dateCheck($dateofbirth); 
	$checkall=$checkall && inputChecker($contact,"/(6|8|9)\d{7}/"); //only allow singaporean phone number
	$checkall=$checkall && inputChecker($department,"/^[A-Za-z\s]+$/"); //only allow letters
	$checkall=$checkall && inputChecker($occupation,"/^[A-Za-z\s]+$/"); //only allow letters
	if (!$checkall) {
		echo "<p class='AlreadyLoggedInText'>Error: Please check inputs.</p>";
		die();
	}
	// htmlspecialchars  (defence against XSS)
	$sanitizedEmail = htmlspecialchars($email);
	$sanitizedPassword = htmlspecialchars($password);
	$sanitizedFirstName = htmlspecialchars($firstname);
	$sanitizedLastName = htmlspecialchars($lastname);
	$sanitizedDateOfBirth = htmlspecialchars($dateofbirth);
	$sanitizedContact = htmlspecialchars($contact);
	$sanitizedDepartment = htmlspecialchars($department);
	$sanitizedOccupation = htmlspecialchars($occupation);
	//hashing password
	$hashed_password = password_hash($sanitizedPassword,PASSWORD_DEFAULT);
	$query=$con->prepare("INSERT INTO users (`email`,`password`,`first_name`,`last_name`,`date_of_birth`,`contact`,`department`,`occupation`) VALUES (?,?,?,?,?,?,?,?)");
	$query->bind_param('sssssiss', $sanitizedEmail, $hashed_password, $sanitizedFirstName, $sanitizedLastName, $sanitizedDateOfBirth, $sanitizedContact, $sanitizedDepartment,$sanitizedOccupation);
	if($query->execute()){ //executing query (processes and print the results)
		header("Location: http://localhost/SWAP-TP/userList.php");
		die();
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}

function edituser($email, $password,$firstname, $lastname, $dateofbirth, $contact, $department,$occupation){
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
	$inputArray = array($email,$password,$firstname,$lastname,$dateofbirth,$contact,$department,$occupation);
	$inputNames = array('Email','Password','First Name','Last Name','Date Of Birth','Contact','Department','Occupation');
	for($counter = 0; $counter < count($inputArray); $counter++){
		if($counter == 2 || $counter == 3){
			if(strlen($inputArray[$counter]) > 20){
				echo "<p class='AlreadyLoggedInText'>".$inputNames[$counter]." is too long!</p>";
				die();
			}
		}
		elseif($counter == 4){
			if(strlen($inputArray[$counter]) > 10){
				echo "<p class='AlreadyLoggedInText'>".$inputNames[$counter]." wrong format.</p>";
				die();
			}
		}
		elseif($counter == 5){
			if(strlen($inputArray[$counter]) !== 8){
				echo "<p class='AlreadyLoggedInText'>".$inputNames[$counter]." number is incorrect!</p>";
				die();
			}
		}
		elseif($counter == 6 || $counter == 7){
			if(strlen($inputArray[$counter]) > 20){
				echo "<p class='AlreadyLoggedInText'>".$inputNames[$counter]." is too long!</p>";
				die();
			}
		}
		else{
			if(strlen($inputArray[$counter]) > 30){
				echo "<p class='AlreadyLoggedInText'>".$inputNames[$counter]." is too long!</p>";
				die();
			}
			elseif(strlen($inputArray[$counter]) < 10){
				echo "<p class='AlreadyLoggedInText'>".$inputNames[$counter]." is too Short!</p>";
				die();
			}
			else{

			}
		}
	}

	// regular expressions + date checking
	$checkall = true;
	$checkall=$checkall && inputChecker($email,"/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i");
	$checkall=$checkall && inputChecker($password,"/^((?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s]|.*[_])).{10,29}$/"); //must have lower case, upper case, special char and number
	$checkall=$checkall && inputChecker($firstname,"/^[A-Za-z\s]+$/"); //only allow letters
	$checkall=$checkall && inputChecker($lastname,"/^[A-Za-z\s]+$/"); //only allow letters
	$checkall=$checkall && dateCheck($dateofbirth); 
	$checkall=$checkall && inputChecker($contact,"/(6|8|9)\d{7}/"); //only allow singaporean phone number
	$checkall=$checkall && inputChecker($department,"/^[A-Za-z\s]+$/"); //only allow letters
	$checkall=$checkall && inputChecker($occupation,"/^[A-Za-z\s]+$/"); //only allow letters
	if (!$checkall) {
		echo "<p class='AlreadyLoggedInText'>Error: Please check inputs.</p>";
		die();
	}
	else{
		echo "Validated";
	}
	// htmlspecialchars  (defence against XSS)
	$sanitizedEmail = htmlspecialchars($email);
	$sanitizedPassword = htmlspecialchars($password);
	$sanitizedFirstName = htmlspecialchars($firstname);
	$sanitizedLastName = htmlspecialchars($lastname);
	$sanitizedDateOfBirth = htmlspecialchars($dateofbirth);
	$sanitizedContact = htmlspecialchars($contact);
	$sanitizedDepartment = htmlspecialchars($department);
	$sanitizedOccupation = htmlspecialchars($occupation);
	//hashing password
	$hashed_password = password_hash($sanitizedPassword,PASSWORD_DEFAULT);
	$query=$con->prepare("UPDATE `users` SET `email`=?, `password`=?, `first_name`=?, `last_name`=?, `date_of_birth`=?, `contact`=?, `department`=?, `occupation`=? WHERE `ID` = ?");
	$query->bind_param('sssssissi', $sanitizedEmail, $hashed_password, $sanitizedFirstName, $sanitizedLastName, $sanitizedDateOfBirth, $sanitizedContact, $sanitizedDepartment,$sanitizedOccupation,$_SESSION['editUserId']);
	if($query->execute()){ //executing query (processes and print the results)
		unset($_SESSION['editUserId']);
		header("Location: http://localhost/SWAP-TP/userList.php");
		die();
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}
// deletion of accounts
function deleteItem($userId) {
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
	$query=$con->prepare("DELETE FROM `users` WHERE `ID`=?");
	$query->bind_param('s', $userId); //bind the parameters
	if($query->execute()){ //executing query (processes and print the results)
		header("Location: http://localhost/SWAP-TP/userList.php");
		die();
	}
	else{
		echo "Error Executing Query";
	}
}

function editProfile($firstName,$lastName,$contactNumber,$aboutMe){
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

	//check if new profile pic was uploaded
	if ($_FILES['uploadPic']['size'] != 0 || $_FILES['uploadPic']['error'] != 4 || is_uploaded_file($_FILES['uploadPic']['name'])){
		//change uploaded file's name to remove user-controlled factor
		$target_dir = "profilePic/";
		$filename = basename($_FILES["uploadPic"]["name"]);
		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); 
		$target_file = $target_dir . time() . "." . $ext;
		//input validation 1) file type validation.
		$allowed = array('png', 'jpg', 'jpeg','pdf');
		if (in_array($ext, $allowed)) {
			//input validation 2) check file size
			if ($_FILES["uploadPic"]["size"] > 1000000) {
				echo "File size is too large.";
				die();
			}
			//input validation 3) check if file exists
			if(file_exists($target_file)){
				echo "File already exists.";
				die();
			}
			// check if file was uploaded successfully
			if(!move_uploaded_file($_FILES["uploadPic"]["tmp_name"], $target_file)){
				echo "Sorry, an error occurred when uploading the file.";
				die();
			}
			else{
				$query=$con->prepare("UPDATE `users` SET `profilePic` = ? WHERE `ID` = ?");
				$query->bind_param('si', $target_file,$_SESSION['ID']);
				if($query->execute()){ //executing query (processes and print the results)
					printok("Successfully Uploaded."); 
				}
				else{
					printerror("Selecting $db_database",$con);
				}
			}
			
		}
		else{
			echo "Error: only JPG, JPEG, PNG & PDF files are allowed.";
			die();
		}
	}

	// min & max length input validation
	$inputArray = array($firstName,$lastName,$contactNumber,$aboutMe);
	$inputNames = array('First Name','Last Name','Contact','About Me');
	for($counter = 0; $counter < count($inputArray); $counter++){
		if($counter == 0 || $counter == 1){
			if(strlen($inputArray[$counter]) > 20){
				echo $inputNames[$counter]." is too long!";
				die();
			}
		}
		elseif($counter == 2){
			if(strlen($inputArray[$counter]) !== 8){
				echo $inputNames[$counter]." number is incorrect!<br>";
				die();
			}
		}
		elseif($counter == 3){
			if(strlen($inputArray[$counter]) > 500){
				echo $inputNames[$counter]." is too long!<br>";
				die();
			}
		}
	}

	// regular expressions + date checking
	$checkall = true;
	$checkall=$checkall && inputChecker($firstName,"/^[A-Za-z\s]+$/"); //only allow letters
	$checkall=$checkall && inputChecker($lastName,"/^[A-Za-z\s]+$/"); //only allow letters
	$checkall=$checkall && inputChecker($contactNumber,"/(6|8|9)\d{7}/"); //only allow singaporean phone number
	if (!$checkall) {
		echo "Error checking inputs<br>Please return to the registration form";
		die();
	}
	// htmlspecialchars  (defence against XSS)
	$sanitizedFirstName = htmlspecialchars($firstName);
	$sanitizedLastName = htmlspecialchars($lastName);
	$sanitizedContact = htmlspecialchars($contactNumber);
	$sanitizedAboutMe = htmlspecialchars($aboutMe);
	$query=$con->prepare("UPDATE `users` SET `first_name`=?, `last_name`=?, `contact`=?, `aboutMe`=? WHERE `ID` = ?");
	$query->bind_param('ssisi', $sanitizedFirstName, $sanitizedLastName, $sanitizedContact, $sanitizedAboutMe, $_SESSION['ID']);
	if($query->execute()){ //executing query (processes and print the results)
		header("Location: http://localhost/SWAP-TP/profile.php");
		die();
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}

function enableOTP(){
	require "config.php";
	require "Authentication.php";
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

	$otpStatus = 1;
	$query=$con->prepare("UPDATE `users` SET `otp` = ? WHERE `ID`=?");
	$query->bind_param('ii', $otpStatus, $_SESSION['ID']); //bind the parameters
	if($query->execute()){ //executing query (processes and print the results)
		logout();
	}
	else{
		echo "Error Executing Query";
	}
}

function disableOTP(){
	require "config.php";
	require "Authentication.php";
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

	$otpStatus = -1;
	$query=$con->prepare("UPDATE `users` SET `otp` = ? WHERE `ID`=?");
	$query->bind_param('ii', $otpStatus, $_SESSION['ID']); //bind the parameters
	if($query->execute()){ //executing query (processes and print the results)
		logout();
	}
	else{
		echo "Error Executing Query";
	}
}
?>
