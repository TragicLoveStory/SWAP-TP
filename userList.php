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

        if (isset($_POST['editUser']) && $_POST['editUser'] === 'Edit') {
            if(!empty($_POST['editUserID'])){
                $_SESSION['editUserId'] = $_POST['editUserID'];
                header("Location: http://localhost/SWAP-TP/editAccount.php?editing=true");
                die();
            }
        }
        if (isset($_POST['deleteUser']) && $_POST['deleteUser'] === 'Delete') {
            if(!empty($_POST['deleteUserID'])){
                deleteItem($_POST['deleteUserID']);
            }
        }

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
            echo "<tr><th>$id</th><th>$email</th><th>$password</th><th>$firstname</th><th>$lastname</th><th>$dateofbirth</th><th>$contact</th><th>$department</th><th>$occupation</th><th>$role</th>
            <th>
                <form action='userList.php' method='POST'>
                    <input type='hidden' name='editUserID' value=".$id.">
                    <input type='submit' name='editUser' value='Edit'>
                </form>
            </th>
            <th>
                <form action='userList.php' method='POST'>
                    <input type='hidden' name='deleteUserID' value=".$id.">
                    <input type='submit' name='deleteUser' value='Delete'>
                </form>
            </th></tr>";
        }
        echo "</table>";
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