<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
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
        elseif(!isset($_GET['editingSafety']) || $_GET['editingSafety'] != "true"){
            echo "Error.";
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
        if(isset($_GET['editingSafety']) && $_GET['editingSafety']==="true"){
            $query=$con->prepare("SELECT `safetyTitle`,`safetyContent`,`videoLink`FROM `workplacesafety` WHERE `id` =?");
            $query->bind_param('i', $_SESSION['safetyID']); //bind the parameters
            $query->execute();
            $result = $query-> get_result();
            $row = $result -> fetch_assoc();
        }  
        if(isset($_POST['Submit']) && $_POST['Submit'] === "Edit Safety Thread"){
            if(!empty($_POST['video']) && !empty($_POST['content']) && !empty($_POST['title'])){
                require_once "safetyFunctions.php";
                editSafetyThread($_POST['title'],$_POST['content'],$_POST['video']);
            }
            else{
                echo "Error: No fields should be empty<br>";
            }
        }
    ?>
    <form action="<?= $fullUri ?>" method='post'>
    <table style='text-align: left; margin-left: auto; margin-right: auto;'>
    <tr>
        <th></th>
        <th style='text-align: center;'>Edit Safety Thread</th>
    </tr>
    <tr>
        <td><label for='title'>Title:</label></td>
        <td><input type='text' name='title' style="width: 375px;" value="<?= $row['safetyTitle']; ?>"><br></td>
    </tr>
    <tr>
        <td><label for='content'>Content:</label></td>
        <td><textarea name="content" rows="8" cols="50" style="resize:none; white-space: pre-wrap;"><?= $row['safetyContent']; ?></textarea><br></td>
    </tr>
    <tr>
        <td><label for='video'>Video Link:</label></td>
        <td><input type='text' name='video' style="width: 375px;" value="<?= $row['videoLink']; ?>"><br></td>
    </tr>
    <tr>
        <td></td>
        <td style='text-align: right;'><input type='submit' value='Edit Safety Thread' name='Submit'></td>
    </tr>  
    </table>
    </form>
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