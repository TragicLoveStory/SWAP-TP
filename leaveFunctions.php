<?php
function submitMC($days,$startDate,$endDate){
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
    //check date inputs
    $checkall = true;
    $checkall=$checkall && dateCheck($startDate);
    $checkall=$checkall && dateCheck($endDate);  
    if (!$checkall) {
		echo "Error with start and end dates.";
		die();
	}
    //change uploaded file's name to remove user-controlled factor
    $target_dir = "files/";
    $filename = basename($_FILES["uploadMC"]["name"]);
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); 
    $target_file = $target_dir . time() . "." . $ext;
    // validate number of days requested
    if($days > 60 || $days < 1){
        echo "Incorrect number of days requested.";
        die();
    }
    //check if file was uploaded
    if ($_FILES['uploadMC']['size'] != 0 || $_FILES['uploadMC']['error'] != 4 || is_uploaded_file($_FILES['uploadMC']['name'])){

    }
    else{
        echo "File not uploaded.";
        die();
    }
    //input validation 1) file type validation.
    $allowed = array('png', 'jpg', 'jpeg','pdf');
    if (in_array($ext, $allowed)) {
        //input validation 2) check file size
        if ($_FILES["uploadMC"]["size"] > 1000000) {
            echo "File size is too large.";
            die();
        }
        //input validation 3) check if file exists
        if(file_exists($target_file)){
            echo "File already exists.";
            die();
        }
        // check if file was uploaded successfully
        if(!move_uploaded_file($_FILES["uploadMC"]["tmp_name"], $target_file)){
            echo "Sorry, an error occurred when uploading the file.";
            die();
        }
        else{
            //date_default_timezone_set('Singapore');
            //$date = date('y/m/d H:i:s', time());
            $query=$con->prepare("INSERT INTO `medicalcertificate` (`userId`,`mcFile`, `Days`,`startDate`,`endDate`,`department`) VALUES (?,?,?,?,?,?)");
            $query->bind_param('isisss', $_SESSION['ID'],$target_file,$days,$startDate,$endDate,$_SESSION['department']);
            if($query->execute()){ //executing query (processes and print the results)
                header("Location: https://localhost/SWAP-TP/attendanceAndLeave.php");
                die();
            }
            else{
                printerror("Selecting $db_database",$con);
            }
        }
        
    }
    else{
        echo "Error: only JPG, JPEG, PNG & PDF files are allowed.";
    }
}

function deleteMC($mcID){
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
    $query=$con->prepare("SELECT `mcFile` FROM `medicalcertificate` WHERE `id`=?");
    $query->bind_param('i', $mcID); //bind the parameters
    $query->execute();
    $result = $query-> get_result();
    $row = $result -> fetch_assoc();
    if(!$row){
        header("Location: https://localhost/SWAP-TP/mcList.php");
        die();
    }
    else{
        $fileName = basename("/".$row['mcFile']);
        rename($row['mcFile'],"archivedFiles/".$fileName);
        $query2=$con->prepare("DELETE FROM `medicalcertificate` WHERE `id`=?");
        $query2->bind_param('i', $mcID); //bind the parameters
        if($query2->execute()){ //executing query (processes and print the results)
            header("Location: https://localhost/SWAP-TP/mcList.php");
            die();
        }
        else{
            echo "Error Executing Query";
        }
    }  
}

function submitLeave($days,$startDate,$endDate,$reason){
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
    //else printok("Connecting to $db_hostname");
    // validate number of days requested
    if($days > 60 || $days < 1){
        echo "Incorrect number of days requested.";
        die();
    }
    //check date inputs
    $checkall = true;
    $checkall=$checkall && dateCheck($startDate);
    $checkall=$checkall && dateCheck($endDate);  
    if (!$checkall) {
		echo "Error with start and end dates.";
		die();
	}
    $sanitizedReason = htmlspecialchars($reason);
    $query=$con->prepare("INSERT INTO `workleave` (`userId`,`Days`,`startDate`,`endDate`,`Reason`,`department`) VALUES (?,?,?,?,?,?)");
    $query->bind_param('iissss', $_SESSION['ID'],$days,$startDate,$endDate,$reason,$_SESSION['department']);
    if($query->execute()){ //executing query (processes and print the results)
        header("Location: https://localhost/SWAP-TP/attendanceAndLeave.php");
        die();
    }
    else{
        printerror("Selecting $db_database",$con);
    }
}

function deleteLeave($leaveID){
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
    $query1=$con->prepare("DELETE FROM `workleave` WHERE `id`=?");
    $query1->bind_param('i', $leaveID); //bind the parameters
    if($query1->execute()){ //executing query (processes and print the results)
        header("Location: https://localhost/SWAP-TP/leaveList.php");
        die();
    }
    else{
        echo "Error Executing Query";
    }  
}

function denyLeaveRequest($leaveID){
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
    $deniedStatus = 0;
    $query=$con->prepare("UPDATE workleave SET `status`=? WHERE `id`=?");
    $query->bind_param('ii',$deniedStatus, $leaveID); //bind the parameters
    if($query->execute()){ //executing query (processes and print the results)
        header("Location: https://localhost/SWAP-TP/authoriseLeave.php");
        die();
    }
    else{
        echo "Error Executing Query";
    }
}

function denyMcRequest($MCID){
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
    $deniedStatus = 0;
    $query=$con->prepare("UPDATE medicalcertificate SET `status`=? WHERE `id`=?");
    $query->bind_param('ii',$deniedStatus, $MCID); //bind the parameters
    if($query->execute()){ //executing query (processes and print the results)
        header("Location: https://localhost/SWAP-TP/authoriseLeave.php");
        die();
    }
    else{
        echo "Error Executing Query";
    }
}

function approveLeaveRequest($leaveID,$userID,$startDate,$endDate,$totalDays){
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
    date_default_timezone_set('Asia/Singapore');
    $start = new DateTime(date('Y-m-d',$startDate));
    $end = new DateTime(date('Y-m-d',$endDate));
    $days  = $end->diff($start)->format('%a');
    $days+=1;
    if($totalDays != $days){
        echo "days not consistent.";
        echo $totalDays;
        echo $days;
        die();
    }
    $approvedStatus = 1;
    $query=$con->prepare("UPDATE workleave SET `status`=? WHERE `id`=?");
    $query->bind_param('ii',$approvedStatus, $leaveID); //bind the parameters
    if($query->execute()){ //executing query (processes and print the results)
        for($i = $start; $i <= $end; $i->modify('+1 day')){
            $iteratedDate = $i->format("Y-m-d");

            $query2=$con->prepare("SELECT `Date` FROM `attendance` WHERE `Date` LIKE ? AND `userId` = ?");
            $query2->bind_param('si', $iteratedDate,$userID); //bind the parameters
            $query2->execute();
            $result = $query2-> get_result();
            $row = $result -> fetch_assoc();
            if ($row) {
                echo "attendance already in db. ERROR";
                die();
            }
            else{
                $workAttendance = 1;
                $query3=$con->prepare("INSERT INTO `attendance` (`userId`,`status`,`Date`) VALUES (?,?,?)");
                $query3->bind_param('iis',$userID,$workAttendance,$iteratedDate);
                if($query3->execute()){

                }
                else{
                    echo "Error executing query";
                    die();
                }
            }     
        }
        header("Location: https://localhost/SWAP-TP/authoriseLeave.php");
        die();
    }
    else{
        echo "Error Executing Query";
    }
}

function approveMcRequest($MCID,$userID,$startDate,$endDate,$totalDays){
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
    date_default_timezone_set('Asia/Singapore');
    $start = new DateTime(date('Y-m-d',$startDate));
    $end = new DateTime(date('Y-m-d',$endDate));
    $days  = $end->diff($start)->format('%a');
    $days+=1;
    if($totalDays != $days){
        echo "days not consistent.<br>";
        echo $totalDays."<br>";
        echo $days;
        die();
    }
    $approvedStatus = 1;
    $query=$con->prepare("UPDATE medicalcertificate SET `status`=? WHERE `id`=?");
    $query->bind_param('ii',$approvedStatus, $MCID); //bind the parameters
    if($query->execute()){ //executing query (processes and print the results)
        for($i = $start; $i <= $end; $i->modify('+1 day')){
            $iteratedDate = $i->format("Y-m-d");

            $query2=$con->prepare("SELECT `Date` FROM `attendance` WHERE `Date` LIKE ? AND `userId` = ?");
            $query2->bind_param('si', $iteratedDate,$userID); //bind the parameters
            $query2->execute();
            $result = $query2-> get_result();
            $row = $result -> fetch_assoc();
            if ($row) {
                echo "attendance already in db. ERROR";
                die();
            }
            else{
                $workAttendance = 1;
                $query3=$con->prepare("INSERT INTO `attendance` (`userId`,`status`,`Date`) VALUES (?,?,?)");
                $query3->bind_param('iis',$userID,$workAttendance,$iteratedDate);
                if($query3->execute()){

                }
                else{
                    echo "Error executing query";
                    die();
                }
            }     
        }
        header("Location: https://localhost/SWAP-TP/authoriseLeave.php");
        die();
    }
    else{
        echo "Error Executing Query";
    }
}

function deleteLeaveRequest($leaveID){
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
    $query1=$con->prepare("DELETE FROM `workleave` WHERE `id`=?");
    $query1->bind_param('i', $leaveID); //bind the parameters
    if($query1->execute()){ //executing query (processes and print the results)
        header("Location: https://localhost/SWAP-TP/attendanceAndLeave.php");
        die();
    }
    else{
        echo "Error Executing Query";
    } 
}

function deleteMcRequest($MCID){
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
    $query1=$con->prepare("SELECT `mcFile` FROM `medicalcertificate` WHERE `id`=?");
    $query1->bind_param('i', $MCID); //bind the parameters
    if($query1->execute()){ //executing query (processes and print the results)
        $result = $query1-> get_result();
        $row = $result -> fetch_assoc();
        if(!$row){
            echo "Error.";
            header("Location: https://localhost/SWAP-TP/attendanceAndLeave.php");
            die();
        }
        else{
            $fileName = basename("/".$row['mcFile']);
            rename($row['mcFile'],"archivedFiles/".$fileName);
            $query2=$con->prepare("DELETE FROM `medicalcertificate` WHERE `id`=?");
            $query2->bind_param('i', $MCID); //bind the parameters
            if($query2->execute()){ //executing query (processes and print the results)
                header("Location: https://localhost/SWAP-TP/attendanceAndLeave.php");
                die();
            }
            else{
                echo "Error Executing Query";
            }
        }   
    }  
}

function editMC($days,$startDate,$endDate){
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
    //check date inputs
    $checkall = true;
    $checkall=$checkall && dateCheck($startDate);
    $checkall=$checkall && dateCheck($endDate);  
    if (!$checkall) {
		echo "Error with start and end dates.";
		die();
	}
    //change uploaded file's name to remove user-controlled factor
    $target_dir = "files/";
    $filename = basename($_FILES["editMC"]["name"]);
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); 
    $target_file = $target_dir . time() . "." . $ext;
    // validate number of days requested
    if($days > 60 || $days < 1){
        echo "Incorrect number of days requested.";
        die();
    }
    //check if file was uploaded
    if ($_FILES['editMC']['size'] != 0 || $_FILES['editMC']['error'] != 4 || is_uploaded_file($_FILES['editMC']['name'])){

    }
    else{
        echo "File not uploaded.";
        die();
    }
    //input validation 1) file type validation.
    $allowed = array('png', 'jpg', 'jpeg','pdf');
    if (in_array($ext, $allowed)) {
        //input validation 2) check file size
        if ($_FILES["editMC"]["size"] > 1000000) {
            echo "File size is too large.";
            die();
        }
        //input validation 3) check if file exists
        if(file_exists($target_file)){
            echo "File already exists.";
            die();
        }
        // check if file was uploaded successfully
        if(!move_uploaded_file($_FILES["editMC"]["tmp_name"], $target_file)){
            echo "Sorry, an error occurred when uploading the file.";
            die();
        }
        else{
            $query=$con->prepare("SELECT `mcFile` from medicalcertificate WHERE `id`=?");
            $query->bind_param('i',$_SESSION['MCID']);
            $query->execute();
            $result = $query-> get_result();
            $row = $result -> fetch_assoc();
            $fileName = basename("/".$row['mcFile']);
            rename($row['mcFile'],"archivedFiles/".$fileName);
            //date_default_timezone_set('Singapore');
            //$date = date('y/m/d H:i:s', time());
            $query2=$con->prepare("UPDATE medicalcertificate SET `mcFile`=?,`Days`=?, `startDate`=?, `endDate`=? WHERE `id`=?");
            $query2->bind_param('sissi',$target_file,$days,$startDate,$endDate,$_SESSION['MCID']);
            if($query2->execute()){ //executing query (processes and print the results)
                header("Location: https://localhost/SWAP-TP/attendanceAndLeave.php");
                die();
            }
            else{
                printerror("Selecting $db_database",$con);
            }
        }
        
    }
    else{
        echo "Error: only JPG, JPEG, PNG & PDF files are allowed.";
    }
}

function editLeave($days,$startDate,$endDate,$reason){
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
    // validate number of days requested
    if($days > 60 || $days < 1){
        echo "Incorrect number of days requested.";
        die();
    }
    $checkall = true;
    $checkall=$checkall && dateCheck($startDate);
    $checkall=$checkall && dateCheck($endDate);  
    if (!$checkall) {
		echo "Error with start and end dates.";
		die();
	}
    $sanitizedReason = htmlspecialchars($reason);
    $query=$con->prepare("UPDATE `workleave` SET `Days`=?,`startDate`=?,`endDate`=?,`Reason`=? WHERE `id`=?");
    $query->bind_param('isssi',$days,$startDate,$endDate,$reason,$_SESSION['leaveID']);
    if($query->execute()){ //executing query (processes and print the results)
        unset($_SESSION['leaveID']);
        header("Location: https://localhost/SWAP-TP/attendanceAndLeave.php");
        die();
    }
    else{
        printerror("Selecting $db_database",$con);
    }
}
// UPDATE ATTENDANCE TABLE WHEN MC/LEAVE IS PERMITTED OR DENIED (ADVANCED)
?>
