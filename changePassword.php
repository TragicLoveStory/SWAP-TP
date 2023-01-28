<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<?php 
        session_start();
        require "config.php";
        require "userfunctions.php";
        if(!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
            echo "Only permitted for Users.";
            die();
        } 
        include "navbar.php";
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
        $errorMessage="";
        require_once "userFunctions.php";
        require_once "config.php";

        if(isset($_POST['confirmPasswordChanges']) && $_POST['confirmPasswordChanges'] === "Submit Changes"){
            if(!empty($_POST['currentPassword']) && !empty($_POST['newPassword']) && !empty($_POST['confirmPassword'])){
                if($_POST['newPassword'] == $_POST['confirmPassword']){
                    $query=$con->prepare("SELECT `password` FROM `users` WHERE `ID` =?");
                    $query->bind_param('i', $_SESSION['ID']); //bind the parameters
                    if($query->execute()){
                        $result = $query-> get_result();
                        $row = $result -> fetch_assoc();
                        if(!$row){
                            $errorMessage = "Error.";
                        }
                        elseif(empty($row['password']) || !password_verify($_POST['currentPassword'],htmlspecialchars($row['password']))){
                            $errorMessage="Incorrect Current Password.";
                        }
                        else{
                            changePassword($_POST['currentPassword'],$_POST['newPassword']);
                        }
                    }
                } 
                else{
                    $errorMessage="Error: New password and Confirm password are not identical.";
                }  
            }
            else{
                $errorMessage="Error: no field must be null.";
            }
    }
    ?>
    <div class="container" style="display: flex; flex-direction: column; align-items:center; margin-top: 12rem; margin-bottom: 12rem;">
        <p style='text-align: center;'><b>Change Password</b></p>
        <form action="changePassword.php" method="POST" enctype="multipart/form-data" style="text-align: center;">
            <label for='currentPassword'>Current Password:</label><br>
            <input type='password' name='currentPassword'><br>
            <label for='newPassword'>New Password:</label><br>
            <input type='password' name='newPassword'><br>
            <label for='confirmPassword'>Confirm New Password:</label><br>
            <input type='password' name='confirmPassword'><br>
            <input type="submit" name="confirmPasswordChanges" value="Submit Changes" style='margin-top: 1rem;'><br>
        </form>
        <p style='text-align: center; margin-top: 1rem;'><?= $errorMessage ?></p>
    </div>
    <?php include "footer.php"; ?>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<!-- Include every Bootstrap JavaScript plugin and dependency  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://www.w3schools.com/lib/w3.js"></script> <!-- Include EVERY OTHER HTML Files to this file-->
<script>
    //to bring in other HTML on the fly into this page
    w3.includeHTML();
</script>
</html>