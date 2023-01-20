<?php 
function createSafetyThread($title,$content,$videoLink){
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
    //input validation
	$sanitizedTitle = htmlspecialchars($title);
    $sanitizedContent = htmlspecialchars($content);
    $sanitizedVideoLink = htmlspecialchars($videoLink);
    $query=$con->prepare("INSERT INTO workplacesafety (`safetyTitle`,`safetyContent`,`videoLink`) VALUES (?,?,?)");
	$query->bind_param('sss',$sanitizedTitle, $sanitizedContent, $sanitizedVideoLink);
	if($query->execute()){ //executing query (processes and print the results)
		header("Location: http://localhost/SWAP-TP/Safety.php");
		die();
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}
//edit safety forum threads
function editSafetyThread($title,$content,$videoLink){
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
    //input validation
	$sanitizedTitle = htmlspecialchars($title);
    $sanitizedContent = htmlspecialchars($content);
    $sanitizedVideoLink = htmlspecialchars($videoLink);
    //SQL statement
    date_default_timezone_set('Singapore');
    $date = date('Y/m/d H:i:s', time());
    $query=$con->prepare("UPDATE `workplacesafety` SET `safetyTitle`=?, `safetyContent`=?, `videoLink`=?,`lastEdited` =? WHERE `id`=?");
	$query->bind_param('ssssi', $sanitizedTitle,$sanitizedContent,$sanitizedVideoLink,$date,$_SESSION['safetyID']);
	if($query->execute()){ //executing query (processes and print the results)
		unset($_SESSION['safetyID']);
        header("Location: http://localhost/SWAP-TP/Safety.php");
        die();
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}
//deleting safety forum threads
function deleteSafety($safetyID){
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
    $query=$con->prepare("DELETE FROM `workplacesafety` WHERE `id`=?");
    $query->bind_param('i', $safetyID); //bind the parameters
    if($query->execute()){ //executing query (processes and print the results)
        header("Location: http://localhost/SWAP-TP/Safety.php");
        die();
    }
    else{
        echo "Error Executing Query";
    }
}
?>