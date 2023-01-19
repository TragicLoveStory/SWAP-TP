<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> -->
</head>
<body>
    <?php 
    require "config.php";
    require "userFunctions.php";
    session_start();
    if (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && $_SESSION["role"]==="USER-ADMIN"){
        echo "Permitted for User Admins";
        //connection to internalhr database
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
        // loading of user details
        $query=$con->prepare("select * from users");
        $query->execute();
        $query->bind_result($id, $email, $password,$firstname, $lastname, $dateofbirth, $contact, $department,$occupation, $role, $status, $aboutMe, $profilePic, $otp);
        echo "<table align='center' border='1'><tr>";
        echo
        "<th>Id</th><th>Email</th><th>Password</th><th>First name</th><th>Last name</th><th>Date Of Birth</th><th>Contact</th><th>Department</th><th>Occupation</th><th>Role</th></tr>";
        while($query->fetch())
        {
            echo "<tr><th>$id</th><th>$email</th><th>$password</th><th>$firstname</th><th>$lastname</th><th>$dateofbirth</th><th>$contact</th><th>$department</th><th>$occupation</th><th>$role</th><th><a href='editAccount.php?editing=true&TheUserId=".$id."'>edit</a></th><th><a href='userList.php?deletion=true&TheUserId=".$id."'>delete</a></th></tr>";
        }
        echo "</table>";

        if (isset($_GET['deletion']) && $_GET['deletion'] === 'true') {
            deleteItem();
        }
    }
    else{
        echo "Only permitted for User Admins.";
        die();
    }
    ?>
    
</body>
<style>
        th{
            max-width: 200px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }
</style>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://www.w3schools.com/lib/w3.js"></script> Include EVERY OTHER HTML Files to this file -->
    <!-- <script>
        //to bring in other HTML on the fly into this page
        w3.includeHTML();
    </script> -->
</html>