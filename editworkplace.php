<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $mysql_host="localhost"; // MySQL's ip address
    $mysql_user="root";
    $mysql_password="";
    $mysql_db="internalhr";

    $con = new mysqli($mysql_host,$mysql_user,$mysql_password,$mysql_db);
    if (!$con){
        echo $con->errno ."<br>";
        die('Could not connect: '. $con->error);
    }
    else {
        echo "Connection to DB server at $mysql_host successful<br>";
    }

    if(isset($_POST['Submit']) && $_POST["Submit"] === "Update"){
        if (!empty($_POST['videoTitle']) &&
            !empty($_POST['videoLink']) &&
            !empty($_POST['id'])
            ) {
                echo "OK: fields are not empty<br>";
            }
            else {
                echo "Error: No fields should be empty<br>";
            }
            $now = new DateTime("now", new DateTimeZone('Asia/Singapore'));
            $now = $now->format('Y-m-d H:i:s');

            $videoTitle=$_POST['videoTitle'];
            $videoLink=$_POST['VideoLink'];
            $id=$_POST['id'];
            
            $query= $con->prepare("UPDATE `workplacesafety` set `videoTitle`=?, `videoLink`=?, `createOn`=? WHERE `id`=?
            ");
            $query->bind_param('sssi', $videoTitle,$videoLink,$now,$id); //bind the parameters
            
            if ($query->execute()){  //execute query
                echo "Query executed.";
                header("location: workplacecrud.php");
            }else{
                echo "Error executing query.";
            }
            
    }
    else{
        echo "NO!";
        echo "NO!";
    }

    if(isset($_GET['Submit']) && $_GET['Submit']==="GetUpdate"){
        $id=$_GET['id'];
        $query=$con->prepare("SELECT `id`, `videoTitle`, `videoLink` FROM `workplacesafety` WHERE id=?");
        $query->bind_param('i', $id); //bind the parameters
        if($query->execute()){
            $result = $query->get_result();
            $row = $result->fetch_assoc();
            if(!$result) {
                die("SELECT query failed<br> ".$con->error);
            }
            else {
                echo "SELECT query successful<br>";
            }
            $nrows=$result->num_rows;
            echo "#rows=$nrows<br>";
        }  
    }
    $uri = $_SERVER['REQUEST_URI'];
    $fullUri = "http://localhost${uri}";
    ?>
    <b>Update</b><br>
    <form action="<?= $fullUri ?>" method="post">
        <table>
        <tr><td>Video Title:</td><td><input type="text" name="videoTitle" value="<?php echo $row['videoTitle']?>"></td></tr>
        <tr><td>Video Link : </td><td><input type="text" name="VideoLink" value="<?php echo $row['videoLink']?>"></td></tr>
        <tr><td></td><td>
        <input type="hidden" name="id" value="<?php echo $row['id']?>">
        <input type="submit" name="Submit" value="Update"></td></tr>
        </table>
    </form>
</body>
</html>

