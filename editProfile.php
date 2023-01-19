<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
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
        require_once "userFunctions.php";
        require_once "config.php";
        $query=$con->prepare("SELECT * FROM `users` WHERE `ID` =?");
        $query->bind_param('i', $_SESSION['ID']); //bind the parameters
        if($query->execute()){
            $result = $query-> get_result();
            $row = $result -> fetch_assoc();
        }
        else{
            printerror("Selecting $db_database",$con);
        }

        if(isset($_POST['confirmChanges']) && $_POST['confirmChanges'] === "Submit Changes"){
            if(!empty($_POST['firstName']) && !empty($_POST['lastName']) && !empty($_POST['contactNumber']) &&!empty($_POST['aboutMe'])){
                editProfile($_POST['firstName'],$_POST['lastName'],$_POST['contactNumber'],$_POST['aboutMe']);
            }
            else{
                echo "No field must be null.";
            }
        }
    ?>
    <div class="container" style="display: flex; flex-direction: column; align-items:center;">
        <form action="editProfile.php" method="POST" enctype="multipart/form-data" style="text-align: center;">
            <img src="<?= $row['profilePic'] ?>" alt="Profile Picture" style="object-fit: cover; width: 150px; height: 150px; border-radius: 50%; border: 1.5px solid #656065;"><br>
            <input type="file" name="uploadPic"><br>
            <label for='firstName'>First name:</label><br>
            <input type='text' name='firstName' value= '<?= $row['first_name'] ?>'><br>
            <label for='lastName'>Last name:</label><br>
            <input type='text' name='lastName' value= '<?= $row['last_name'] ?>'><br>
            <label for='dateOfBirth'>Date Of Birth:</label><br>
            <input type='text' name='contactNumber' value= '<?= $row['contact'] ?>'><br>
            <label for='aboutMe'>About Me:</label><br>
            <textarea name="aboutMe" rows="8" cols="50" style="resize:none; white-space: pre-wrap;"><?= $row['aboutMe'] ?></textarea><br>
            <input type="submit" name="confirmChanges" value="Submit Changes"><br>
        </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://www.w3schools.com/lib/w3.js"></script> <!-- Include EVERY OTHER HTML Files to this file-->
<script>
    //to bring in other HTML on the fly into this page
    w3.includeHTML();
</script>
</html>