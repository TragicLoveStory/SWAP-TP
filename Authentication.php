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

    echo "testing";
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

    $query=$con->prepare("SELECT `ID`,`email`,`password`,`first_name`,`last_name`,`date_of_birth`,`contact`,`department`,`role` from users WHERE `email`=? AND `password`=?");
    $query->bind_param('ss',$email,$password);
    if($query->execute()){ //executing query (processes and print the results)
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        if(empty($row['email']) || empty($row['password']) || $row['email'] !== $email || $row['password'] !== $password){
            printerror("Wrong Login Credentials",$con);
        }
        else{
            printok("Login Successful");
			//session_set_cookie_params(30*24*60*60,"/SWAP-TP", "",TRUE,TRUE); //this is non strict (non same site only)
			session_set_cookie_params([
				'lifetime' => '86400',
				'path' => '/SWAP-TP',
				'domain' => '',
				'secure' => TRUE,
				'httponly' => TRUE,
				'samesite' => 'Strict'
			]);
            session_start();
	        printok("Started session"); //creates/resumes session
            $_SESSION["ID"]=$row['ID']; //adds email variable into session (form of keypair/hash map)
            $_SESSION["role"]=$row['role'];
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
	header("Location: http://localhost/SWAP-TP/foo.php");
	die();
}
?>