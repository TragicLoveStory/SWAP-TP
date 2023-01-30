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
        }
        // loading of thread details
        include "navbar.php";
        $query=$con->prepare("SELECT `safetyTitle`,`safetyContent`,`videoLink`,`createOn`,`lastEdited` from `workplacesafety` WHERE id=?"); 
        $query->bind_param('i',$_GET['safetyID']);
        if($query->execute()){ //executing query (processes and print the results)
            $result = $query->get_result();
            $row = $result->fetch_assoc(); 
            if(!$row){
                echo "Error.";
                include "footer.php";
                die();
            }
        }
    ?>
    <div class='container safetyThreadTable'>
        <table class='forumTable2'>
            <tr>
                <th>Title</th><th>Content</th><th>Video link</th><th>Created on</th><th>Last edited</th>
            </tr>
            <tr>
                <td><?= $row['safetyTitle'] ?></td><td><?= $row['safetyContent'] ?></td><td><?= $row['videoLink'] ?></td><td><?= $row['createOn'] ?></td><td><?= $row['lastEdited'] ?></td>
            </tr>
        </table>
    </div> 
    <?php include "footer.php" ?>
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