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
</body>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://www.w3schools.com/lib/w3.js"></script> Include EVERY OTHER HTML Files to this file -->
    <!-- <script>
        //to bring in other HTML on the fly into this page
        w3.includeHTML();
    </script> -->
</html>