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
        session_start();
        require "config.php";
        require "userfunctions.php";
        $uri = $_SERVER['REQUEST_URI'];
        $fullUri = "http://localhost${uri}";
        if (!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
            echo "Must be logged in.";
            die();
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
            else printok("Connecting to $db_hostname");
        if(isset($_GET['editing']) && $_GET['editing']==="true"){
            $query=$con->prepare("SELECT `title`,`content`,`userId` FROM `forum` WHERE `id` =?");
            $query->bind_param('i', $_GET['forumID']); //bind the parameters
            $query->execute();
            $result = $query-> get_result();
            $row = $result -> fetch_assoc();
        }  
        if(isset($_POST['Submit']) && $_POST['Submit'] === "Edit Thread"){
            if(!empty($_POST['title']) && !empty($_POST['content'])){
                require_once "forumFunctions.php";
                editThread($_POST['title'],$_POST['content'],$_GET['forumID']);
            }
            else{
                echo "Error: No fields should be empty<br>";
            }
        }
        if($_SESSION['ID'] !== $row['userId']){
            echo "Access forbidden. Unable to edit other user's threads";
            die();
        }
    ?>
    <form action="<?= $fullUri ?>" method='post'>
    <table style='text-align: left; margin-left: auto; margin-right: auto;'>
    <tr>
        <th></th>
        <th style='text-align: center;'>Edit Thread</th>
    </tr>
    <tr>
        <td><label for='title'>Title:</label></td>
        <td><input type='text' name='title' value="<?= $row['title'] ?>"><br></td>
    </tr>
    <tr>
        <td><label for='content'>Content:</label></td>
        <td><textarea name="content" rows="8" cols="50" style="resize:none"><?= $row['content'] ?></textarea><br></td>
    </tr>
    <tr>
        <td></td>
        <td style='text-align: right;'><input type='submit' value='Edit Thread' name='Submit'></td>
    </tr>  
    </table>
    </form>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://www.w3schools.com/lib/w3.js"></script> <!-- Include EVERY OTHER HTML Files to this file-->
<script>
    //to bring in other HTML on the fly into this page
    w3.includeHTML();
</script>
</html>