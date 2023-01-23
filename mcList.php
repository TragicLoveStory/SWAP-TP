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
    require "config.php";
    require "userFunctions.php";
    require "leaveFunctions.php";
    session_start();
    if (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && $_SESSION["role"]==="LEAVE-ADMIN"){

        if (isset($_POST['deleteMC']) && $_POST['deleteMC'] === 'Delete') {
            if(!empty($_POST['deleteMcID'])){
                deleteMC($_POST['deleteMcID']);
            }
        }

        //echo "Permitted for Leave Admins";
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
        //else printok("Connecting to $db_hostname");
        include 'navbar.php';
        $query=$con->prepare("select `id`,`userId`,`mcFile`,`Days`,`timeOfSubmission`,`status`from medicalcertificate");
        $query->execute();
        $query->bind_result($id, $userId, $mcFile, $Days, $timeOfSubmission,$status);
        //echo "<p>TESTING PLACING FETCH HTML AT SPECIFIC LOCATION.</p>";
        //echo "<p>IF TABLE IS BELOW THIS TEXT, THE TEST IS SUCCESSFUL.</p>";
        $result = $query->get_result();
        echo "<div class ='container listingTable'>
        <p>Table that contains the application for Medical Certificate by employees and to list whether Medical Certificate is approved.</p>
        <table class='listingTable2'>
            <tr><th>MC ID</th><th>User ID</th><th>MC(Image)</th><th>MC(File)</th><th>Days</th><th>Time of Submission</th><th>status</th><th>Delete</th></tr>";
        while($row = $result->fetch_assoc()){
        $id = $row['id'];
        $userId = $row['userId'];
        $mcFile = $row['mcFile'];
        $fileName = basename("/".$mcFile);
        $Days = $row['Days'];
        $timeOfSubmission = $row['timeOfSubmission'];
        $status = $row['status'];
        echo "<tr><td>$id</td><td>$userId</td><td><img src='$mcFile' class='image'></td><td>$fileName</td><td>$Days</td><td>$timeOfSubmission</td><td>$status</td>
              <td>
                <form action='mcList.php' method='POST'>
                    <input type='hidden' name='deleteMcID' value=".$id.">
                    <input type='submit' name='deleteMC' value='Delete'>
                </form>
              </td></tr>";
        }
        echo "</table>";
        echo "<br>";
        include 'footer.php';
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