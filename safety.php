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
    require "safetyFunctions.php";
    session_start();
    if (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && $_SESSION["role"] === "FORUM-ADMIN"){
        if (isset($_POST['deletion']) && $_POST['deletion'] === 'Delete') {
            deleteSafety($_POST['dislikeSafetyID']);
        }
        if (isset($_POST['editing']) && $_POST['editing'] === 'Edit') {
            $_SESSION['safetyID'] = $_POST['editSafetyID'];
            header("Location: https://localhost/SWAP-TP/editSafety.php?editingSafety=true");
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

        include "navbar.php";
        // loading of forum details
        $query=$con->prepare("SELECT * from workplacesafety");
        $query->execute();
        $query->bind_result($id,$safetyTitle, $safetyContent, $videoLink, $createOn, $lastEdited);
        echo "<div class='container listingTable'>
        <p style='margin-bottom: 3rem;'>Table containing workplace safety materials for employees to view. Edit details whenever necessary. Please contact <i>tpamcIT@tp.edu.sg</i> or any IT staff for any inquiries.</p>
        <form action='createSafety.php' method='POST' style='margin-bottom: 3rem;'><input type='submit' value='Create new Safety Thread'></form>
        <table class='hidingTable2'>
        <tr><th>Safety ID</th><th>Title</th><th>Safety Content</th><th>Video link</th><th>Created on</th><th>Last Edited</th></tr>";
        while($query->fetch())
        {
            echo "<tr><td>$id</td><td><a href='safetyThread.php?safetyID=".$id."'>$safetyTitle</a></td><td>$safetyContent</td><td><a href==".$videoLink.">$videoLink</a></td><td>$createOn</td><td>$lastEdited</td>
            <td>
                <form action='Safety.php' method='POST'>
                    <input type='hidden' name='editSafetyID' value=".$id.">
                    <input type='submit' name='editing' value='Edit'>
                </form>
            </td>
            <td>
                <form action='Safety.php' method='POST'>
                    <input type='hidden' name='dislikeSafetyID' value=".$id.">
                    <input type='submit' name='deletion' value='Delete'>
                </form>
            </td></tr>";
        }
        echo "</table></div>";
        include "footer.php";
    }
    elseif (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && $_SESSION["role"] !== "FORUM-ADMIN"){
        include "navbar.php";
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
        // loading of forum details
        $query=$con->prepare("SELECT * from workplacesafety");
        $query->execute();
        $query->bind_result($id,$safetyTitle, $safetyContent, $videoLink, $createOn, $lastEdited);
        echo "<div class='container listingTable'>
        <p style='margin-bottom: 1rem; margin-top: 8rem;'>Workplace safety materials and guidelines for employees to view.<br>Please contact <i>tpamcIT@tp.edu.sg</i> or any IT staff for any inquiries.<br>For any inquiries regarding safety guidelines and procedures, please contact <i>tpamcHR@tp.edu.sg</i> or fill up the contact us form <a href='contactUs.php'>here for further assistance.</a></p>
        <table class='hidingTable2'>
        <tr><th>Safety ID</th><th>Title</th><th>Safety Content</th><th>Video link</th><th>Created on</th><th>Last Edited</th></tr>";
        while($query->fetch())
        {
            echo "<tr><td>$id</td><td><a href='safetyThread.php?safetyID=".$id."'>$safetyTitle</a></td><td>$safetyContent</td><td><a href==".$videoLink.">$videoLink</a></td><td>$createOn</td><td>$lastEdited</td></td></tr>";
        }
        echo "</table></div>";
        include "footer.php";
    }
    else{
        echo "Must be logged in.";
        die();
    }
    ?>
    <style>
        /* th{
            max-width: 150px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        } */
    </style>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<!-- Include every Bootstrap JavaScript plugin and dependency  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://www.w3schools.com/lib/w3.js"></script> <!-- Include EVERY OTHER HTML Files to this file-->
<script>
    //to bring in other HTML on the fly into this page
    w3.includeHTML();
</script>
</html>