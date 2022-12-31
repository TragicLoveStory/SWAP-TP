<?php
function submitMC($days){
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
            $query=$con->prepare("INSERT INTO `medicalcertificate` (`userId`,`mcFile`, `Days`,`department`) VALUES (?,?,?,?)");
            $query->bind_param('isis', $_SESSION['ID'],$target_file,$days,$_SESSION['department']);
            if($query->execute()){ //executing query (processes and print the results)
                // header("Location: http://localhost/SWAP-TP/SubmitMC.php");
                // die();
                printok("Successfully Uploaded."); 
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

function deleteMC(){
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
    $query->bind_param('i', $_GET['mcID']); //bind the parameters
    $query->execute();
    $result = $query-> get_result();
    $row = $result -> fetch_assoc();
    if(!$row){
        header("Location: http://localhost/SWAP-TP/mcList.php");
        die();
    }
    else{
        unlink($row['mcFile']);
        $query2=$con->prepare("DELETE FROM `medicalcertificate` WHERE `id`=?");
        $query2->bind_param('i', $_GET['mcID']); //bind the parameters
        if($query2->execute()){ //executing query (processes and print the results)
            header("Location: http://localhost/SWAP-TP/mcList.php");
            die();
        }
        else{
            echo "Error Executing Query";
        }
    }  
}

function submitLeave($days,$reason){
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
    // validate number of days requested
    if($days > 60 || $days < 1){
        echo "Incorrect number of days requested.";
        die();
    }
    $sanitizedReason = htmlspecialchars($reason);
    $query=$con->prepare("INSERT INTO `workleave` (`userId`,`Days`,`Reason`,`department`) VALUES (?,?,?,?)");
    $query->bind_param('iiss', $_SESSION['ID'],$days,$reason,$_SESSION['department']);
    if($query->execute()){ //executing query (processes and print the results)
        // header("Location: http://localhost/SWAP-TP/SubmitMC.php");
        // die();
        printok("Successfully Uploaded."); 
    }
    else{
        printerror("Selecting $db_database",$con);
    }
}

function deleteLeave(){
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
    $query1->bind_param('i', $_GET['leaveID']); //bind the parameters
    if($query1->execute()){ //executing query (processes and print the results)
        header("Location: http://localhost/SWAP-TP/leaveList.php");
        die();
    }
    else{
        echo "Error Executing Query";
    }  
}

function denyLeaveRequest(){
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
    $query->bind_param('ii',$deniedStatus, $_GET['DLID']); //bind the parameters
    if($query->execute()){ //executing query (processes and print the results)
        header("Location: http://localhost/SWAP-TP/authoriseLeave.php");
        die();
    }
    else{
        echo "Error Executing Query";
    }
}

function denyMcRequest(){
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
    $query->bind_param('ii',$deniedStatus, $_GET['DMCID']); //bind the parameters
    if($query->execute()){ //executing query (processes and print the results)
        header("Location: http://localhost/SWAP-TP/authoriseLeave.php");
        die();
    }
    else{
        echo "Error Executing Query";
    }
}

function approveLeaveRequest(){
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
    $approvedStatus = 1;
    $query=$con->prepare("UPDATE workleave SET `status`=? WHERE `id`=?");
    $query->bind_param('ii',$approvedStatus, $_GET['ALID']); //bind the parameters
    if($query->execute()){ //executing query (processes and print the results)
        header("Location: http://localhost/SWAP-TP/authoriseLeave.php");
        die();
    }
    else{
        echo "Error Executing Query";
    }
}

function approveMcRequest(){
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
    $approvedStatus = 1;
    $query=$con->prepare("UPDATE medicalcertificate SET `status`=? WHERE `id`=?");
    $query->bind_param('ii',$approvedStatus, $_GET['AMCID']); //bind the parameters
    if($query->execute()){ //executing query (processes and print the results)
        header("Location: http://localhost/SWAP-TP/authoriseLeave.php");
        die();
    }
    else{
        echo "Error Executing Query";
    }
}

function deleteLeaveRequest(){
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
    $query1->bind_param('i', $_GET['LDID']); //bind the parameters
    if($query1->execute()){ //executing query (processes and print the results)
        header("Location: http://localhost/SWAP-TP/attendanceAndLeave.php");
        die();
    }
    else{
        echo "Error Executing Query";
    } 
}

function deleteMcRequest(){
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
    $query1=$con->prepare("DELETE FROM `medicalcertificate` WHERE `id`=?");
    $query1->bind_param('i', $_GET['MCDID']); //bind the parameters
    if($query1->execute()){ //executing query (processes and print the results)
        header("Location: http://localhost/SWAP-TP/attendanceAndLeave.php");
        die();
    }
    else{
        echo "Error Executing Query";
    } 
}

function editMC($days){
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
            $query->bind_param('i',$_GET['mcEID']);
            $query->execute();
            $result = $query-> get_result();
            $row = $result -> fetch_assoc();
            $fileName = basename("/".$row['mcFile']);
            rename($row['mcFile'],"archivedFiles/".$fileName);
            //date_default_timezone_set('Singapore');
            //$date = date('y/m/d H:i:s', time());
            $query2=$con->prepare("UPDATE medicalcertificate SET `mcFile`=?,`Days`=? WHERE `id`=?");
            $query2->bind_param('sii',$target_file,$days,$_GET['mcEID']);
            if($query2->execute()){ //executing query (processes and print the results)
                header("Location: http://localhost/SWAP-TP/attendanceAndLeave.php");
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

function editLeave($days,$reason){
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
    $sanitizedReason = htmlspecialchars($reason);
    $query=$con->prepare("UPDATE `workleave` SET `Days`=?,`Reason`=? WHERE `id`=?");
    $query->bind_param('isi',$days,$reason,$_GET['LEID']);
    if($query->execute()){ //executing query (processes and print the results)
        header("Location: http://localhost/SWAP-TP/attendanceAndLeave.php");
        die();
    }
    else{
        printerror("Selecting $db_database",$con);
    }
}
// LEFT WITH EDIT LEAVE REQUESTS
// UPDATE ATTENDANCE TABLE WHEN MC/LEAVE IS PERMITTED OR DENIED (ADVANCED)
?>
