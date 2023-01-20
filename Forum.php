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
    if (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && $_SESSION["role"] !== "FORUM-ADMIN"){
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
            echo "<table align='center' border='1'><tr>";
            echo
            "<th>email</th><th>title</th><th>createOn</th><th>lastEdited</th><th>viewCount</th></tr>";
            while($query->fetch())
            {
                echo "<tr><th>$email</th><th><a href='forumThread.php?forumID=".$id."'>$title</a></th><th>$createOn</th><th>$lastEdited</th><th>$viewCount</th></tr>";
            }
            echo "</table>";
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
            echo "<table align='center' border='1'><tr>";
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