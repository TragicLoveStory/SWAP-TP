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
        if(isset($_POST['editProfile']) && $_POST['editProfile'] === "Edit Profile"){
            header("Location: https://localhost/SWAP-TP/editProfile.php");
		    die();
        }
        if(isset($_POST['changePassword']) && $_POST['changePassword'] === "Change Password"){
            header("Location: https://localhost/SWAP-TP/changePassword.php");
		    die();
        }
        if(isset($_POST['logout']) && $_POST['logout'] === "Logout"){
            include "Authentication.php";
            logout();
        }
        if(isset($_POST['enableOTP']) && $_POST['enableOTP'] === "Enable OTP"){
            enableOTP();
        }
        if(isset($_POST['disableOTP']) && $_POST['disableOTP'] === "Disable OTP"){
            disableOTP();
        }
        if(isset($_POST['enable2FA']) && $_POST['enable2FA'] === "Enable 2FA"){
            enable2FA();
        }
        if(isset($_POST['disable2FA']) && $_POST['disable2FA'] === "Disable 2FA"){
            disable2FA();
        }

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
        $query=$con->prepare("SELECT * FROM `users` WHERE `ID` =?");
        $query->bind_param('i', $_SESSION['ID']); //bind the parameters
        if($query->execute()){
            $result = $query-> get_result();
            $row = $result -> fetch_assoc();
            if($row['otp'] == 1){
                $otpStatus = "Enabled";
            }
            else{
                $otpStatus = "Disabled";
            }

            if($row['otp'] == 2){
                $TwoStatus = "Enabled";
            }
            else{
                $TwoStatus = "Disabled";
            }
        }
        else{
            printerror("Selecting $db_database",$con);
        }
    ?>
    <div class="container" style="display: flex; flex-direction: column; align-items:center;">
        <img src="<?= $row['profilePic'] ?>" alt="Profile Picture" style="object-fit: cover; width: 150px; height: 150px; border-radius: 50%; border: 1.5px solid #656065; margin-bottom: 3%">
        <p>Email: <?= $row['email']; ?></p>
        <p>Name: <?= $row['first_name']." ".$row['last_name']; ?></p>
        <p>Date Of Birth: <?= $row['date_of_birth'] ?></p>
        <p>Contact Number: <?= $row['contact'] ?></p>
        <p>About me: <?= $row['aboutMe'] ?></p>
        <?php if($row['occupation'] == "MANAGER" && $row['role'] != "LEAVE-ADMIN") : ?>
            <p>Occupation: Manager</p> 
            <a href='authoriseLeave.php'>Authorise Leave & MC requests here</a>
        <?php endif; ?>

        <?php if($row['role'] == "USER-ADMIN") : ?> 
            <p>Role: User Admin</p> 
            <a href='userList.php'>Manage user accounts here:</a>
        <?php elseif($row['role'] == "FORUM-ADMIN") : ?> 
            <p>Role: Forum Admin</p>
            <a href='Forum.php'>Manage forum threads & comments here:</a>
            <a href='Safety.php'>Manage workplace safety contents here:</a>
        <?php elseif($row['role'] == "LEAVE-ADMIN") : ?> 
            <p>Role: Leave Admin</p>
            <a href='mcList.php'>View All medical certificates submitted here:</a>
            <a href='leaveList.php'>View All leave requests submitted here:</a>
            <a href='authoriseLeave.php'>Authorise Leave & MC requests here</a>
        <?php endif; ?>

        <form action="profile.php" method="POST" style="text-align: center; margin-top: 1rem;">
            <input type="submit" name="editProfile" value="Edit Profile"><br><br>
        </form>
        <form action="profile.php" method="POST" style="text-align: center;">
            <input type="submit" name="changePassword" value="Change Password"><br><br>
        </form>
        <form action="profile.php" method="POST" style="text-align: center;">
            <input type="submit" name="logout" value="Logout"><br><br>
        </form>

        <?php if($otpStatus == "Enabled") : ?> 
            <p>OTP: <?= $otpStatus ?></p>
            <form action="profile.php" method="POST" style="text-align: center;">
                <input type="submit" name="disableOTP" value="Disable OTP">
            </form>
        <?php else : ?>
            <p>OTP: <?= $otpStatus ?></p>
            <form action="profile.php" method="POST" style="text-align: center;">
                <input type="submit" name="enableOTP" value="Enable OTP">
            </form>
        <?php endif; ?>

        <?php if($TwoStatus == "Enabled") : ?> 
            <p style='margin-top: 1rem;'>2FA: <?= $TwoStatus ?></p>
            <form action="profile.php" method="POST" style="text-align: center;">
                <input type="submit" name="disable2FA" value="Disable 2FA">
            </form>
        <?php else : ?>
            <p style='margin-top: 1rem;'>2FA: <?= $TwoStatus ?></p>
            <form action="profile.php" method="POST" style="text-align: center;">
                <input type="submit" name="enable2FA" value="Enable 2FA">
            </form>
        <?php endif; ?>

    </div>
    <?php include "footer.php" ?>
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