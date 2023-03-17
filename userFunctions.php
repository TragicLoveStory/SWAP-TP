<?php	// Creates the user table and setup accounts
use PragmaRX\Google2FA\Google2FA;

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
		header("Location: https://localhost/SWAP-TP/userList.php");
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
		header("Location: https://localhost/SWAP-TP/userList.php");
		die();
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}
// deletion of accounts
function deleteItem($userId,$csrfToken) {
	require "config.php";
	$elapsedTokenTime = time() - $_SESSION['tokenTime'];
    if(!isset($_SESSION['token'])){
        if(isset($_SESSION['tokenTime'])){
            unset($_SESSION['tokenTime']);
        }
        echo "Invalid.";
        die();
    }
    elseif(!isset($_SESSION['tokenTime'])){
        if(isset($_SESSION['token'])){
            unset($_SESSION['token']);
        }
        echo "Invalid.";
        die();
    }
    elseif($_SESSION['token'] != $csrfToken){
        echo "Invalid Request.";
        unset($_SESSION['token']);
        unset($_SESSION['tokenTime']);
        die();
    }
    elseif($elapsedTokenTime >= 300){
        echo "Request expired.";
        unset($_SESSION['token']);
        unset($_SESSION['tokenTime']);
        die();
    }
    else{
        unset($_SESSION['token']);
        unset($_SESSION['tokenTime']);
    }

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
		header("Location: https://localhost/SWAP-TP/userList.php");
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
		header("Location: https://localhost/SWAP-TP/profile.php");
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
	//else printok("Connecting to $db_hostname");

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
	//else printok("Connecting to $db_hostname");

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

function enable2FA(){
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
	//else printok("Connecting to $db_hostname");

	$otpStatus = 2;
	$query=$con->prepare("UPDATE `users` SET `otp` = ? WHERE `ID`=?");
	$query->bind_param('ii', $otpStatus, $_SESSION['ID']); //bind the parameters
	if($query->execute()){ //executing query (processes and print the results)
		addQRCode();
	}
	else{
		echo "Error Executing Query";
	}
}

function addQRCode(){
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

	$query=$con->prepare("SELECT * FROM `2fa` WHERE `userId` = ?");
	$query->bind_param('i', $_SESSION['ID']); //bind the parameters
	$query->execute();
	$result = $query-> get_result();
	$num_of_rows = $result->num_rows;
	if($num_of_rows == 0){
		$google2fa = new \PragmaRX\Google2FA\Google2FA();
		$secret = $google2fa->generateSecretKey();
		
		include_once 'mailFunctions.php';
		$recoveryString = generateRandomString();

		$query2=$con->prepare("INSERT INTO `2fa` (`userId`,`secret`,`recovery`) VALUES (?,?,?)");
		$query2->bind_param('iss',$_SESSION['ID'], $secret, $recoveryString);
		if($query2->execute()){
			$text = $google2fa->getQRCodeUrl(
				'TPAMC.com',
				$_SESSION['email'],
				$secret
			);
			$image_url = 'https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl='.$text;
			$_SESSION['2faURL'] = $image_url;
			$_SESSION['recoveryCode'] = $recoveryString;
			header("Location: https://localhost/SWAP-TP/qrCode.php");
			die();
			
		}
	}
	else{
		$google2fa = new \PragmaRX\Google2FA\Google2FA();
		$newSecret = $google2fa->generateSecretKey();

		include_once 'mailFunctions.php';
		$newRecoveryString = generateRandomString();

		$query3=$con->prepare("UPDATE `2fa` SET `secret` = ?, `recovery` = ? WHERE `userId`=?");
		$query3->bind_param('ssi', $newSecret, $newRecoveryString, $_SESSION['ID']); //bind the parameters
		if($query3->execute()){
			$text = $google2fa->getQRCodeUrl(
				'TPAMC.com',
				$_SESSION['email'],
				$newSecret
			);
			$image_url = 'https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl='.$text;
			$_SESSION['2faURL'] = $image_url;
			$_SESSION['recoveryCode'] = $newRecoveryString;
			header("Location: https://localhost/SWAP-TP/qrCode.php");
			die();
			
		}
	}
}

function disable2FA(){
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
	//else printok("Connecting to $db_hostname");

	$otpStatus = -1;
	$query=$con->prepare("UPDATE `users` SET `otp` = ? WHERE `ID`=?");
	$query->bind_param('ii', $otpStatus, $_SESSION['ID']); //bind the parameters
	if($query->execute()){ //executing query (processes and print the results)
		$query=$con->prepare("DELETE FROM `2fa` WHERE `userId` = ?");
		$query->bind_param('i',$_SESSION['ID']);
		if($query->execute()){
			logout();
		}
		
	}
	else{
		echo "Error Executing Query";
	}
}
function changePassword($currentPassword,$newPassword){
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
	$query=$con->prepare("SELECT `password` FROM `users` WHERE `ID` =?");
	$query->bind_param('i', $_SESSION['ID']); //bind the parameters
	if($query->execute()){
		$result = $query-> get_result();
		$row = $result -> fetch_assoc();
		if(!$row){
			echo "Error.";
			die();
		}
		elseif(empty($row['password']) || !password_verify($currentPassword,htmlspecialchars($row['password']))){
			echo "<p class='AlreadyLoggedInText'>Incorrect Current Password2.</p>";
			die();
		}
		else{
			// min & max length input validation
			if(strlen($newPassword) > 30){
				echo "<p class='AlreadyLoggedInText'>".$newPassword." is too long!.</p>";
				die();
			}
			elseif(strlen($newPassword) < 10){
				echo "<p class='AlreadyLoggedInText'>".$newPassword." is too short!.</p>";
				die();
			}
			// regular expressions
			$checkall = true;
			$checkall=$checkall && inputChecker($newPassword,"/^((?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s])).{10,29}$/"); //must have lower case, upper case, special char and number
			if (!$checkall) {
				echo "<p class='AlreadyLoggedInText'>Passwords must contain 1 Lowercase, Uppercase, Special Character, number and at least 10 characters long</p>";
				die();
			}
			// htmlspecialchars
			$sanitizedPasswordInput = htmlspecialchars($newPassword);
			//hashing password
			$hashed_password = password_hash($sanitizedPasswordInput,PASSWORD_DEFAULT);
			$query=$con->prepare("UPDATE `users` SET `password` = ? WHERE `ID`=?");
			$query->bind_param('si',$hashed_password, $_SESSION['ID']); //bind the parameters
			if($query->execute()){
				logout();
			}
			else{
				echo "Password change error.";
				die();
				//printerror("Selecting $db_database",$con);
			}
		}
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}
?>
