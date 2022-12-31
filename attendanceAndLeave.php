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
    require "leaveFunctions.php";
    require "config.php";
    require "userFunctions.php";
    session_start();
    if (!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
       echo "Must be logged in.";
        die();
    }  
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
    date_default_timezone_set('Asia/Singapore');
    $counter = 0;
    $startDate = strtotime("2022/12/22");
    $endDate = time();
    // calculate total number of days for attendance
    $date1 = new DateTime('2022/12/22');
    $date2 = new DateTime('now');
    $days  = $date2->diff($date1)->format('%a');
    $query=$con->prepare("SELECT `status`,`Date` FROM `attendance` WHERE `userId` =?");
    $query->bind_param('i', $_SESSION['ID']); //bind the parameters
    $query->execute();
    $result = $query-> get_result();
    while($row = $result -> fetch_assoc()){
        $attendanceDate = strtotime($row['Date']);
        if($row['status'] === 1 && $attendanceDate > $startDate && $attendanceDate < $endDate){
            $counter += 1;
        } 
    }
    $percentage = round(($counter / $days) * 100,2);
    echo "<p style='text-align: center;'>Attendance: ".$counter."/".$days." (".$percentage."%)"."</p>";

    $query2=$con->prepare("SELECT `id`,`userId`,`Days`,`Reason`,`department`,`timeOfSubmission`,`status` FROM workleave WHERE userId = ?");
    $query2->bind_param('i', $_SESSION['ID']); //bind the parameters
    $query2->execute();
    $result2 = $query2-> get_result();
    echo "<div id='toggle1'><p style='text-align: center;'>Leave Requests:</p><table align='center' border='1'><tr>";
    echo
    "<th>id</th><th>userId</th><th>Days</th><th>Reason</th><th>Department</th><th>timeOfSubmission</th><th>status</th></tr>";
    while($row = $result2 -> fetch_assoc()){
        $id = $row['id'];
        $userId = $row['userId'];
        $Days = $row['Days'];
        $Reason = $row['Reason'];
        $department = $row['department'];
        $timeOfSubmission = $row['timeOfSubmission'];
        $status = $row['status'];
        if($status === -1){
            echo "<th>$id</th><th>$userId</th><th>$Days</th><th>$Reason</th><th>$department</th><th>$timeOfSubmission</th><th>$status</th><th><a href='editLeave.php?leaveEditing=true&LEID=".$id."'>Edit</a></th><th><a href='attendanceAndLeave.php?leaveDeleting=true&LDID=".$id."'>Delete</a></th></tr>";
        }
        elseif($status === 1 || $status === 0){
            echo "<th>$id</th><th>$userId</th><th>$Days</th><th>$Reason</th><th>$department</th><th>$timeOfSubmission</th><th>$status</th></tr>";
        }
        

    }
    echo "</table></div>";

    $query3=$con->prepare("SELECT `id`,`userId`,`mcFile`,`Days`,`department`,`timeOfSubmission`,`status` FROM medicalcertificate WHERE userId = ?");
    $query3->bind_param('i',$_SESSION['ID']);
    $query3->execute();
    $result3 = $query3-> get_result();
    echo "<div id='toggle2'><p style='text-align: center;'>MC Requests:</p><table align='center' border='1'><tr>";
    echo
    "<th>id</th><th>userId</th><th>mcFile</th><th>Days</th><th>Department</th><th>timeOfSubmission</th><th>status</th></tr>";
    while ($row = $result3->fetch_assoc()) {
        $id2 = $row['id'];
        $userId2 = $row['userId'];
        $mcFile2 = $row['mcFile'];
        $Days2 = $row['Days'];
        $department2 = $row['department'];
        $timeOfSubmission2 = $row['timeOfSubmission'];
        $status2 = $row['status'];
        if($status2 === -1){
            echo "<th>$id2</th><th>$userId2</th><th>$mcFile2</th><th>$Days2</th><th>$department2</th><th>$timeOfSubmission2</th><th>$status2</th><th><a href='editMc.php?mcEID=".$id2."'>Edit</a></th><th><a href='attendanceAndLeave.php?mcDeleting=true&MCDID=".$id2."'>Delete</a></th></th></tr>";
        }
        elseif($status2 === 0 || $status2 === 1){
            echo "<th>$id2</th><th>$userId2</th><th>$mcFile2</th><th>$Days2</th><th>$department2</th><th>$timeOfSubmission2</th><th>$status2</th></tr>";
        }
        
    }
    echo "</table></div>";

    if (isset($_GET['leaveEditing']) && $_GET['leaveEditing'] === 'true') {
        editLeaveRequest();
    }
    if (isset($_GET['leaveDeleting']) && $_GET['leaveDeleting'] === 'true') {
        deleteLeaveRequest();
    }
    if (isset($_GET['mcEditing']) && $_GET['mcEditing'] === 'true') {
        editMcRequest();
    }
    if (isset($_GET['mcDeleting']) && $_GET['mcDeleting'] === 'true') {
        deleteMcRequest();
    }
    //*list all leave requests and medical certificates uploaded*
    //*ALLOW user to edit or delete PENDING leave/MC requests*

    ?>
    <!-- <form action="submitMC.php" method="post" enctype="multipart/form-data" style="text-align: center;">
        <label for='mcDays'>Number of days for work leave:</label>
        <input type="number" id="mcDays" name="mcDays" min="1" max="60"><br>
        <label for='uploadMC'>Upload MC:</label>
        <input type="file" name="uploadMC">
        <input type="submit" value="Upload MC" name="Submit">
    </form> -->
</body>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://www.w3schools.com/lib/w3.js"></script> Include EVERY OTHER HTML Files to this file -->
    <!-- <script>
        //to bring in other HTML on the fly into this page
        w3.includeHTML();
    </script> -->
</html>