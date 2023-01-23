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
    session_start();
    if (!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
       echo "Must be logged in.";
        die();

    }

    if(isset($_POST['Submit']) && $_POST['Submit'] === "Submit Work Leave"){
        if(!empty($_POST['submitLeave']) && !empty($_POST['startDate']) && !empty($_POST['endDate']) && !empty($_POST['reasonForLeave'])){
            submitLeave($_POST['submitLeave'],$_POST['startDate'],$_POST['endDate'],$_POST['reasonForLeave']);
        }  
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
    include 'navbar.php';


    ?>

    <form action="submitLeave.php" method="post" style="text-align: center;">
        <label for='submitLeave'>Number of days for work leave:</label>
        <input type="number" id="submitLeave" name="submitLeave" min="1" max="60"><br>
        <label for='startDate'>Start Date</label>
        <input type='date' name='startDate'  min="1900-01-01" value="<?= date('Y-m-d'); ?>"><br>
        <label for='endDate'>End Date</label>
        <input type='date' name='endDate'  min="1900-01-01" value="<?= date('Y-m-d'); ?>"><br>
        <label for='reasonForLeave'>Reason for work leave:</label>
        <input type="text" id="reasonForLeave" name="reasonForLeave"><br>
        <input type="submit" value="Submit Work Leave" name="Submit">
    </form>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br><br><br><br><br><br>
    <?php
    include 'footer.php';
    ?>
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