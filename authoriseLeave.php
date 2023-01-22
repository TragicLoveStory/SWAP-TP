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
    <button onclick="showMC();">Show MC Requests:</button>
    <button onclick="showLeave();">Show Leave Requests:</button>
    <?php 
    require "config.php";
    require "userFunctions.php";
    require "leaveFunctions.php";
    session_start();
    if (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && isset($_SESSION["occupation"])){
        if($_SESSION["role"]==="LEAVE-ADMIN"){

            if (isset($_POST['denyLeave']) && $_POST['denyLeave'] === 'Deny') {
                if(!empty($_POST['denyLeaveID'])){
                    denyLeaveRequest($_POST['denyLeaveID']);
                }
            }
            if (isset($_POST['denyMc']) && $_POST['denyMc'] === 'Deny') {
                if(!empty($_POST['denyMcID'])){
                    denyMcRequest($_POST['denyMcID']);
                }
            }
            if (isset($_POST['approveLeave']) && $_POST['approveLeave'] === 'Approve') {
                if(!empty($_POST['approveLeaveID']) && !empty($_POST['approveLeaveUserId']) && !empty($_POST['approveLeaveStartDate']) && !empty($_POST['approveLeaveEndDate']) && !empty($_POST['approveLeaveDays'])){
                    approveLeaveRequest($_POST['approveLeaveID'],$_POST['approveLeaveUserId'],$_POST['approveLeaveStartDate'],$_POST['approveLeaveEndDate'],$_POST['approveLeaveDays']);
                }
            }
            if (isset($_POST['approveMc']) && $_POST['approveMc'] === 'Approve') {
                if(!empty($_POST['approveMcID']) && !empty($_POST['approveMcUserId']) && !empty($_POST['approveMcStartDate']) && !empty($_POST['approveMcEndDate']) && !empty($_POST['approveMcDays'])){
                    approveMcRequest($_POST['approveMcID'],$_POST['approveMcUserId'],$_POST['approveMcStartDate'],$_POST['approveMcEndDate'],$_POST['approveMcDays']);
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
            else printok("Connecting to $db_hostname");
            // loading of Leave requests
            date_default_timezone_set('Asia/Singapore');
            $query=$con->prepare("SELECT `id`,`userId`,`Days`,`startDate`,`endDate`,`Reason`,`department`,`timeOfSubmission`,`status` FROM workleave WHERE status = -1");
            //AND status = -1
            $query->execute();
            $result = $query-> get_result();
            echo "<div id='toggle1' style='display: none;'><table align='center' border='1'><tr>";
            echo
            "<th>id</th><th>userId</th><th>Days</th><th>startDate</th><th>endDate</th><th>Reason</th><th>Department</th><th>timeOfSubmission</th><th>status</th></tr>";
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                $userId = $row['userId'];
                $Days = $row['Days'];
                $startDate = $row['startDate'];
                $endDate = $row['endDate'];
                $Reason = $row['Reason'];
                $department = $row['department'];
                $timeOfSubmission = $row['timeOfSubmission'];
                $status = $row['status'];
                echo "<tr><th>$id</th><th>$userId</th><th>$Days</th><th>$startDate</th><th>$endDate</th><th>$Reason</th><th>$department</th><th>$timeOfSubmission</th><th>$status</th>
                <th>
                    <form action='authoriseLeave.php' method='POST'>
                        <input type='hidden' name='approveLeaveID' value=".$id.">
                        <input type='hidden' name='approveLeaveUserId' value=".$userId.">
                        <input type='hidden' name='approveLeaveStartDate' value=".strtotime($startDate).">
                        <input type='hidden' name='approveLeaveEndDate' value=".strtotime($endDate).">
                        <input type='hidden' name='approveLeaveDays' value=".$Days.">
                        <input type='submit' name='approveLeave' value='Approve'>
                    </form>
                </th>
                <th>
                    <form action='authoriseLeave.php' method='POST'>
                        <input type='hidden' name='denyLeaveID' value=".$id.">
                        <input type='submit' name='denyLeave' value='Deny'>
                    </form>
                </th></tr>";
            }
            echo "</table></div>";
            // loading of MC requests
            $query2=$con->prepare("SELECT `id`,`userId`,`mcFile`,`Days`,`startDate`,`endDate`,`department`,`timeOfSubmission`,`status` FROM medicalcertificate WHERE status = -1");
            $query2->execute();
            $result2 = $query2-> get_result();
            echo "<div id='toggle2' style='display: none;'><table align='center' border='1'><tr>";
            echo
            "<th>id</th><th>userId</th><th>mcFile</th><th>Days</th><th>starDate</th><th>endDate</th><th>Department</th><th>timeOfSubmission</th><th>status</th></tr>";
            while ($row2 = $result2->fetch_assoc()) {
                $id2 = $row2['id'];
                $userId2 = $row2['userId'];
                $mcFile2 = $row2['mcFile'];
                $Days2 = $row2['Days'];
                $startDate2 = $row2['startDate'];
                $endDate2 = $row2['endDate'];
                $department2 = $row2['department'];
                $timeOfSubmission2 = $row2['timeOfSubmission'];
                $status2 = $row2['status'];
                echo "<th>$id2</th><th>$userId2</th><th>$mcFile2</th><th>$Days2</th><th>$startDate2</th><th>$endDate2</th><th>$department2</th><th>$timeOfSubmission2</th><th>$status2</th>
                <th>
                    <form action='authoriseLeave.php' method='POST'>
                        <input type='hidden' name='approveMcID' value=".$id2.">
                        <input type='hidden' name='approveMcUserId' value=".$userId2.">
                        <input type='hidden' name='approveMcStartDate' value=".strtotime($startDate2).">
                        <input type='hidden' name='approveMcEndDate' value=".strtotime($endDate2).">
                        <input type='hidden' name='approveMcDays' value=".$Days2.">
                        <input type='submit' name='approveMc' value='Approve'>
                    </form>
                </th>
                <th>
                    <form action='authoriseLeave.php' method='POST'>
                        <input type='hidden' name='denyMcID' value=".$id2.">
                        <input type='submit' name='denyMc' value='Deny'>
                    </form>
                </th></tr>";
            }
            echo "</table></div>";
        }
        elseif($_SESSION['occupation'] === "MANAGER"){

            if (isset($_GET['leaveDeny']) && $_GET['leaveDeny'] === 'true') {
                denyLeaveRequest();
            }
            if (isset($_GET['mcDeny']) && $_GET['mcDeny'] === 'true') {
                denyMcRequest();
            }
            if (isset($_GET['leaveApproval']) && $_GET['leaveApproval'] === 'true') {
                approveLeaveRequest();
            }
            if (isset($_GET['mcApproval']) && $_GET['mcApproval'] === 'true') {
                approveMcRequest();
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
            // loading of Leave requests
            $query=$con->prepare("SELECT `id`,`userId`,`Days`,`startDate`,`endDate`,`Reason`,`department`,`timeOfSubmission`,`status` FROM workleave WHERE department = ? AND userId != ? AND status = -1");
            //AND status = -1
            $query->bind_param('si', $_SESSION['department'],$_SESSION['ID']);
            $query->execute();
            $result = $query-> get_result();
            echo "<div id='toggle1' style='display: none;'><table align='center' border='1'><tr>";
            echo
            "<th>id</th><th>userId</th><th>Days</th><th>startDate</th><th>endDate</th><th>Reason</th><th>Department</th><th>timeOfSubmission</th><th>status</th></tr>";
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                $userId = $row['userId'];
                $Days = $row['Days'];
                $startDate = $row['startDate'];
                $endDate = $row['endDate'];
                $Reason = $row['Reason'];
                $department = $row['department'];
                $timeOfSubmission = $row['timeOfSubmission'];
                $status = $row['status'];
                echo "<th>$id</th><th>$userId</th><th>$Days</th><th>$startDate</th><th>$endDate</th><th>$Reason</th><th>$department</th><th>$timeOfSubmission</th><th>$status</th><th><a href='authoriseLeave.php?leaveApproval=true&ALID=".$id."&uid=".$userId."&sd1=".strtotime($startDate)."&ed1=".strtotime($endDate)."&td=".$Days."'>Approve</a></th><th><a href='authoriseLeave.php?leaveDeny=true&DLID=".$id."'>Deny</a></th></tr>";
            }
            echo "</table></div>";
            // loading of MC requests
            $query2=$con->prepare("SELECT `id`,`userId`,`mcFile`,`Days`,`startDate`,`endDate`,`department`,`timeOfSubmission`,`status` FROM medicalcertificate WHERE department = ? AND userId != ? AND status = -1");
            $query2->bind_param('si', $_SESSION['department'],$_SESSION['ID']);
            $query2->execute();
            $result2 = $query2-> get_result();
            echo "<div id='toggle2' style='display: none;'><table align='center' border='1'><tr>";
            echo
            "<th>id</th><th>userId</th><th>mcFile</th><th>Days</th><th>startDate</th><th>endDate</th><th>Department</th><th>timeOfSubmission</th><th>status</th></tr>";
            while ($row2 = $result2->fetch_assoc()) {
                $id2 = $row2['id'];
                $userId2 = $row2['userId'];
                $mcFile2 = $row2['mcFile'];
                $Days2 = $row2['Days'];
                $startDate2 = $row2['startDate'];
                $endDate2 = $row2['endDate'];
                $department2 = $row2['department'];
                $timeOfSubmission2 = $row2['timeOfSubmission'];
                $status2 = $row2['status'];
                echo "<th>$id2</th><th>$userId2</th><th>$mcFile2</th><th>$Days2</th><th>$startDate2</th><th>$endDate2</th><th>$department2</th><th>$timeOfSubmission2</th><th>$status2</th><th><a href='authoriseLeave.php?mcApproval=true&AMCID=".$id2."&uid=".$userId2."&sd2=".strtotime($startDate2)."&ed2=".strtotime($endDate2)."&td=".$Days2."'>Approve</a></th><th><a href='authoriseLeave.php?mcDeny=true&DMCID=".$id2."'>Deny</a></th></tr>";
            }
            echo "</table></div>";
        }
        else{
            echo "Error: Denied access.";
            die();
        }
        // TO DO FOR ADMINISTRATORS version (can delete)
        
    }
    
    ?>
    
</body>
<script>
    function showLeave(){
        var leaveState = document.getElementById("toggle1");
        var mcState = document.getElementById("toggle2");
        if(leaveState.style.display === "none"){
            leaveState.style.display ="block";
            mcState.style.display = "none";
        }
        else{
            leaveState.style.display ="none";
        }
    }

    function showMC(){
        var leaveState = document.getElementById("toggle1");
        var mcState = document.getElementById("toggle2");
        if(mcState.style.display === "none"){
            mcState.style.display ="block";
            leaveState.style.display = "none";
        }
        else{
            mcState.style.display ="none";
        }
    }

    // function showLeave(){
    //     var leaveState = document.getElementById("toggle2");
    // }
</script>
<style>
        th{
            max-width: 200px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
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