<?php
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

function addmc($fullname, $mcfile) {
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

    
    $query=$con->prepare("INSERT INTO medicalcertificate (`fullName`, `mcFile`) VALUES (?,?)");
	$query->bind_param('ss', $fullname,$mcfile);
	if($query->execute()){ //executing query (processes and print the results)
		// header("Location: http://localhost/SWAP-TP/userList.php");
		// die();
		printok("Closing connection");
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}

?>