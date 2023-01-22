<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>

    
    <?php 
    require "config.php";
    require "userFunctions.php";
    require "forumFunctions.php";
    session_start();
    if (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && $_SESSION["role"] !== "FORUM-ADMIN"){
        if(isset($_POST['createForumPost']) && $_POST['createForumPost']=== "Create a forum post"){
            header("Location: http://localhost/SWAP-TP/createThread.php");
            die();
        }
        include "navbar.php";
        echo '<br><form action="Forum.php" method="POST"><input type="submit" value="Create a forum post" name="createForumPost" ></form><br><br>';
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
            //else printok("Connecting to $db_hostname");


        // loading of forum details
        $query=$con->prepare("SELECT forum.id, users.email, forum.title, forum.createOn, forum.lastEdited, forum.viewCount, forum.status FROM forum INNER JOIN users ON forum.userId = users.ID");
        $query->execute();
            $query->bind_result($id, $email, $title, $createOn, $lastEdited, $viewCount,$status);
            echo "<table class = 'table'
            
            align='center' 
            border='0' 
            height='400' 
            WIDTH='1500'><tr>";



            echo "<th>email</th>
                  <th>title</th>
                  <th>createOn</th>
                  <th>lastEdited</th>
                  <th>viewCount</th></tr>";
            while($query->fetch())
            {
                echo "<tr><th>$email</th><th><a href='forumThread.php?forumID=".$id."'>$title</a></th><th>$createOn</th><th>$lastEdited</th><th>$viewCount</th></tr>";
            }
            echo "</table><br><br>";

    }
    elseif(isset($_SESSION["ID"]) && isset($_SESSION["role"]) && $_SESSION["role"] === "FORUM-ADMIN"){
        if (isset($_POST['deletion']) && $_POST['deletion'] === 'Delete') {
            if(!empty($_POST['FD'])){
                deleteThread($_POST['FD']);
            }
            else{
                echo "Error.";
                die();
            }     
        }
        if (isset($_POST['archive']) && $_POST['archive'] === 'Archive') {
            if(!empty($_POST['FD']) && !empty($_POST['archiveStatus'])){
                archiveThread($_POST['archiveStatus'],$_POST['FD']);
            }
            else{
                echo "Error.";
                die();
            }
            
        }
        if (isset($_POST['archive']) && $_POST['archive'] === 'Unarchive') {
            if(!empty($_POST['FD']) && !empty($_POST['archiveStatus'])){
                archiveThread($_POST['archiveStatus'],$_POST['FD']);
            }
            else{
                echo "Error.";
                die();
            }
        }
        if(isset($_POST['createForumPost']) && $_POST['createForumPost']=== "Create a forum post"){
            header("Location: http://localhost/SWAP-TP/createThread.php");
            die();
        }
        echo '<form action="Forum.php" method="POST"><input type="submit" value="Create a forum post" name="createForumPost"></form>';
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
        $query=$con->prepare("SELECT forum.id, users.email, forum.title, forum.createOn, forum.lastEdited, forum.viewCount, forum.status FROM forum INNER JOIN users ON forum.userId = users.ID");
        $query->execute();
            $query->bind_result($id, $email, $title, $createOn, $lastEdited, $viewCount,$status);
            echo
            "<th>id</th><th>email</th><th>title</th><th>createOn</th><th>lastEdited</th><th>viewCount</th><th>Status</th></tr>";
            while($query->fetch())
            {
                if($status == 1){
                    $archiveState = "Archive";
                }
                elseif($status == 0){
                    $archiveState = "Unarchive";
                }
                echo "<tr><th>$id</th><th>$email</th><th><a href='forumThread.php?forumID=".$id."'>$title</a></th><th>$createOn</th><th>$lastEdited</th><th>$viewCount</th><th>$status</th>
                <th><form action='Forum.php' method='POST'><input type='hidden' name='archiveStatus' value=".$status."><input type='hidden' name='FD' value=".$id."><input type='submit' name='archive' value=".$archiveState."><input type='submit' name='deletion' value='Delete'></form></th></tr>";
            }
            echo "</table>";
    }
    else{
        echo "Must be logged in.";
        die();
    }
    include "footer.php";
    ?>


    <style>
        th{
            max-width: 500px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        form{
            float: right;
            padding-right: 10px;
        }
    </style>
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