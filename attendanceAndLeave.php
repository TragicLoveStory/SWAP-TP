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
    require "leaveFunctions.php";
    require "config.php";
    require "userFunctions.php";
    session_start();
    if (!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
       echo "Must be logged in.";
        die();
    }  

    if (isset($_POST['editLeave']) && $_POST['editLeave'] == "Edit"){
        if(!empty($_POST['editLeaveID'])){
            $_SESSION['leaveID'] = $_POST['editLeaveID'];
            header("Location: https://localhost/SWAP-TP/editLeave.php?leaveEditing=true");
            die();
        }
    }
    if (isset($_POST['editMC']) && $_POST['editMC'] == "Edit"){
        if(!empty($_POST['editMcID'])){
            $_SESSION['MCID'] = $_POST['editMcID'];
            header("Location: https://localhost/SWAP-TP/editMc.php?mcEditing=true");
            die();
        }
    }

    if (isset($_POST['deleteLeave']) && $_POST['deleteLeave'] === 'Delete') {
        if(!empty($_POST['deleteLeaveID'])){
            deleteLeaveRequest($_POST['deleteLeaveID']);
        }
    }
    if (isset($_POST['deleteMC']) && $_POST['deleteMC'] === 'Delete') {
        if(!empty($_POST['deleteMCID'])){
            deleteMcRequest($_POST['deleteMCID']);
        }
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
    //else printok("Connecting to $db_hostname");
    include 'navbar.php';
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
    echo "<p style='text-align: center; margin-top: 3%;'>Your Current Attendance:</p>";
    echo "<p style='text-align: center;'>Attendance: ".$counter."/".$days." (".$percentage."%)"."</p>";

    $query2=$con->prepare("SELECT `id`,`userId`,`Days`,`startDate`,`endDate`,`Reason`,`department`,`timeOfSubmission`,`status` FROM workleave WHERE userId = ?");
    $query2->bind_param('i', $_SESSION['ID']); //bind the parameters
    $query2->execute();
    $result2 = $query2-> get_result();
    echo "<div class='container forumTable' style='margin-top: 3%;'>
        <p style='text-align: center;'>Leave Requests:</p>
        <table class='forumTable2'>
            <tr><th>Leave ID</th><th>User Id</th><th>Number of Days</th><th>Start Date</th><th>End Date</th><th>Reason</th><th>Department</th><th>Time of Submission</th><th>status</th></tr>";
    while($row2 = $result2 -> fetch_assoc()){
        $id = $row2['id'];
        $userId = $row2['userId'];
        $Days = $row2['Days'];
        $startDate = $row2['startDate'];
        $endDate = $row2['endDate'];
        $Reason = $row2['Reason'];
        $department = $row2['department'];
        $timeOfSubmission = $row2['timeOfSubmission'];
        $status = $row2['status'];
        if($status === -1){
            echo "<td>$id</td><td>$userId</td><td>$Days</td><td>$startDate</td><td>$endDate</td><td>$Reason</td><td>$department</td><td>$timeOfSubmission</td><td>$status</td>
            <td>
                <form action='attendanceAndLeave.php' method='POST'>
                    <input type='hidden' name='editLeaveID' value=".$id.">
                    <input type='submit' name='editLeave' value='Edit'>
                </form>
            </td>
            <td>
                <form action='attendanceAndLeave.php' method='POST'>
                    <input type='hidden' name='deleteLeaveID' value=".$id.">
                    <input type='submit' name='deleteLeave' value='Delete'>
                </form>
            </td></tr>";
        }
        elseif($status === 1 || $status === 0){
            echo "<td>$id</td><td>$userId</td><td>$Days</td><td>$startDate</td><td>$endDate</td><td>$Reason</td><td>$department</td><td>$timeOfSubmission</td><td>$status</td></tr>";
        }
        

    }
    echo "</table></div>";

    $query3=$con->prepare("SELECT `id`,`userId`,`mcFile`,`Days`,`startDate`,`endDate`,`department`,`timeOfSubmission`,`status` FROM medicalcertificate WHERE userId = ?");
    $query3->bind_param('i',$_SESSION['ID']);
    $query3->execute();
    $result3 = $query3-> get_result();
    echo "<div class='container forumTable' style='margin-top: 3%;'>
        <p style='text-align: center;'>Medical Certificate(MC) Requests:</p>
        <table class='forumTable2' style='margin-bottom: 10rem;'>
            <tr><th>MC ID</th><th>User Id</th><th>MC(File)</th><th>Number of days</th><th>Start Date</th><th>End Date</th><th>Department</th><th>Time of Submission</th><th>status</th></tr>";
    while ($row3 = $result3->fetch_assoc()) {
        $id2 = $row3['id'];
        $userId2 = $row3['userId'];
        $mcFile2 = $row3['mcFile'];
        $Days2 = $row3['Days'];
        $startDate2 = $row3['startDate'];
        $endDate2 = $row3['endDate'];
        $department2 = $row3['department'];
        $timeOfSubmission2 = $row3['timeOfSubmission'];
        $status2 = $row3['status'];
        if($status2 === -1){
            echo "<td>$id2</td><td>$userId2</td><td>$mcFile2</td><td>$Days2</td><td>$startDate2</td><td>$endDate2</td><td>$department2</td><td>$timeOfSubmission2</td><td>$status2</td>
            <td>
                <form action='attendanceAndLeave.php' method='POST'>
                    <input type='hidden' name='editMcID' value=".$id2.">
                    <input type='submit' name='editMC' value='Edit'>
                </form>
            </td>
            <td>
                <form action='attendanceAndLeave.php' method='POST'>
                    <input type='hidden' name='deleteMCID' value=".$id2.">
                    <input type='submit' name='deleteMC' value='Delete'>
                </form>
            </td></tr>";
        }
        elseif($status2 === 0 || $status2 === 1){
            echo "<td>$id2</td><td>$userId2</td><td>$mcFile2</td><td>$Days2</td><td>$startDate2</td><td>$endDate2</td><td>$department2</td><td>$timeOfSubmission2</td><td>$status2</td></tr>";
        }
        
    }
    echo "</table></div>";
    include 'footer.php';
    ?>
    <!-- <form action="submitMC.php" method="post" enctype="multipart/form-data" style="text-align: center;">
        <label for='mcDays'>Number of days for work leave:</label>
        <input type="number" id="mcDays" name="mcDays" min="1" max="60"><br>
        <label for='uploadMC'>Upload MC:</label>
        <input type="file" name="uploadMC">
        <input type="submit" value="Upload MC" name="Submit">
    </form> -->
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