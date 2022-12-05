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
    require "mcFunctions.php";
    session_start();
    //if (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && $_SESSION["role"]==="ADMIN"){
        //echo "Permitted for admins";
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
        // loading of user details
        $query=$con->prepare("select * from medicalcertificate");
        $query->execute();
        $query->bind_result($id, $fullname, $mcfile, $time);
        echo "<table align='center' border='1'><tr>";
        echo
        "<th>Id</th><th>Full Name</th><th>MC</th><th>Time Submitted</th></tr>";
        while($query->fetch())
        {
            echo "<th>$id</th><th>$fullname</th><th>$mcfile</th><th>$time</th><th><a href='editMc.php?editing=true&TheUserId=".$id."'>edit</a></th><th><a href='mcList.php?deletion=true&fullname=".$fullname."'>delete</a></th></tr>";
        }
        echo "</table>";

        // deletion of accounts
        function deleteItem() {
            require "config.php";
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
            $uri = $_SERVER['REQUEST_URI'];
            $fullUri = "http://localhost${uri}";
            $url_components = parse_url($fullUri);
            parse_str($url_components['query'], $params);
            $fullname = $params['fullname'];
            $query=$con->prepare("DELETE FROM medicalcertificate WHERE fullName=?");
            $query->bind_param('s', $fullname); //bind the parameters
            if($query->execute()){ //executing query (processes and print the results)
                header("Location: http://localhost/SWAP-TP/mcList.php");
                die();
            }
            else{
                echo "Error Executing Query";
            }
        }

        if (isset($_GET['deletion']) && $_GET['deletion'] === 'true') {
            deleteItem();
        }
    //}
    //else{
        //echo "Only permitted for Admins.";
    //}
    ?>
    
</body>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://www.w3schools.com/lib/w3.js"></script> Include EVERY OTHER HTML Files to this file -->
    <!-- <script>
        //to bring in other HTML on the fly into this page
        w3.includeHTML();
    </script> -->
</html>