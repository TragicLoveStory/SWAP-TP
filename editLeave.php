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
    require "leaveFunctions.php";
    require "config.php";
    require "userFunctions.php";
    session_start();
    if (!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
       echo "Must be logged in.";
        die();
    }
    elseif(!isset($_GET['leaveEditing']) || $_GET['leaveEditing'] != "true"){
        echo "Error.";
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
    else printok("Connecting to $db_hostname");
    $query=$con->prepare("SELECT `Days`,`Reason` from workleave WHERE `id`=?");
    $query->bind_param('i',$_SESSION['leaveID']);
    $query->execute();
    $result = $query-> get_result();
    $row = $result -> fetch_assoc();
    if(!$row){
        echo "access forbidden";
        die();
    }
    $uri = $_SERVER['REQUEST_URI'];
    $fullUri = "http://localhost${uri}";
    if(isset($_POST['Submit']) && $_POST['Submit'] === "Edit Work Leave"){
        if(!empty($_POST['editLeave']) && !empty($_POST['startDate']) && !empty($_POST['endDate']) && !empty($_POST['reasonForLeave'])){
            editLeave($_POST['editLeave'],$_POST['startDate'],$_POST['endDate'],$_POST['reasonForLeave']);
        }
        
    }
    ?>
    <form action="<?= $fullUri ?>" method="post" style="text-align: center;">
        <label for='editLeave'>Number of days for work leave:</label>
        <input type="number" id="editLeave" name="editLeave" min="1" max="60" value="<?= $row['Days'] ?>"><br>
        <label for='startDate'>Start Date</label>
        <input type='date' name='startDate'  min="1900-01-01" value="<?= date('Y-m-d'); ?>"><br>
        <label for='endDate'>End Date</label>
        <input type='date' name='endDate'  min="1900-01-01" value="<?= date('Y-m-d'); ?>"><br>
        <label for='reasonForLeave'>Reason for work leave:</label>
        <input type="text" id="reasonForLeave" name="reasonForLeave" value="<?= $row['Reason'] ?>"><br>
        <input type="submit" value="Edit Work Leave" name="Submit">
    </form>
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