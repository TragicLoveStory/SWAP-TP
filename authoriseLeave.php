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
    <button onclick="showMC();">Show MC Requests:</button>
    <button onclick="showLeave();">Show Leave Requests:</button>
    <?php 
    require "config.php";
    require "userFunctions.php";
    require "leaveFunctions.php";
    session_start();
    if (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && isset($_SESSION["occupation"])){
        if($_SESSION["role"]==="LEAVE-ADMIN"){
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
                echo "<tr><th>$id</th><th>$userId</th><th>$Days</th><th>$startDate</th><th>$endDate</th><th>$Reason</th><th>$department</th><th>$timeOfSubmission</th><th>$status</th><th><a href='authoriseLeave.php?leaveApproval=true&ALID=".$id."&uid=".$userId."&sd1=".strtotime($startDate)."&ed1=".strtotime($endDate)."&td=".$Days."'>Approve</a></th><th><a href='authoriseLeave.php?leaveDeny=true&DLID=".$id."'>Deny</a></th></tr>";
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
                echo "<th>$id2</th><th>$userId2</th><th>$mcFile2</th><th>$Days2</th><th>$startDate2</th><th>$endDate2</th><th>$department2</th><th>$timeOfSubmission2</th><th>$status2</th><th><a href='authoriseLeave.php?mcApproval=true&AMCID=".$id2."&uid=".$userId2."&sd2=".strtotime($startDate2)."&ed2=".strtotime($endDate2)."&td=".$Days2."'>Approve</a></th><th><a href='authoriseLeave.php?mcDeny=true&DMCID=".$id2."'>Deny</a></th></tr>";
            }
            echo "</table></div>";
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
        }
        elseif($_SESSION['occupation'] === "MANAGER"){
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
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://www.w3schools.com/lib/w3.js"></script> Include EVERY OTHER HTML Files to this file -->
    <!-- <script>
        //to bring in other HTML on the fly into this page
        w3.includeHTML();
    </script> -->
</html>