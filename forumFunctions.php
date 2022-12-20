<?php 
// deletion of forum thread (administrator)
function deleteThread() {
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
    $query=$con->prepare("DELETE FROM `forum` WHERE `id`=?");
    $query->bind_param('i', $_GET['FD']); //bind the parameters
    if($query->execute()){ //executing query (processes and print the results)
        header("Location: http://localhost/SWAP-TP/Forum.php");
        die();
    }
    else{
        echo "Error Executing Query";
    }
}
//Archive/Locking of forum thread (administrator)
function archiveThread() {
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
    if($_GET['AID'] == 1){
        $threadStatus = 0;
    }
    elseif($_GET['AID'] == 0){
        $threadStatus = 1;
    }
    else{
        echo "Error: Forum Status Incorrect.";
    }
    $query=$con->prepare("UPDATE forum SET `status`=? WHERE `id`=?");
    $query->bind_param('ii', $threadStatus,$_GET['FD']); //bind the parameters
    if($query->execute()){ //executing query (processes and print the results)
        header("Location: http://localhost/SWAP-TP/Forum.php");
        die();
    }
    else{
        echo "Error Executing Query";
    }
}
// Creating Forum Thread
function createThread($title,$content){
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
    $query=$con->prepare("INSERT INTO forum (`userId`,`title`,`content`) VALUES (?,?,?)");
	$query->bind_param('iss', $_SESSION['ID'], $sanitizedTitle, $sanitizedContent);
	if($query->execute()){ //executing query (processes and print the results)
		header("Location: http://localhost/SWAP-TP/Forum.php");
		die();
		printok("Closing connection");
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}
// incresing view count on each click of thread
function viewCounter($viewCount,$forumID){
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

    $viewCount+=1;
    $query=$con->prepare("UPDATE `forum` SET `viewCount`=? WHERE `id`=?");
	$query->bind_param('ii', $viewCount,$forumID);
	if($query->execute()){ //executing query (processes and print the results)
        
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}
?>
