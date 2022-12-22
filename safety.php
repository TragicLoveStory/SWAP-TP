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
    require "safetyFunctions.php";
    session_start();
    if (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && $_SESSION["role"] === "FORUM-ADMIN"){
        echo '<form action="createSafety.php" method="POST"><input type="submit" value="Create new Safety Thread"></form>';
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
        // loading of forum details
        $query=$con->prepare("SELECT * from workplacesafety");
        $query->execute();
            $query->bind_result($id,$safetyTitle, $safetyContent, $videoLink, $createOn, $lastEdited);
            echo "<table align='center' border='1'><tr>";
            echo
            "<th>id</th><th>safetyTitle</th><th>safetyContent</th><th>videoLink</th><th>createOn</th><th>lastEdited</th></tr>";
            while($query->fetch())
            {
                echo "<tr><th>$id</th><th><a href='safetyThread.php?safetyID=".$id."'>$safetyTitle</a></th><th>$safetyContent</th><th>$videoLink</th><th>$createOn</th><th>$lastEdited</th><th><a href='editSafety.php?editing=true&safetyID=".$id."'>Edit</a></th><th><a href='Safety.php?deletion=true&safetyID=".$id."'>Delete</a></th></tr>";
            }
            echo "</table>";
        if (isset($_GET['deletion']) && $_GET['deletion'] === 'true') {
            deleteSafety();
        }
}
    elseif (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && $_SESSION["role"] !== "FORUM-ADMIN"){
        echo '<form action="createSafety.php" method="POST"><input type="submit" value="Create new Workplace Safety Thread"></form>';
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
        // loading of forum details
        $query=$con->prepare("SELECT * from workplacesafety");
        $query->execute();
            $query->bind_result($id,$safetyTitle, $safetyContent, $videoLink, $createOn, $lastEdited);
            echo "<table align='center' border='1'><tr>";
            echo
            "<th>id</th><th>safetyTitle</th><th>safetyContent</th><th>videoLink</th><th>createOn</th><th>lastEdited</th></tr>";
            while($query->fetch())
            {
                echo "<tr><th>$id</th><th>$safetyTitle</th><th>$safetyContent</th><th>$videoLink</th><th>$createOn</th><th>$lastEdited</th></tr>";
            }
            echo "</table>";
    }
    else{
        echo "Must be logged in.";
        die();
    }
    ?>
    <style>
        th{
            max-width: 150px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
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