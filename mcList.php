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
    require "leaveFunctions.php";
    session_start();
    if (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && $_SESSION["role"]==="LEAVE-ADMIN"){
        echo "Permitted for Leave Admins";
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
        $query=$con->prepare("select `id`,`userId`,`mcFile`,`Days`,`timeOfSubmission`,`status`from medicalcertificate");
        $query->execute();
        $query->bind_result($id, $userId, $mcFile, $Days, $timeOfSubmission,$status);
        echo "<p>TESTING PLACING FETCH HTML AT SPECIFIC LOCATION.</p>";
        echo "<p>IF TABLE IS BELOW THIS TEXT, THE TEST IS SUCCESSFUL.</p>";
        echo "<table align='center' border='1'><tr>";
        echo
        "<th>id</th><th>userId</th><th>mcFile</th><th>mcFile</th><th>Days</th><th>timeOfSubmission</th><th>status</th></tr>";
        while($query->fetch()){
            $fileName = basename("/".$mcFile);
            echo "<th>$id</th><th>$userId</th><th><img src='$mcFile' class='image'></th><th>$fileName</th><th>$Days</th><th>$timeOfSubmission</th><th>$status</th><th><a href='mcList.php?deletion=true&mcID=".$id."'>Delete</a></th></tr>";
        }
        echo "</table>";

        if (isset($_GET['deletion']) && $_GET['deletion'] === 'true') {
            deleteMC();
        }
    }
    else{
        echo "Only permitted for Attendance & Leave Admins";
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
        .image{
            height: 70%;
            width: 70%;

        }
</style>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://www.w3schools.com/lib/w3.js"></script> Include EVERY OTHER HTML Files to this file -->
    <!-- <script>
        //to bring in other HTML on the fly into this page
        w3.includeHTML();
    </script> -->
</html>