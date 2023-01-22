<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body class="backgroundImage">
    <?php 
     require "config.php";
     require "Authentication.php";
     if (!isset($_COOKIE['nezha']) || !isset($_COOKIE['nali'])){
        echo "Access Forbidden.";
        die();
     }
    $key = "erioepb834759-0324523u45htyiow45hjiopwjh-90et0-239456";
    $initializationIV = "";
    $AES128_ECB="aes-128-ecb";
    $decryptedvalue = openssl_decrypt($_COOKIE['nali'], $AES128_ECB, $key, $options=0, $initializationIV);
     if(isset($_POST['Submit']) && $_POST['Submit'] === "Submit OTP"){
        if(strlen($_POST['otpInput']) != 6){
            echo "Error";
            die();
        }
        elseif($_POST['otpInput'] === $decryptedvalue){
            otpLogin($_POST['otpInput']);
        }
    }
    ?>
    <div class="container">
        <div class="loginForm">
            <form method="post" action="otp.php">
                <input type="text" name="otpInput" placeholder="Enter OTP Here" class="inputField">
                <input type="submit" value="Submit OTP" name="Submit" class="signInButton">
            </form>
        </div>
    </div>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<script src="https://www.w3schools.com/lib/w3.js"></script> <!-- Include EVERY OTHER HTML Files to this file-->
<script>
    //to bring in other HTML on the fly into this page
    w3.includeHTML();
</script>
</html>