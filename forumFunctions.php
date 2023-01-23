<?php 
// deletion of forum thread (administrator)
function deleteThread($forumID) {
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
    $query->bind_param('i', $forumID); //bind the parameters
    if($query->execute()){ //executing query (processes and print the results)
        header("Location: https://localhost/SWAP-TP/Forum.php");
        die();
    }
    else{
        echo "Error Executing Query";
    }
}
//Archive/Locking of forum thread (administrator)
function archiveThread($archiveStatus,$forumID) {
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
    if($archiveStatus == 1){
        $threadStatus = 0;
    }
    elseif($archiveStatus == 0){
        $threadStatus = 1;
    }
    else{
        echo "Error: Forum Status Incorrect.";
    }
    $query=$con->prepare("UPDATE forum SET `status`=? WHERE `id`=?");
    $query->bind_param('ii', $threadStatus,$forumID); //bind the parameters
    if($query->execute()){ //executing query (processes and print the results)
        header("Location: https://localhost/SWAP-TP/Forum.php");
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
		header("Location: https://localhost/SWAP-TP/Forum.php");
		die();
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
// edit forum threads
function editThread($title,$content,$forumID){
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
    //SQL statement
    date_default_timezone_set('Asia/Singapore');
    $date = date('Y/m/d H:i:s', time());
    $query=$con->prepare("UPDATE `forum` SET `title`=?, `content`=?,`lastEdited` =? WHERE `id`=?");
	$query->bind_param('sssi', $sanitizedTitle,$sanitizedContent,$date,$forumID);
	if($query->execute()){ //executing query (processes and print the results)
        unset($_SESSION['forumID']);
        header("Location: https://localhost/SWAP-TP/forumThread.php?forumID=$forumID");
        die();
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}

function createComment($comment){
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
    $sanitizedComment = htmlspecialchars($comment);
    $forumId = $_SESSION['forumId'];
    $query=$con->prepare("INSERT INTO `comments` (`userId`,`forumId`,`comment`) VALUES (?,?,?)");
	$query->bind_param('iis', $_SESSION['ID'], $_SESSION['forumId'], $sanitizedComment);
	if($query->execute()){ //executing query (processes and print the results)
        unset($_SESSION["forumId"]);
		header("Location: https://localhost/SWAP-TP/forumThread.php?forumID=".$forumId);
		die();
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}

function editComment($comment,$commentID,$forumId){
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
    $sanitizedComment = htmlspecialchars($comment);
    $query=$con->prepare("UPDATE `comments` SET `comment`=? WHERE `ID`=?");
	$query->bind_param('si', $sanitizedComment, $commentID);
	if($query->execute()){ //executing query (processes and print the results)
        unset($_SESSION["commentId"]);
		header("Location: https://localhost/SWAP-TP/forumThread.php?forumID=".$forumId);
		die();
	}
	else{
		printerror("Selecting $db_database",$con);
	}
}

function deleteComment($commentID,$forumID){
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
    $query=$con->prepare("DELETE FROM `comments` WHERE `ID`=?");
    $query->bind_param('i', $commentID); //bind the parameters
    if($query->execute()){ //executing query (processes and print the results)
        header("Location: https://localhost/SWAP-TP/forumThread.php?forumID=".$forumID);
        die();
    }
    else{
        echo "Error Executing Query";
    }
}

function likeComment($commentID,$forumID){
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

    // check if user already liked/disliked
    $query=$con->prepare("SELECT `id`,`commentId`,`userId`,`status` FROM `commentlikes` WHERE `commentId` = ? AND `userId` = ?");
    $query->bind_param('ii',$commentID,$_SESSION['ID']);
    if($query->execute()){ //executing query (processes and print the results)
        $result = $query-> get_result();
        $row = $result -> fetch_assoc();
        if ($row) {
            if($row['status'] === 1){
                // user already liked, remove the like
                $query2=$con->prepare("DELETE FROM `commentlikes` WHERE `commentId` = ? AND `userId` = ?");
                $query2->bind_param('ii', $commentID, $_SESSION['ID']);
                if($query2->execute()){
                    header("Location: https://localhost/SWAP-TP/forumThread.php?forumID=".$forumID);
                    die();
                }
            }
            else{
                // user disliked, change it to like
                $liking = 1;
                $query3=$con->prepare("UPDATE `commentlikes` SET `status`=? WHERE `commentId` = ? AND `userId` = ?");
                $query3->bind_param('iii', $liking, $commentID, $_SESSION['ID']);
                if($query3->execute()){
                    header("Location: https://localhost/SWAP-TP/forumThread.php?forumID=".$forumID);
                    die();
                }
            }
        }
        else{
            // user has not liked nor dislike, insert accordingly
            $liking = 1;
            $query4=$con->prepare("INSERT INTO `commentlikes` (`commentId`,`userId`,`status`) VALUES (?,?,?)");
            $query4->bind_param('iii', $commentID, $_SESSION['ID'], $liking);
            if($query4->execute()){
                header("Location: https://localhost/SWAP-TP/forumThread.php?forumID=".$forumID);
                die();
            }
        }
    }
    else{
        printerror("Selecting $db_database",$con);
    }
}

function dislikeComment($commentID,$forumID){
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

    // check if user already liked/disliked
    $query=$con->prepare("SELECT `id`,`commentId`,`userId`,`status` FROM `commentlikes` WHERE `commentId` = ? AND `userId` = ?");
    $query->bind_param('ii',$commentID,$_SESSION['ID']);
    if($query->execute()){ //executing query (processes and print the results)
        $result = $query-> get_result();
        $row = $result -> fetch_assoc();
        if ($row) {
            if($row['status'] === 0){
                $query2=$con->prepare("DELETE FROM `commentlikes` WHERE `commentId` = ? AND `userId` = ?");
                $query2->bind_param('ii', $commentID, $_SESSION['ID']);
                if($query2->execute()){
                    header("Location: https://localhost/SWAP-TP/forumThread.php?forumID=".$forumID);
                    die();
                }
            }
            $disliking = 0;
            $query3=$con->prepare("UPDATE `commentlikes` SET `status`=? WHERE `commentId` = ? AND `userId` = ?");
            $query3->bind_param('iii', $disliking, $commentID, $_SESSION['ID']);
            if($query3->execute()){
                header("Location: https://localhost/SWAP-TP/forumThread.php?forumID=".$forumID);
                die();
            }
        }
        else{
            $disliking = 0;
            $query4=$con->prepare("INSERT INTO `commentlikes` (`commentId`,`userId`,`status`) VALUES (?,?,?)");
            $query4->bind_param('iii', $commentID, $_SESSION['ID'], $disliking);
            if($query4->execute()){
                header("Location: https://localhost/SWAP-TP/forumThread.php?forumID=".$forumID);
                die();
            }
        }
    }
    else{
        printerror("Selecting $db_database",$con);
    }
}
?>
