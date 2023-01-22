<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
    <?php 
    require "config.php";
    require "userFunctions.php";
    require "leaveFunctions.php";
    session_start();
    if (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && $_SESSION["role"]==="LEAVE-ADMIN"){

        if (isset($_POST['deleteMc']) && $_POST['deleteMc'] === 'Delete') {
            if(!empty($_POST['deleteMcID'])){
                deleteMC($_POST['deleteMcID']);
            }
        }

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
            echo "<th>$id</th><th>$userId</th><th><img src='$mcFile' class='image'></th><th>$fileName</th><th>$Days</th><th>$timeOfSubmission</th><th>$status</th>
            <th>
                <form action='mcList.php' method='POST'>
                    <input type='hidden' name='deleteMcID' value=".$id.">
                    <input type='submit' name='deleteMc' value='Delete'>
                </form>
            </th></tr>";
        }
        echo "</table>";
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