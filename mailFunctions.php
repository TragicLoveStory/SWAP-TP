<?php 
function contactMail($subject,$body){
    $receiver = "ashwynoh@gmail.com";
    // $subject = "Email Test via PHP using Localhost";
    // $body = "Hi, there...This is a test email send from Localhost.";
    $sender = "From:parcelofjoy@gmail.com";
    if(mail($receiver, $subject, $body, $sender)){
        echo "<p class='AlreadyLoggedInText'>Email successfully sent to the HR Team.</p>";
        die();
    }else{
        echo "<p class='AlreadyLoggedInText'>Error: failed while sending mail</p>";
        die();
    }
}

function forgotPassword($email){
    $randomString = generateRandomString();
    $receiver = $email;
    date_default_timezone_set('Asia/Singapore');
    $currentTime = date("Y:m:d h:i:s");
    $subject = "Reset your password";
    $body = "We received a request for a password change at ".$currentTime.". Create a new password by clicking the link below.\r\nhttps://localhost/SWAP-TP/resetPassword.php?s=".$randomString;
    $sender = "From:parcelofjoy@gmail.com";
    if(mail($receiver, $subject, $body, $sender)){
        echo "<p class='AlreadyLoggedInText'>Email successfully sent to ".$receiver."</p>";
        insertString($randomString,$email);
    }else{
        echo "<p class='AlreadyLoggedInText'>Error: failed while sending mail</p>";
    }
}

function insertString($randomString,$email){
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
    $query=$con->prepare("INSERT INTO `forgotpassword` (`email`,`string`) VALUES (?,?)");
    $query->bind_param('ss',$email,$randomString);
    if($query->execute()){ //executing query (processes and print the results)
    }
    else{
        printerror("Selecting $db_database",$con);
    }
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateOTP() {
    $characters = '1357902468';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 6; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function resetPassword($randomString,$newPassword,$confirmPassword){
    if($confirmPassword !== $newPassword){
        echo "<p class='AlreadyLoggedInText'>Passwords Do not match.</p>";
		die();
    }
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
    date_default_timezone_set('Asia/Singapore');
    $query=$con->prepare("SELECT * FROM `forgotpassword` WHERE `string`=?");
    $query->bind_param('s',$randomString);
    $query->execute();
    $result = $query-> get_result();
    $row = $result -> fetch_assoc();
    if(!$row){
        echo "<p class='AlreadyLoggedInText'>Error: Invalid link.</p>";
        die();
    }
    $currentTime = time();
    $randomStringTime = strtotime($row['time']);
    $elapsedTime = $currentTime - $randomStringTime;
    if($elapsedTime >= 900){
        $query=$con->prepare("DELETE FROM `forgotpassword` WHERE `string`=?");
        $query->bind_param('s',$randomString);
        if($query->execute()){
            echo "<p class='AlreadyLoggedInText'>Error: Expired Link.</p>";
            die();
        }
    }
    else{
        $checkall = true;
        $checkall=$checkall && inputChecker($newPassword,"/^((?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s]|.*[_])).{10,29}$/");
        if (!$checkall) {
            echo "<p class='AlreadyLoggedInText'>Password requires at least:\r\n 1 Capital Letter\r\n 1 Special Character\r\n 1 number\r\n10 characters long</p>";
            die();
        }
        $sanitizedPassword = htmlspecialchars($newPassword);
        //hashing password
	    $hashed_password = password_hash($sanitizedPassword,PASSWORD_DEFAULT);
        $query2=$con->prepare("UPDATE `users` SET `password`=? WHERE `email`=?");
        $query2->bind_param('ss',$hashed_password,$row['email']);
        if($query2->execute()){
            $query3=$con->prepare("DELETE FROM `forgotpassword` WHERE `string`=?");
            $query3->bind_param('s',$randomString);
            if($query3->execute()){
                header("Location: https://localhost/SWAP-TP/loginForm.php");
                die();
            }
        }
    }

}

function sendOTP($email){
    date_default_timezone_set('Singapore');
    $generatedOTP = generateOTP();
    $receiver = $email;
    $currentTime = date("Y:m:d h:i:s");
    $subject = "OTP";
    $body = "Hello ".$email.", as OTP is enabled for your TPAMC account, we have sent an OTP for your recent login attempt at ".$currentTime." below.\r\n".$generatedOTP;
    $sender = "From:parcelofjoy@gmail.com";
    if(mail($receiver, $subject, $body, $sender)){
        //echo "Email sent successfully to $receiver";
        $key = "erioepb834759-0324523u45htyiow45hjiopwjh-90et0-239456";
        $initializationIV = "";
        $AES128_ECB="aes-128-ecb";
        $encryptedValue = openssl_encrypt($generatedOTP, $AES128_ECB, $key, $options=0, $initializationIV);
        setcookie("nali", $encryptedValue, [
            'expires' => time() + 1800,
            'path' => '/SWAP-TP',
            'domain' => '',
            'secure' => TRUE,
            'httponly' => TRUE,
            'samesite' => 'Strict'
        ]);
        header("Location: https://localhost/SWAP-TP/otp.php");
        die();
    }else{
        echo "Sorry, failed while sending mail!";
    }
}
?>