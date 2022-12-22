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
     require "forumFunctions.php";
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
        else {
            printok("Connecting to $db_hostname");
        }
        // loading of thread details
        $query=$con->prepare("SELECT `safetyTitle`,`safetyContent`,`videoLink`,`createOn`,`lastEdited` from `workplacesafety` WHERE id=?"); 
        $query->bind_param('i',$_GET['safetyID']);
        if($query->execute()){ //executing query (processes and print the results)
            $result = $query->get_result();
            $row = $result->fetch_assoc(); 
        }
    ?>
    <table align='center' border='1'>
            <tr>
                <th>safetyTitle</th><th>safetyContent</th><th>videoLink</th><th>createOn</th><th>lastEdited</th>
            </tr>
            <tr>
                <th><?= $row['safetyTitle'] ?></th><th><?= $row['safetyContent'] ?></th><th><?= $row['videoLink'] ?></th><th><?= $row['createOn'] ?></th><th><?= $row['lastEdited'] ?></th>
            </tr>
        </table>
    <style>
        .Content{
            max-width: 300px;
        }
    </style>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://www.w3schools.com/lib/w3.js"></script> <!-- Include EVERY OTHER HTML Files to this file-->
<script>
    //to bring in other HTML on the fly into this page
    w3.includeHTML();
</script>
</html>