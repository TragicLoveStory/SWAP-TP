<?php
function submitMC(){
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
            date_default_timezone_set('Singapore');
            $date = date('y/m/d H:i:s', time());
            $query=$con->prepare("INSERT INTO `medicalcertificate` (`userId`,`mcFile`,`timeOfSubmission`) VALUES (?,?,?)");
            $query->bind_param('iss', $_SESSION['ID'],$target_file,$date);
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
?>
