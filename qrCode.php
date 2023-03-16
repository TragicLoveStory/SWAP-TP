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
    session_start();
    require "config.php";
    require "userfunctions.php";
    if(!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
        echo "Only permitted for Users.";
        die();
    }
    elseif(!isset($_SESSION['2faURL']) || !isset($_SESSION['recoveryCode'])){
        echo "Permission Denied.";
        die();
    }
    else{
        $imageURL = $_SESSION['2faURL'];
    }
    if(isset($_POST['Submit']) && $_POST['Submit'] === "Back to Login Page"){
        require 'Authentication.php';
        unset($_SESSION['2faURL']);
        logout();
    }
    ?>
    <div class="container qrFlex">
        <img src="<?= $imageURL ?>" style='margin-top: 3rem;'>
        <p style='color: white; margin-top: 1rem;'>Your recovery code is: <span style = 'color: #915F6D;'><?= $_SESSION['recoveryCode']; ?></span></p>
        <p style='color: white;'>Please keep a copy of the recovery code in case you lost access to your authenticator. For any inquiries or support, please contact tpamcIT@tp.edu.sg</p>
        <div class="loginForm" style='margin-top: 1rem;'>
            <form method="post" action="qrCode.php">
                <input type="submit" value="Back to Login Page" name="Submit" class="BackButton">
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